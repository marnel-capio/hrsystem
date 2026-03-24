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

    // Fetch the batch and its target location
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
    $totalRows = count($rows);
    $now = now()->format('Y-m-d H:i:s');

    foreach ($rows as $rowIndex => $row) {
        // Skip completely empty rows
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

            // Required fields
            $hasGender = !empty(trim($row['Gender'] ?? ''));
            $hasSource = !empty(trim($row['From what recruitment channel have you applied for this job?'] ?? ''));
            $hasResume = !empty(trim($row['Upload your updated resume'] ?? ''));

            if (!$hasGender) { $skippedApplicants[] = "{$name} - Missing gender"; DB::rollBack(); continue; }
            if (!$hasSource) { $skippedApplicants[] = "{$name} - Missing source"; DB::rollBack(); continue; }
            if (!$hasResume) { $skippedApplicants[] = "{$name} - Missing resume"; DB::rollBack(); continue; }

            // Parse name
            $nameParts = explode(',', $name);
            $last = trim($nameParts[0] ?? '');
            $first = isset($nameParts[1]) ? trim(explode(' ', trim($nameParts[1]))[0]) : '';
            $middle = isset($nameParts[1]) ? trim(explode(' ', trim($nameParts[1]))[1] ?? '') : '';

            if (empty($last) || empty($first)) { $skippedApplicants[] = "{$name} - Invalid name format"; DB::rollBack(); continue; }

            // Gender mapping
            $rawGender = strtolower(preg_replace('/\s+/', '', $row['Gender']));
            $gender = $genderMap[$rawGender] ?? null;
            if (is_null($gender)) { $skippedApplicants[] = "{$name} - Invalid gender: {$row['Gender']}"; DB::rollBack(); continue; }

            // Source mapping
            $rawSource = trim($row['From what recruitment channel have you applied for this job?']);
            $source_type = $sourceTypeMap[$rawSource] ?? 3;
            $source = $sourceMap[$rawSource] ?? 1;
            $other_source = in_array($source_type, [1,2,4,5]) ? $rawSource : null;

            // Exam status
            $rawExamStatus = trim(strtolower($row['Results'] ?? ''));
            $exam_application_status = $examStatusMap[$rawExamStatus] ?? 1;

            // Exam plan date
            $rawDate = trim($row['Exam Schedule.'] ?? '');
            $exam_plan_date = null;
            if (!empty($rawDate)) {
                if (preg_match('/(\d+)\/(\d+)\s*(AM|PM)/i', $rawDate, $matches)) {
                    $month = $matches[1];
                    $day = $matches[2];
                    $ampm = strtoupper($matches[3]);
                    $year = date('Y');
                    $hour = ($ampm === 'AM') ? 9 : 15;
                    $minute = 0;
                    $exam_plan_date = sprintf('%04d-%02d-%02d %02d:%02d:00', $year, $month, $day, $hour, $minute);
                } else {
                    $ts = strtotime($rawDate);
                    if ($ts !== false) $exam_plan_date = date('Y-m-d H:i:s', $ts);
                }
            }

            // Insert applicant & application
            $applicant = ActionApplicant::updateOrCreateFromRow($row, $gender, $source_type, $source, $other_source, $now);
            ActionApplication::updateOrCreateFromRow($applicant->id, $request->batch_id, $row, $exam_application_status, $exam_plan_date, $now, $batchTargetLocation);

            DB::commit();
            $importedApplicants[] = "{$last}, {$first}";

        } catch (\Exception $e) {
            DB::rollBack();
            $failedApplicants[] = $name;
        }
    }

    // Prepare messages
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

    $errorMsg = '';

    // Merge skipped into failed
    $allFailed = array_merge($failedApplicants, $skippedApplicants);

    // Build failed message
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

    Log::createLog(
        'ACTION',
        $logMessage,
        $user->id
    );

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
