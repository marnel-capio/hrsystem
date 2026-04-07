<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportApplicationsRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionBatchModel;
use App\Models\IntermediateApplicant;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ApplicationImportController extends Controller
{
    public function create()
    {
        $actionBatches = ActionBatchModel::getWithTargetLocationAndApplications();

        return inertia('action/applications/ActionApplicationList', [
            'errorsConfig' => [
                'field_required' => config('errors.field_required')['errorMessage'],
                'max_length_exceeded' => config('errors.max_length_exceeded')['errorMessage'],
                'file_too_large' => config('errors.file_too_large')['errorMessage'],
            ],
            'applications' => $actionBatches->flatMap(function ($batch) {
                return $batch->applications->map(function ($app) use ($batch) {
                    return [
                        'id' => $app->id,
                        'action_applicant_id' => $app->action_applicant_id,
                        'action_batch_id' => $app->action_batch_id,
                        'first_name' => $app->applicant->first_name,
                        'last_name' => $app->applicant->last_name,
                        'action_batch' => $batch->action_batch,
                        'target_location' => $batch->resourceSchedule->target_location ?? null,
                    ];
                });
            }),
            'actionBatches' => $actionBatches,
            'filters' => ['search' => request('search', '')],
            'userPermissions' => auth()->user()->permissions ?? 0,
        ]);
    }

    public function import(ImportApplicationsRequest $request)
    {

        $validated = $request->validated();

        $batch = ActionBatchModel::with('resourceSchedule')
            ->find($validated['batch_id']);

        $batchTargetLocation = $batch->resourceSchedule->target_location ?? null;

        $file = $request->file('file');
        $rows = $this->parseFile($file);

        $config = config('constants');
        $sourceTypeMap = $config['source_type'];
        $sourceMap = $config['source'];
        $genderMap = $config['gender'];
        $examStatusMap = $config['exam_status'];

        $importedApplicants = [];
        $skippedApplicants = [];
        $failedApplicants = [];
        $now = now()->format('Y-m-d H:i:s');

        foreach ($rows as $rowIndex => $row) {
            if (empty(array_filter($row))) {
                $skippedApplicants[] = 'Row '.($rowIndex + 2).' - Empty row';

                continue;
            }

            $name = trim($row['Full Name (Last Name, First Name, Middle Initial)'] ?? '');
            if (empty($name)) {
                $skippedApplicants[] = 'Row '.($rowIndex + 2).' - No name found';

                continue;
            }

            try {
                DB::beginTransaction();

                // -------------------------
                // PARSE EXCEL TIMESTAMP
                // -------------------------
                $rawTimestamp = trim($row['Timestamp'] ?? '');
                $createdTime = now()->format('Y-m-d H:i:s'); // default fallback
                if (! empty($rawTimestamp)) {
                    // Attempt d/m/Y H:i:s
                    $ts = \DateTime::createFromFormat('d/m/Y H:i:s', $rawTimestamp);
                    if ($ts === false) {
                        // fallback to strtotime in case Excel formatted differently
                        $parsed = strtotime($rawTimestamp);
                        if ($parsed !== false) {
                            $ts = new \DateTime;
                            $ts->setTimestamp($parsed);
                        }
                    }
                    if ($ts !== false) {
                        $createdTime = $ts->format('Y-m-d H:i:s');
                    }
                }
                \Log::debug('Parsed timestamp', ['row' => $rowIndex + 2, 'createdTime' => $createdTime]);

                // -------------------------
                // REQUIRED FIELDS CHECK
                // -------------------------
                $hasGender = ! empty(trim($row['Gender'] ?? ''));
                $hasSource = ! empty(trim($row['From what recruitment channel have you applied for this job?'] ?? ''));
                $hasResume = ! empty(trim($row['Upload your updated resume'] ?? ''));

                if (! $hasGender) {
                    $skippedApplicants[] = "{$name} - Missing gender";
                    DB::rollBack();

                    continue;
                }
                if (! $hasSource) {
                    $skippedApplicants[] = "{$name} - Missing source";
                    DB::rollBack();

                    continue;
                }
                if (! $hasResume) {
                    $skippedApplicants[] = "{$name} - Missing resume";
                    DB::rollBack();

                    continue;
                }

                // -------------------------
                // GENDER & SOURCE MAPPING
                // -------------------------
                $rawGender = strtolower(preg_replace('/\s+/', '', $row['Gender']));
                $gender = $genderMap[$rawGender] ?? null;
                if (is_null($gender)) {
                    $skippedApplicants[] = "{$name} - Invalid gender";
                    DB::rollBack();

                    continue;
                }

                $config = config('constants');
                $recruitmentPortalKeywords = $config['recruitment_portal_keywords'];
                $sourceMap = $config['source_map'];

                $rawSource = trim($row['From what recruitment channel have you applied for this job?']);
                \Log::info('Source value:', ['rawSource' => $rawSource]);

                if (in_array($rawSource, $recruitmentPortalKeywords)) {
                    // Recruitment Portal
                    $source_type = 3;
                    $source = $sourceMap[$rawSource];
                    $other_source = null;
                } elseif ($rawSource === 'Referral (Employee Referral or Applicant Referral)') {
                    // Employee Referral
                    $source_type = 4;
                    $source = null;
                    $other_source = $rawSource;
                } elseif ($rawSource === 'Campus Recruitment Activity') {
                    // Campus Recruitment
                    $source_type = 1;
                    $source = null;
                    $other_source = $rawSource;
                } else {
                    // Everything else goes to other_source with null source_type and source
                    $source_type = null;
                    $source = null;
                    $other_source = $rawSource;
                }

                \Log::info('Result after assignment', [
                    'source_type' => $source_type,
                    'source' => $source,
                    'other_source' => $other_source,
                ]);

                // -------------------------
                // EXAM STATUS & DATE
                // -------------------------
                $rawExamStatus = trim(strtolower($row['Results'] ?? ''));
                $examApplicationStatusFromExcel = $examStatusMap[$rawExamStatus] ?? 1;

                $rawDate = trim($row['Exam Schedule.'] ?? '');
                $exam_plan_date = null;
                if (! empty($rawDate)) {
                    $ts = strtotime($rawDate);
                    if ($ts !== false) {
                        $exam_plan_date = date('Y-m-d H:i:s', $ts);
                    }
                }

                // -------------------------
                // DECIDE APPLICANT TYPE
                // -------------------------
                $email = trim($row['Email Address'] ?? '');
                $existingApplicant = ActionApplicant::where('email_address', $email)->first();
                $lastApplication = $existingApplicant
                    ? ActionApplication::where('action_applicant_id', $existingApplicant->id)
                        ->orderBy('source_date', 'desc')
                        ->first()
                    : null;

                $exam_application_status = $examApplicationStatusFromExcel;
                $initial_interview_application_status = null;
                $failedExamStatuses = [6, 7];
                $failedInitialStatuses = [5];
                $currentRowTime = Carbon::parse($createdTime);
                $sixMonthsAgo = $currentRowTime->copy()->subMonths(6);

                if (! $existingApplicant) {
                    // New applicant
                    $applicant = ActionApplicant::updateOrCreateFromRow(
                        $row, $gender, $source_type, $source, $other_source, $createdTime, now()->format('Y-m-d H:i:s')
                    );
                } elseif ($lastApplication) {
                    $applicant = $existingApplicant;
                    $lastAppTime = Carbon::parse($lastApplication->source_date);

                    $isFailed = in_array($lastApplication->exam_application_status, $failedExamStatuses) ||
                                in_array($lastApplication->initial_interview_application_status, $failedInitialStatuses);

                    if ($isFailed && $lastAppTime > $sixMonthsAgo) {
                        $skippedApplicants[] = "{$name} - Failed within last 6 months, cannot reapply yet.";
                        DB::rollBack();

                        continue;
                    } elseif ($isFailed && $lastAppTime <= $sixMonthsAgo) {
                        // New application, no statuses
                    } elseif (! $isFailed && $lastAppTime > $sixMonthsAgo) {
                        $initial_interview_application_status = 1;
                    }
                }

                \Log::info('Exam status mapping', [
                    'rawExamStatus' => $rawExamStatus,
                    'mapped' => $examApplicationStatusFromExcel,
                ]);

                // -------------------------
                // CREATE APPLICATION
                // -------------------------
                ActionApplication::updateOrCreateFromRow(
                    $applicant->id,
                    $request->batch_id,
                    $row,
                    $exam_application_status,
                    $exam_plan_date,
                    now()->format('Y-m-d H:i:s'),
                    $batchTargetLocation,
                    $createdTime
                );

                DB::commit();
                $importedApplicants[] = mb_convert_encoding($name, 'UTF-8', 'UTF-8');

                \Log::info('Saving application', [
                    'name' => $name,
                    'exam_application_status' => $exam_application_status,
                ]);

            } catch (\Throwable $e) {
                DB::rollBack();

                \Log::error('Application import failed', [
                    'row' => $rowIndex + 2,
                    'name' => $name,
                    'email' => $row['Email Address'] ?? null,
                    'source' => $row['From what recruitment channel have you applied for this job?'] ?? null,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);

                $failedApplicants[] = "{$name} - ".$e->getMessage();
            }
        }
        // -------------------------
        // Prepare messages that show on screen & logging
        // -------------------------
        $totalRows = count($rows);

        $successMsg = '';
        if (! empty($importedApplicants)) {
            $successList = [];
            foreach ($importedApplicants as $index => $name) {
                $successList[] = ($index + 1).'. '.$name;
            }
            $successMsg = 'Count of Successful Uploads: '.count($importedApplicants)."\n\n".
                        config('errors.successful_action_application_import.errorMessage')."\n".
                        implode("\n", $successList)."\n\n";
        }

        $allFailed = array_merge($failedApplicants, $skippedApplicants);
        $errorMsg = '';
        if (! empty($allFailed)) {
            $errorList = [];
            foreach ($allFailed as $index => $name) {
                $errorList[] = ($index + 1).'. '.$name;
            }
            $errorMsg = 'Count of Failed Uploads: '.count($allFailed)."\n\n".
                        config('errors.failed_action_application_import.errorMessage')."\n".
                        implode("\n", $errorList)."\n\n";
        }

        // Log after all imports
        $user = Auth::user();
        $logMessage = "Imported ACTION applications and applicants. Total rows: {$totalRows}, Success: ".count($importedApplicants).
                    ', Failed: '.count($allFailed);
        Log::createLog('ACTION', $logMessage, $user->id);

        return back()->with([
            'success' => $successMsg ?: null,
            'error' => $errorMsg ?: null,
        ]);
    }

    private function parseFile($file)
    {
        $mimeType = $file->getMimeType();
        $filePath = $file->getRealPath();

        try {
            if ($mimeType === 'text/csv' || $mimeType === 'text/plain') {
                $handle = fopen($filePath, 'r');
                $rows = [];
                $header = fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    $rows[] = array_combine($header, $row) ?: [];
                }
                fclose($handle);
            } else {
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rowsArray = $worksheet->toArray(null, true, true, true);
                $header = array_shift($rowsArray);
                $rows = [];
                foreach ($rowsArray as $rowData) {
                    $row = [];
                    foreach ($header as $i => $h) {
                        $row[$h] = $rowData[$i] ?? '';
                    }
                    $rows[] = $row;
                }
            }

            return $rows;

        } catch (\Exception $e) {
            throw new \Exception('Parse error: '.$e->getMessage());
        }
    }

    public function importIntermediateApplicants(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $file = $request->file('file');
        $rows = $this->parseFileIntermediate($file);

        $imported = [];
        $skipped = [];
        $failed = [];
        \Loga::debug($rows[0]);

        foreach ($rows as $index => $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                $skipped[] = 'Row '.($index + 2).' - Empty row';

                continue;
            }

            $email = trim($row['Email Address'] ?? '');
            $fullName = trim($row['Name (Last Name, Given Name, Middle Name)'] ?? '');

            if (empty($email) || empty($fullName)) {
                $skipped[] = 'Row '.($index + 2).' - Missing email or name';

                continue;
            }

            // -------------------------
            // 1. PARSE NAME
            // -------------------------
            $nameParts = array_map('trim', explode(',', $fullName));
            $lastName = $nameParts[0] ?? null;
            $firstName = $nameParts[1] ?? null;
            $middleName = $nameParts[2] ?? null;

            DB::beginTransaction();
            try {
                // -------------------------
                // 2. CREATE OR UPDATE APPLICANT
                // -------------------------
                $applicant = IntermediateApplicant::updateOrCreate(
                    ['email_address' => $email],
                    [
                        'first_name' => $firstName,
                        'middle_name' => $middleName,
                        'last_name' => $lastName,
                        'address' => $row['Address'] ?? null,
                        'contact_no' => $row['Contact Number'] ?? null,
                        'birthdate' => $row['Birthday'] ?? null,
                        'age' => $row['Age'] ?? null,
                        'school_graduated_from' => $row['School Graduated from'] ?? null,
                        'course' => $row['Course/Degree taken'] ?? null,
                        'year_attended' => $row['Inclusive Year Attended'] ?? null,
                        'spouse_details' => $row['SPOUSE'] ?? null,
                        'children' => is_numeric($row['CHILDREN']) ? (int)$row['CHILDREN'] : 0,
                        'father_details' => $row['FATHER'] ?? null,
                        'mother_details' => $row['MOTHER'] ?? null,
                        'sibling_details' => $row['SIBLING/S'] ?? null,
                        'emergency_contact_name' => $row['Person to notify in case of emergency:'] ?? null,
                        'emergency_contact_number' => $row['Contact Details'] ?? null,
                        'emergency_contact_address' => $row['Address'] ?? null,
                        'registered_date' => $this->parseExcelDate($row['Timestamp'] ?? null),
                        'registered_by' => auth()->id(),
                    ]
                );

                // -------------------------
                // 3. APPLICATION HISTORY
                // -------------------------
                $applicant->applications()->create([
                    'position' => $row['Position you are applying for'] ?? null,
                    'application_stage' => 1, // New
                    'fy_week' => 1,
                    'availability_date' => $this->parseExcelDate($row['Date Available to Report to Work'] ?? null),
                    'desired_salary_range' => $row['Desired Salary Range'] ?? null,
                    'work_preference' => $row['Please check your work preference:'] ?? null,
                    'basic_pay' => $row['Basic Pay'] ?? null,
                    'bonuses' => $row['Bonuses'] ?? null,
                    'hmo' => $row['HMO'] ?? null,
                    'leaves' => $row['Vacation Leaves/ Sick Leavse'] ?? null,
                    'allowances' => $row['Allowances'] ?? null,
                    'other_benefits' => $row['Other Benefits'] ?? null,
                    'answer_q1' => $this->mapYesNo($row['Please answer the following questions below: [Have you ever filed an application in AWS, Inc. before?]'] ?? null),
                    'answer_q2' => $this->mapYesNo($row['Please answer the following questions below: [Do any of your friends or relatives, other than a spouse, work in AWS>]'] ?? null),
                    'answer_q3' => $this->mapYesNo($row['Please answer the following questions below: [Have you worked in AWS before?]'] ?? null),
                    'answer_q4' => $this->mapYesNo($row['Please answer the following questions below: [Will you travel if the job requires it?]'] ?? null),
                    'answer_q5' => $this->mapYesNo($row['Please answer the following questions below: [Will you work overtime if needed?]'] ?? null),
                    'answer_q6' => $this->mapYesNo($row['Please answer the following questions below: [Have you been convicted of a crime?]'] ?? null),
                    'answer_q7' => $this->mapYesNo($row['Please answer the following questions below: [Do you have any physical illness or impairments?]'] ?? null),
                ]);

                // -------------------------
                // 4. WORK EXPERIENCES
                // -------------------------

                // Mark existing work experiences as deleted
                $applicant->workExperiences()->update(['is_deleted' => 1]);

                $workBlocks = [
                    ['Employer (Company Name)', 'Company Address', 'Job Title', 'Dates Employed', 'Work Description/ Responsibilities', 'Salary', 'Reason for Leaving', 'Name of Supervisor/Team Lead and Contact Number'],
                    ['Employer (Company Name)2', 'Company Address2', 'Job Title2', 'Dates Employed2', 'Work Description/ Responsibilities2', 'Salary2', 'Reason for Leaving2', 'Name of Supervisor/Team Lead and Contact Number2'],
                    ['Employer (Company Name)3', 'Company Address3', 'Job Title3', 'Dates Employed3', 'Work Description/ Responsibilities3', 'Salary3', 'Reason for Leaving3', 'Name of Supervisor/Team Lead and Contact Number3'],
                ];

                foreach ($workBlocks as $block) {
                    // Check if this work experience has an employer
                    if (! empty($row[$block[0]])) {
                        $applicant->workExperiences()->create([
                            'employer' => $row[$block[0]] ?? null,
                            'company_address' => $row[$block[1]] ?? null,
                            'job_title' => $row[$block[2]] ?? null,
                            'date_employed' => $this->parseExcelDate($row[$block[3]] ?? null),
                            'work_description' => $row[$block[4]] ?? null,
                            'salary' => $row[$block[5]] ?? null,
                            'reason_for_leaving' => $row[$block[6]] ?? null,
                            'name_supervisor' => $row[$block[7]] ?? null,
                            'is_deleted' => 0,
                            'created_by' => auth()->id() ?? 1,
                            'created_time' => now(),
                            'updated_by' => auth()->id() ?? 1,
                            'updated_time' => now(),
                        ]);
                    }
                }

                DB::commit();
                $imported[] = $fullName;

            } catch (\Throwable $e) {
                DB::rollBack();
                $failed[] = 'Row '.($index + 2)." ({$fullName}) - ".$e->getMessage();
            }
        }

        // Return JSON for Inertia
        return response()->json([
            'success' => count($imported) ? 'Imported: '.implode(', ', $imported) : null,
            'error' => count($failed) ? 'Failed: '.implode(', ', $failed) : null,
            'skipped' => count($skipped) ? 'Skipped: '.implode(', ', $skipped) : null,
        ]);
    }

    /**
     * Convert Yes/No to 1/0
     */
    private function mapYesNo($value)
    {
        $value = strtolower(trim($value ?? ''));

        return in_array($value, ['yes', '1']) ? 1 : 0;
    }

    /**
     * Parse Excel/CSV file for Intermediate Applicants
     */
    private function parseFileIntermediate($file)
    {
        $mime = $file->getMimeType();
        $path = $file->getRealPath();

        $rows = [];

        if (in_array($mime, ['text/csv', 'text/plain'])) {
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle);
            while ($row = fgetcsv($handle)) {
                $rows[] = array_combine($header, $row);
            }
            fclose($handle);
        } else {
            $spreadsheet = IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $arrayData = $worksheet->toArray(null, true, true, true);
            $header = array_shift($arrayData);

            foreach ($arrayData as $rowData) {
                $row = [];
                foreach ($header as $i => $h) {
                    $row[$h] = $rowData[$i] ?? '';
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

    protected function parseExcelDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // If it’s already a Carbon/DateTime instance
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        // If numeric (Excel serial date)
        if (is_numeric($value)) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value));
            } catch (\Throwable $e) {
                return null;
            }
        }

        // Otherwise, try parsing string normally
        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
