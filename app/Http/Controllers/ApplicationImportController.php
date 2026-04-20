<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportApplicationsRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionBatchModel;
use App\Models\Log;
use App\Services\IntermediateApplicantImportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Http\Requests\ImportIntermediateApplicationRequest;
use Inertia\Inertia;
use Inertia\Response;
use App\Mail\UploadStatusReportMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;





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




    public function getTotalApplicants(int $batchId): int
    {
        // Count applicants already existing in the ActionApplication for the given action_batch_id
        $existingApplicantsCount = ActionApplication::where('action_batch_id', $batchId)->count();


        // Total count = Existing applicants for this batch + Newly imported applicants
        return $existingApplicantsCount;
    }
    public function getNewApplicants(array $importedApplicants): int
    {
        return count($importedApplicants);
    }
    
    public function getExistingApplicants(int $batchId, array $importedApplicants): int
    {
        $totalApplicants = $this->getTotalApplicants($batchId);
        
        $newApplicants = $this->getNewApplicants($importedApplicants);

        return $totalApplicants - $newApplicants;
    }

    public function getFailedUploads(array $failedApplicants, array $skippedApplicants): int
    {
        return count($failedApplicants) + count($skippedApplicants);
    }

    public function getLoggedUserName()
{
    $user = Auth::user(); 
    return $user ? $user->name : 'Unknown User';
}
    

    public function import(ImportApplicationsRequest $request)
    {
        $user = Auth::user();
        $userName = $this->getLoggedUserName();
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
        $oldApplicants = [];
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

        $totalApplicants = $this->getTotalApplicants($request->batch_id);
        $newApplicants = $this->getNewApplicants($importedApplicants);
        $existingApplicants = $this->getExistingApplicants($request->batch_id, $importedApplicants);
        $failedUploads = $this->getFailedUploads($failedApplicants, $skippedApplicants); 

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
        $emails = $this->getHrAdminEmails();

        $batchName = ActionBatchModel::where('id', $request->batch_id)
            ->value('action_batch');

        if (!empty($emails)) {
            try {
                Mail::to($emails)->send(
                    new UploadStatusReportMail(
                        $batchName, 
                        [
                            'senderName' => $userName, 
                            'senderRole' => '$', 
                        ], 
                        $totalApplicants,
                        $newApplicants,
                        $existingApplicants,
                        $failedUploads  
                    )
                );
            } catch (\Exception $e) {
                \Log::error('Upload status report email failed', [
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return back()->with([
            'success' => $successMsg ?: null,
            'error' => $errorMsg ?: null,
            'userPermissions' => auth()->user()->permissions
        ]);
    }

    private function getHrAdminEmails(): array
{
    $permissionIds = [
        config('constants.HR_ADMIN_PERMISSION.value'),
    ];

    return User::query()
        ->whereIn('permissions', $permissionIds)
        ->whereNotNull('email_address')
        ->pluck('email_address')
        ->unique()
        ->values()
        ->toArray();
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

    public function importIntermediateApplicants(ImportIntermediateApplicationRequest  $request)
    {
        $file = $request->file('file');
        $importService = new IntermediateApplicantImportService;

        $results = $importService->processFileImport($file);

        $importedApplicants = $results['imported'];
        $allFailed = $results['failed'];

        $totalRows = count($importedApplicants) + count($allFailed);

        // LOG AFTER ALL IMPORTS
        $user = Auth::user();

        $logMessage =
            'Imported Intermediate Applications and Applicants. '.
            "Total rows: {$totalRows}. ".
            'Success: '.count($importedApplicants).', '.
            'Failed: '.count($allFailed);

        Log::createLog('Intermediate', $logMessage, $user->id);

        return back()->with([
            'success' => count($importedApplicants)
                ? $this->formatSuccessMessage($importedApplicants)
                : null,

            'error' => count($allFailed)
                ? $this->formatErrorMessage($allFailed)
                : null,
        ]);
    }

    private function formatSuccessMessage(array $items): string
    {
        $count = count($items);

        $message = "Count of Successful Uploads: {$count}\n";
        $message .= "The following applicants have been successfully uploaded:\n";

        foreach ($items as $index => $item) {
            // Extract just the name (before the first parenthesis)
            $name = trim(explode('(', $item)[0]);

            $message .= ($index + 1).". {$name}\n";
        }

        return $message;
    }

    private function formatErrorMessage(array $items): string
    {
        $messageList = [];

        foreach ($items as $item) {
            $label = $this->extractFailedLabel($item);

            if ($label !== null) {
                $messageList[] = $label;
            }
        }

        $messageList = array_values(array_unique($messageList));
        $count = count($messageList);

        $message = "Count of Failed Uploads: {$count}\n";
        $message .= "The following applicants failed to upload:\n";

        foreach ($messageList as $index => $entry) {
            $message .= ($index + 1).". {$entry} - Excel row contains invalid data.\n";
        }

        return $message;
    }

    private function extractFailedLabel(string $item): ?string
    {
        // 1. Try extract name inside parentheses
        if (preg_match('/\((.*?)\)/', $item, $nameMatch)) {
            $fullName = trim($nameMatch[1]);

            return $this->formatName($fullName);
        }

        // 2. Try extract row number
        if (preg_match('/Row\s+(\d+)/i', $item, $rowMatch)) {
            return "Row {$rowMatch[1]}";
        }

        // 3. Ignore garbage entries like "FAILED"
        return null;
    }

    private function formatName(string $fullName): string
    {
        $nameParts = preg_split('/\s+/', trim($fullName));

        if (count($nameParts) < 2) {
            return $fullName;
        }

        $firstName = $nameParts[0];
        $lastName = $nameParts[count($nameParts) - 1];

        return "{$lastName}, {$firstName}";
    }
}
