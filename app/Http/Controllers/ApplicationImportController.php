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
        $genderMap = $config['gender'];
        $examStatusMap = $config['exam_status'];

        $importedApplicants = [];
        $skippedApplicants = [];
        $failedApplicants = [];

        foreach ($rows as $rowIndex => $row) {
            if (empty(array_filter($row))) {
                $skippedApplicants[] = "Row " . ($rowIndex + 2) . " - Empty row";
                continue;
            }

            $name = trim($row['Full Name (Last Name, First Name, Middle Initial)'] ?? '');
            if (empty($name)) {
                $skippedApplicants[] = "Row " . ($rowIndex + 2) . " - No name found";
                continue;
            }

            try {
                DB::beginTransaction();

                // -------------------------
                // PARSE EXCEL TIMESTAMP
                // -------------------------
                $rawTimestamp = trim($row['Timestamp'] ?? '');
                $createdTime = now()->format('Y-m-d H:i:s');

                if (!empty($rawTimestamp)) {
                    $ts = \DateTime::createFromFormat('d/m/Y H:i:s', $rawTimestamp);

                    if ($ts === false) {
                        $parsed = strtotime($rawTimestamp);
                        if ($parsed !== false) {
                            $ts = new \DateTime();
                            $ts->setTimestamp($parsed);
                        }
                    }

                    if ($ts !== false) {
                        $createdTime = $ts->format('Y-m-d H:i:s');
                    }
                }

                \Log::debug('Parsed timestamp', [
                    'row' => $rowIndex + 2,
                    'createdTime' => $createdTime,
                ]);

                // -------------------------
                // REQUIRED FIELDS CHECK
                // -------------------------
                $hasGender = !empty(trim($row['Gender'] ?? ''));
                $hasSource = !empty(trim($row['From what recruitment channel have you applied for this job?'] ?? ''));
                $hasResume = !empty(trim($row['Upload your updated resume'] ?? ''));

                if (!$hasGender) {
                    $skippedApplicants[] = "{$name} - Missing gender";
                    DB::rollBack();
                    continue;
                }

                if (!$hasSource) {
                    $skippedApplicants[] = "{$name} - Missing source";
                    DB::rollBack();
                    continue;
                }

                if (!$hasResume) {
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

                $recruitmentPortalKeywords = $config['recruitment_portal_keywords'];
                $sourceMap = $config['source_map'];

                $rawSource = trim($row['From what recruitment channel have you applied for this job?']);
                \Log::info('Source value:', ['rawSource' => $rawSource]);

                if (in_array($rawSource, $recruitmentPortalKeywords)) {
                    $source_type = 3;
                    $source = $sourceMap[$rawSource] ?? null;
                    $other_source = null;
                } elseif ($rawSource === 'Referral (Employee Referral or Applicant Referral)') {
                    $source_type = 4;
                    $source = null;
                    $other_source = $rawSource;
                } elseif ($rawSource === 'Campus Recruitment Activity') {
                    $source_type = 1;
                    $source = null;
                    $other_source = $rawSource;
                } else {
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
                $exam_application_status = $examStatusMap[$rawExamStatus] ?? 1;

                $rawDate = trim($row['Exam Schedule.'] ?? '');
                $exam_plan_date = null;

                if (!empty($rawDate)) {
                    $ts = strtotime($rawDate);
                    if ($ts !== false) {
                        $exam_plan_date = date('Y-m-d H:i:s', $ts);
                    }
                }

            // -------------------------
            // CHECK EXISTING APPLICANT / LAST APPLICATION
            // -------------------------
            $email = trim($row['Email Address'] ?? '');
            $existingApplicant = ActionApplicant::where('email_address', $email)->first();

            $lastApplication = $existingApplicant
                ? ActionApplication::where('action_applicant_id', $existingApplicant->id)
                    ->orderBy('source_date', 'desc')
                    ->first()
                : null;

            $failedExamStatuses = [6, 7];
            $failedInitialStatuses = [5];
            $failedFinalStatuses = [5];
            $failedJobOfferStatuses = [4, 5, 6];

            $currentRowTime = Carbon::parse($createdTime);
            $sixMonthsAgo = $currentRowTime->copy()->subMonths(6);

            // -------------------------
            // DECIDE IMPORT BRANCH
            // -------------------------
            $applicationBranch = 'new';
            $applicationOverrides = [
                'remarks' => 'New',
                'exam_application_status' => null,
                'initial_interview_application_status' => null,
                'final_interview_application_status' => null,
                'job_offer_status' => null,
                'exam_plan_date' => null,
            ];

            if ($existingApplicant && $lastApplication) {
                $lastAppTime = Carbon::parse($lastApplication->source_date);

                $isFailed = in_array($lastApplication->exam_application_status, $failedExamStatuses) ||
                            in_array($lastApplication->initial_interview_application_status, $failedInitialStatuses) ||
                            in_array($lastApplication->final_interview_application_status, $failedFinalStatuses) ||
                            in_array($lastApplication->job_offer_status, $failedJobOfferStatuses);

                $withinSixMonths = $lastAppTime > $sixMonthsAgo;

                if ($isFailed && $withinSixMonths) {
                    $skippedApplicants[] = "{$name} - Failed within last 6 months, cannot reapply yet.";
                    DB::rollBack();
                    continue;
                }

                if ($isFailed && !$withinSixMonths) {
                    // duplicate + failed before + old enough => NEW
                    $applicationBranch = 'new';
                    $applicationOverrides = [
                        'remarks' => 'New',
                        'exam_application_status' => null,
                        'initial_interview_application_status' => null,
                        'final_interview_application_status' => null,
                        'job_offer_status' => null,
                        'exam_plan_date' => null,
                    ];
                } elseif (!$isFailed && $withinSixMonths) {
                    // duplicate + not failed + recent => FOR INITIAL INTERVIEW
                    $applicationBranch = 'for_initial_interview';
                    $applicationOverrides = [
                        'remarks' => 'For Initial Interview',
                        'exam_application_status' => $lastApplication->exam_application_status ?: 5,
                        'exam_plan_date' => $lastApplication->exam_plan_date,
                        'initial_interview_application_status' => 1,
                        'final_interview_application_status' => null,
                        'job_offer_status' => null,
                    ];
                } else {
                    // duplicate + not failed + older than 6 months => FOR EXAM
                    $applicationBranch = 'for_exam';
                    $applicationOverrides = [
                        'remarks' => 'Re-applied from previous ACTION batch.',
                        'exam_application_status' => null,
                        'exam_plan_date' => null,
                        'initial_interview_application_status' => null,
                        'final_interview_application_status' => null,
                        'job_offer_status' => null,
                    ];
                }
            }

            // -------------------------
            // ALWAYS UPSERT APPLICANT DATA
            // -------------------------
            $applicant = ActionApplicant::updateOrCreateFromRow(
                $row,
                $gender,
                $source_type,
                $source,
                $other_source,
                $existingApplicant?->created_time ?? $createdTime,
                now()->format('Y-m-d H:i:s')
            );

            ActionApplicant::syncProgrammingLanguagesFromRow($applicant->id, $row);

            // -------------------------
            // CREATE / UPDATE APPLICATION
            // -------------------------
            ActionApplication::updateOrCreateFromRow(
                $applicant->id,
                $request->batch_id,
                $row,
                $exam_application_status,
                $exam_plan_date,
                now()->format('Y-m-d H:i:s'),
                $batchTargetLocation,
                $createdTime,
                $applicationOverrides
            );

            DB::commit();
            $importedApplicants[] = mb_convert_encoding($name, 'UTF-8', 'UTF-8');

            \Log::info('Saving application', [
                'name' => $name,
                'branch' => $applicationBranch,
                'exam_application_status' => $applicationOverrides['exam_application_status'] ?? $exam_application_status,
                'initial_interview_application_status' => $applicationOverrides['initial_interview_application_status'] ?? null,
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

                $failedApplicants[] = "{$name} - " . $e->getMessage();
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

            $successMsg = "Count of Successful Uploads: " . count($importedApplicants) . "\n\n" .
                config('errors.successful_action_application_import.errorMessage') . "\n" .
                implode("\n", $successList) . "\n\n";
        }

        $allFailed = array_merge($failedApplicants, $skippedApplicants);
        $errorMsg = '';
        if (! empty($allFailed)) {
            $errorList = [];
            foreach ($allFailed as $index => $name) {
                $errorList[] = ($index + 1).'. '.$name;
            }

            $errorMsg = "Count of Failed Uploads: " . count($allFailed) . "\n\n" .
                config('errors.failed_action_application_import.errorMessage') . "\n" .
                implode("\n", $errorList) . "\n\n";
        }

        $user = Auth::user();
        $logMessage = "Imported ACTION applications and applicants. Total rows: {$totalRows}, Success: " . count($importedApplicants) .
            ", Failed: " . count($allFailed);

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
