<?php

namespace App\Http\Controllers;

use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionBatchModel;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ApplicationImportController extends Controller
{
    public function create()
    {
        $actionBatches = ActionBatchModel::select('id', 'action_batch', 'target_trainees', 'target_date')
            ->orderBy('id', 'desc')
            ->get();

        $applications = DB::table('action_applicant_applications as app')
            ->join('action_applicants as applicant', 'app.action_applicant_id', '=', 'applicant.id')
            ->join('action_batches as batch', 'app.action_batch_id', '=', 'batch.id')
            ->leftJoin('resource_schedules as rs', 'batch.id', '=', 'rs.action_batch_id') // <- join resource_schedules
            ->select([
                'app.id',
                'app.action_applicant_id',
                'app.action_batch_id',
                'applicant.first_name',
                'applicant.last_name',
                'batch.action_batch',
                'rs.target_location',
            ])
            ->orderBy('app.id', 'desc')
            ->get();

        return inertia('action/applications/ActionApplicationList', [
            'applications' => $applications,
            'actionBatches' => $actionBatches,
            'filters' => ['search' => request('search', '')],
            'userPermissions' => auth()->user()->permissions ?? 0,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:10240',
            'batch_id' => 'required|integer|exists:action_batches,id',
        ]);

        // Fetch batch + target location
        $batch = DB::table('action_batches as b')
            ->leftJoin('resource_schedules as rs', 'b.id', '=', 'rs.action_batch_id')
            ->where('b.id', $request->batch_id)
            ->select('b.id', 'b.action_batch', 'rs.target_location')
            ->first();

        $batchTargetLocation = $batch->target_location ?? null;

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
        $sixMonthsAgo = now()->subMonths(6);

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
                $createdTime = $now; // fallback
                if (!empty($rawTimestamp)) {
                    $ts = \DateTime::createFromFormat('d/m/Y H:i:s', $rawTimestamp);
                    if ($ts !== false) {
                        $createdTime = $ts->format('Y-m-d H:i:s');
                    }
                }

                // Required fields
                $hasGender = !empty(trim($row['Gender'] ?? ''));
                $hasSource = !empty(trim($row['From what recruitment channel have you applied for this job?'] ?? ''));
                $hasResume = !empty(trim($row['Upload your updated resume'] ?? ''));

                if (!$hasGender) { $skippedApplicants[] = "{$name} - Missing gender"; DB::rollBack(); continue; }
                if (!$hasSource) { $skippedApplicants[] = "{$name} - Missing source"; DB::rollBack(); continue; }
                if (!$hasResume) { $skippedApplicants[] = "{$name} - Missing resume"; DB::rollBack(); continue; }

                // Gender & source mapping
                $rawGender = strtolower(preg_replace('/\s+/', '', $row['Gender']));
                $gender = $genderMap[$rawGender] ?? null;
                if (is_null($gender)) { $skippedApplicants[] = "{$name} - Invalid gender"; DB::rollBack(); continue; }

                $rawSource = trim($row['From what recruitment channel have you applied for this job?']);
                $source_type = $sourceTypeMap[$rawSource] ?? 3;
                $source = $sourceMap[$rawSource] ?? 1;
                $other_source = in_array($source_type, [1,2,4,5]) ? $rawSource : null;

                // Exam status
                $rawExamStatus = trim(strtolower($row['Results'] ?? ''));
                $examApplicationStatusFromExcel = $examStatusMap[$rawExamStatus] ?? 1;

                // Exam plan date
                $rawDate = trim($row['Exam Schedule.'] ?? '');
                $exam_plan_date = null;
                if (!empty($rawDate)) {
                    $ts = strtotime($rawDate);
                    if ($ts !== false) $exam_plan_date = date('Y-m-d H:i:s', $ts);
                }

                // -------------------------
                // Decision logic for application type
                // -------------------------
                $email = trim($row['Email Address'] ?? '');
                $existingApplicant = ActionApplicant::where('email_address', $email)->first();
                $lastApplication = $existingApplicant
                    ? ActionApplication::where('action_applicant_id', $existingApplicant->id)
                        ->orderBy('created_time', 'desc')
                        ->first()
                    : null;

                // Default statuses
                $exam_application_status = null;
                $initial_interview_application_status = null;

                $failedExamStatuses = [6,7]; // Failed, No Show
                $failedInitialStatuses = [5]; // Failed initial interview

                if (!$existingApplicant) {
                    // New applicant -> just create applicant, no statuses
                    $applicant = ActionApplicant::updateOrCreateFromRow($row, $gender, $source_type, $source, $other_source, $createdTime, $now
                    );
                } elseif ($lastApplication) {
                    $applicant = $existingApplicant;
                    $lastAppTime = $lastApplication->created_time;

                    $isFailed = in_array($lastApplication->exam_application_status, $failedExamStatuses) ||
                                in_array($lastApplication->initial_interview_application_status, $failedInitialStatuses);

                    if ($isFailed && $lastAppTime > $sixMonthsAgo) {
                        // Skip
                        $skippedApplicants[] = "{$name} - Last failed app <6mo, skipping";
                        DB::rollBack();
                        continue;
                    } elseif ($isFailed && $lastAppTime <= $sixMonthsAgo) {
                        // Failed >6mo → new application (no statuses)
                    } elseif (!$isFailed && $lastAppTime > $sixMonthsAgo) {
                        // No fail <6mo → Initial Interview app
                        $exam_application_status = 5; // Passed
                        $initial_interview_application_status = 1; // Pending
                    } else {
                        // No fail >6mo → Exam app
                        $exam_application_status = 1; // Pending
                    }
                }

                // -------------------------
                // Insert application via model
                // -------------------------
                ActionApplication::updateOrCreateFromRow($applicant->id, $request->batch_id, $row, $exam_application_status, $exam_plan_date, $now, $createdTime         // <-- timestamp from Excel
                );

                DB::commit();

                // Force UTF-8 for import messages
                $importedApplicants[] = mb_convert_encoding($name, 'UTF-8', 'UTF-8');

            } catch (\Exception $e) {
                DB::rollBack();
                $failedApplicants[] = mb_convert_encoding("{$name} - Excel row contains invalid data", 'UTF-8', 'UTF-8');
            }
        }

        // -------------------------
        // Prepare messages that show on screen & logging
        // -------------------------
        $totalRows = count($rows);

        $successMsg = '';
        if (!empty($importedApplicants)) {
            $successList = [];
            foreach ($importedApplicants as $index => $name) {
                $successList[] = ($index + 1) . '. ' . $name;
            }
            $successMsg = "Count of Successful Uploads: " . count($importedApplicants) . "\n\n" .
                        config('errors.successful_action_application_import.errorMessage') . "\n" .
                        implode("\n", $successList) . "\n\n";
        }

        $allFailed = array_merge($failedApplicants, $skippedApplicants);
        $errorMsg = '';
        if (!empty($allFailed)) {
            $errorList = [];
            foreach ($allFailed as $index => $name) {
                $errorList[] = ($index + 1) . '. ' . $name;
            }
            $errorMsg = "Count of Failed Uploads: " . count($allFailed) . "\n\n" .
                        config('errors.failed_action_application_import.errorMessage') . "\n" .
                        implode("\n", $errorList) . "\n\n";
        }

        // Log after all imports
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
            throw new \Exception('Parse error: ' . $e->getMessage());
        }
    }
}
