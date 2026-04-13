<?php

namespace App\Services;

use App\Models\IntermediateApplicant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class IntermediateApplicantImportService
{
    /**
     * Process entire file import (NO SKIP - only success/failed)
     */
    public function processFileImport($file): array
    {
        $rows = $this->parseFileIntermediate($file);

        $imported = [];
        $failed = [];

        foreach ($rows as $index => $row) {

            $result = $this->processApplicantRow($row, $index);

            // Skip empty rows
            if (isset($result['skip']) && $result['skip']) {
                continue;
            }

            // Success case
            if (isset($result['success']) && $result['success']) {
                $imported[] = $result['success'];
            }

            // Error case
            if (isset($result['error']) && $result['error']) {
                $failed[] = $result['error'];
            }
        }

        return [
            'imported' => $imported,
            'failed' => $failed,
        ];
    }

    /**
     * Process a single row (NO SKIP - process everything)
     */
    public function processApplicantRow(array $row, int &$rowIndex): array
    {
        $result = [
            'success' => null,
            'error' => null,
        ];

        $email = trim($row['Email Address'] ?? '');
        $fullName = trim($row['Name (Last Name, Given Name, Middle Name)'] ?? '');

        if (empty($email) && empty($fullName)) {
            return ['skip' => true];
        }

        if (empty($email) || empty($fullName)) {
            return [
                'error' => 'Row '.($rowIndex + 2).' - Missing email or name',
            ];
        }

        DB::beginTransaction();
        try {
            $result = $this->handleApplicantEligibility($row, $email, $fullName, $rowIndex);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $result = [
                'error' => 'Row '.($rowIndex + 2)." ({$fullName}) - ".$e->getMessage(),
            ];
        }

        return $result;
    }

    private function handleApplicantEligibility(array $row, string $email, string $fullName, int $rowIndex): array
    {
        $applicant = IntermediateApplicant::where('email_address', $email)->first();

        // FIRST PROCESS: NO APPLICANT → Create new
        if (! $applicant) {
            return $this->createNewApplicant($row, $fullName);
        }

        // SECOND PROCESS: Existing applicant
        return $this->handleExistingApplicant($applicant, $row, $fullName);
    }

    private function handleExistingApplicant(IntermediateApplicant $applicant, array $row, string $fullName): array
    {

        // 0. Get OLD registered_date FIRST (before any updates)
        $registeredDate = $applicant->registered_date
            ? Carbon::parse($applicant->registered_date)
            : null;

        // 1. Parse + Update profile FIRST (always)
        $parsedData = $this->parseApplicantData($row);
        $updateData = $parsedData['applicant'];

        // Get Excel timestamp
        $excelTimestamp = isset($row['Timestamp'])
            ? $this->parseExcelDate($row['Timestamp'])
            : null;

        // Update applicant profile
        unset($updateData['created_by'], $updateData['created_time'], $updateData['registered_by'], $updateData['source_type'], $updateData['source'], $updateData['other_source']);
        $applicant->update(array_merge($updateData, [
            'updated_by' => Auth::id(),
            'updated_time' => now(),
        ]));

        // 2. Compare dates: if difference > 6 months, CREATE NEW APPLICATION
        if ($excelTimestamp && $registeredDate && $this->isExpired($excelTimestamp, $registeredDate)) {
            return $this->createNewApplicationForExpiredApplicant($applicant, $row, $fullName);
        }

        // 3. NOT expired (≤ 6 months) → Update existing application
        return $this->updateExistingApplication($applicant, $row, $fullName);
    }

    private function isExpired(Carbon $excelTimestamp, Carbon $registeredDate): bool
    {
        return $registeredDate->diffInMonths($excelTimestamp) > 6;
    }

    private function createNewApplicationForExpiredApplicant(
        IntermediateApplicant $applicant,
        array $row,
        string $fullName
    ): array {
        $latestApp = $applicant->applications()->latest('created_time')->first();
        $examStatus = $latestApp?->exam_status ?? null;

        // Handle different exam statuses with appropriate stages
        $stage = match ($examStatus) {
            5 => 1,
            3, 4 => 2,
            default => 1
        };

        $reason = match ($examStatus) {
            5 => 'Most recent application exam was failed and has expired. Created new application for applicant.',
            3, 4 => 'Most recent application exam was passed but has expired. The applicant is now eligible for exam.',
            default => 'Most recent applicant application is expired. Created new application for applicant.'
        };

        return $this->createAppWithSync($applicant, $row, $fullName, $stage, $reason);
    }

    private function updateExistingApplication(IntermediateApplicant $applicant, array $row, string $fullName): array
    {
        $latestApp = $applicant->applications()->latest('created_time')->first();

        // No latest app → create new (safe fallback)
        if (! $latestApp) {
            return $this->createAppWithSync($applicant, $row, $fullName, 1, 'No existing application found. Created new application.');
        }

        $examStatus = $latestApp->exam_status;

        // exam_status = 5 → update latest app (stage 1) and reset exam_status
        if ($examStatus == 5) {
            $data = array_merge(
                $this->buildApplicationData($row, 1, true),
                ['exam_status' => null,
                    'remarks' => 'Most recent application exam was failed. Updated application for applicant.']
            );

            unset($data['created_by'], $data['created_time']);

            $latestApp->update($data);

            $this->syncWorkExperiences($applicant, $row);

            return ['success' => "{$fullName} (FULL APP SYNC + stage 1 reset exam_status)"];
        }

        // exam_status = 3 or 4 → update latest app (stage 3)
        if (in_array($examStatus, [3, 4])) {
            $data = $this->buildApplicationData($row, 3, true);

            $data['remarks'] = 'Most recent application exam was passed. Updated application for applicant.';

            unset($data['created_by'], $data['created_time']);

            $latestApp->update($data);

            return ['success' => "{$fullName} (FULL APP SYNC + stage 3)"];
        }

        // Default: update with current stage
        $data = $this->buildApplicationData($row, $latestApp->application_stage ?? 1, true);
        $data['remarks'] = 'Most recent applicant application is still valid. Updated application for applicant.';
        unset($data['created_by'], $data['created_time']);
        $latestApp->update($data);

        return $this->successWithSync("{$fullName} (UPDATED PROFILE + UPDATED LATEST APP - Default)", $applicant, $row);
    }

    private function createAppWithSync($applicant, $row, $fullName, $stage, $reason)
    {
        $this->createNewApplication($applicant, $row, $fullName, $stage, $reason);
        $this->syncWorkExperiences($applicant, $row);

        return ['success' => "{$fullName} (UPDATED PROFILE + NEW APP stage {$stage} - {$reason})"];
    }

    private function successWithSync($message, $applicant, $row)
    {
        $this->syncWorkExperiences($applicant, $row);

        return ['success' => $message];
    }

    /**
     * Create completely NEW application
     */
    private function createNewApplication($applicant, array $row, string $fullName, int $stage, string $remark): array
    {
        $this->createApplication($applicant, $row, $remark);

        $applicant->applications()->latest()->first()->update([
            'application_stage' => $stage,
        ]);

        $this->syncWorkExperiences($applicant, $row);

        return ['success' => "{$fullName} (NEW App Stage {$stage})"];
    }

    /**
     * Create new applicant with full data
     */
    private function createNewApplicant(array $row, string $fullName): array
    {
        $parsedData = $this->parseApplicantData($row);

        $applicant = IntermediateApplicant::create(array_merge($parsedData['applicant'], [
            'application_stage' => 1,
        ]));

        $this->createApplication($applicant, $row);
        $this->syncWorkExperiences($applicant, $row);

        return ['success' => "{$fullName} (NEW APPLICANT)"];
    }

    /**
     * Update existing eligible applicant
     */
    private function updateEligibleApplicant($applicant, array $row, string $fullName): array
    {
        $parsedData = $this->parseApplicantData($row);

        $updateData = $parsedData['applicant'];

        unset($updateData['created_by'], $updateData['created_time']);

        $applicant->update(array_merge($updateData, [
            'updated_by' => Auth::id(),
            'updated_time' => now(),
        ]));

        $this->syncWorkExperiences($applicant, $row);

        return ['success' => "{$fullName} (UPDATED - Within 6 months)"];
    }

    /**
     * Parse applicant data from row
     */
    private function parseApplicantData(array $row, bool $isUpdate = false): array
    {
        $nameParts = array_map(
            'trim',
            explode(',', trim($row['Name (Last Name, Given Name, Middle Name)'] ?? ''))
        );

        $source = strtolower(trim($row['How did you learn about this job posting?'] ?? ''));

        [$sourceType, $sourceValue, $otherSource] = $this->mapSource($row, $source);

        $applicantData = [
            'source' => $sourceValue,
            'source_type' => $sourceType,
            'other_source' => $otherSource,

            'email_address' => trim($row['Email Address'] ?? ''),
            'first_name' => $nameParts[1] ?? null,
            'middle_name' => $nameParts[2] ?? null,
            'last_name' => $nameParts[0] ?? null,

            'address' => $row['Address'] ?? null,
            'contact_no' => $row['Contact Number (Please follow 0916XXXXXXX format.)'] ?? null,
            'birthdate' => $this->parseBirthday($row['Birthday'] ?? null),
            'age' => $row['Age'] ?? null,
            'school_graduated_from' => $row['School Graduated from'] ?? null,
            'course' => $row['Course/Degree taken'] ?? null,
            'year_attended' => $row['Inclusive Year Attended'] ?? null,
            'others' => $row['Others'] ?? null,

            'spouse_details' => $row['SPOUSE'] ?? null,
            'children' => is_numeric($row['CHILDREN']) ? (int) $row['CHILDREN'] : 0,
            'father_details' => $row['FATHER'] ?? null,
            'mother_details' => $row['MOTHER'] ?? null,
            'sibling_details' => $row['SIBLING/S'] ?? null,

            'emergency_contact_name' => $row['Person to notify in case of emergency:'] ?? null,
            'emergency_contact_number' => $row['Contact Details'] ?? null,
            'emergency_contact_address' => $row['Contact Address'] ?? null,

            'registered_date' => $this->parseExcelDate($row['Timestamp'] ?? null),

            'registered_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'updated_time' => now(),
        ];

        // ✅ ONLY set created fields on insert
        if (! $isUpdate) {
            $applicantData['created_by'] = Auth::id();
            $applicantData['created_time'] = now();
        }

        return [
            'applicant' => $applicantData,
        ];
    }

    private function mapSource(array $row, string $sourceLower): array
    {
        $referralHeader = 'If “Referral” is selected above, please provide the name or description of the referring party. If not applicable, kindly indicate “N/A.”';

        $sourceType = null;
        $sourceValue = null;
        $otherSource = null;

        if (str_contains($sourceLower, 'referral')) {
            $sourceType = 2;
            $otherSource = trim($row[$referralHeader] ?? '');
        } elseif (str_contains($sourceLower, 'foundit')) {
            $sourceType = 1;
            $sourceValue = 1;
        } elseif (str_contains($sourceLower, 'linkedin')) {
            $sourceType = 1;
            $sourceValue = 2;
        } elseif (str_contains($sourceLower, 'facebook')) {
            $sourceType = 1;
            $sourceValue = 3;
        } elseif (str_contains($sourceLower, 'mynimo')) {
            $sourceType = 1;
            $sourceValue = 4;
        } elseif (str_contains($sourceLower, 'kalibrr')) {
            $sourceType = 1;
            $sourceValue = 5;
        } elseif (str_contains($sourceLower, 'aaisi')) {
            $sourceType = 3;
            $sourceValue = 1;
        } elseif (str_contains($sourceLower, 'primover')) {
            $sourceType = 3;
            $sourceValue = 2;
        } elseif (str_contains($sourceLower, 'tech tierra')) {
            $sourceType = 3;
            $sourceValue = 3;
        } elseif (str_contains($sourceLower, 'spring valley')) {
            $sourceType = 3;
            $sourceValue = 4;
        } elseif (str_contains($sourceLower, 'yens')) {
            $sourceType = 3;
            $sourceValue = 5;
        } elseif (str_contains($sourceLower, 'job fairs')) {
            $sourceType = 4;
        } elseif (str_contains($sourceLower, 'website')) {
            $sourceType = 5;
        } elseif (str_contains($sourceLower, 'rehire')) {
            $sourceType = 6;
        }

        return [$sourceType, $sourceValue, $otherSource];
    }

    /**
     * Create application record
     */
    private function createApplication($applicant, array $row, ?string $remark = null)
    {
        $data = $this->buildApplicationData($row, 1);

        // Only add remarks if provided
        if (! is_null($remark)) {
            $data['remarks'] = $remark;
        }

        $applicant->applications()->create($data);
    }

    private function buildApplicationData(array $row, $stage = 1, $isUpdate = false): array
    {

        $registeredDate = $this->parseExcelDate(
            $row['Timestamp'] ?? null
        );

        $fyWeek = $this->getFyWeekFromRegisteredDate($registeredDate);

        $data = [
            'position' => $row['Position you are applying for'] ?? null,
            'application_stage' => $stage,
            'fy_week' => $fyWeek,
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

            'updated_by' => Auth::id(),
            'updated_time' => now(),
        ];

        // ONLY set these on CREATE
        if (! $isUpdate) {
            $data['created_by'] = Auth::id();
            $data['created_time'] = now();
        }

        return $data;
    }

    private function getFyWeekFromRegisteredDate(Carbon|string|null $registeredDate): ?int
    {
        if (! $registeredDate) {
            return null;
        }

        $date = $registeredDate instanceof Carbon
            ? $registeredDate->copy()
            : Carbon::parse($registeredDate);

        // FY year starts April
        $fyYear = $date->month >= 4 ? $date->year : $date->year - 1;

        $aprilStart = Carbon::create($fyYear, 4, 1)->startOfDay();
        $aprilEnd = Carbon::create($fyYear, 4, 1)->endOfYear(); // FY end boundary base

        // Find first MONDAY in April
        $weekStart = $aprilStart->copy()->startOfWeek(Carbon::MONDAY);

        // Ensure it's not before April 1
        if ($weekStart->lt($aprilStart)) {
            $weekStart->addWeek();
        }

        // Ensure full week is valid (Mon–Sun fully inside FY window logic)
        if ($weekStart->copy()->addDays(6)->month < 4) {
            $weekStart->addWeek();
        }

        // If date is before FY week 1 → go to previous FY last week
        if ($date->lt($weekStart)) {
            return $this->getLastFyWeekOfYear($fyYear - 1);
        }

        // Compute weeks from aligned start
        return intdiv($weekStart->diffInDays($date), 7) + 1;
    }

    /**
     * Sync work experiences (delete old, create new)
     */
    private function syncWorkExperiences($applicant, array $row)
    {
        // Mark existing as deleted
        $applicant->workExperiences()->update(['is_deleted' => 1]);

        $workBlocks = [
            ['1. Employer (Company Name)', '1. Company Address', '1. Job Title', '1. Dates Employed', '1. Work Description/ Responsibilities', '1. Salary', '1. Reason for Leaving', '1. Name of Supervisor/Team Lead and Contact Number'],
            ['2. Employer (Company Name)', '2. Company Address', '2. Job Title', '2. Dates Employed', '2. Work Description/ Responsibilities', '2. Salary', '2. Reason for Leaving', '2. Name of Supervisor/Team Lead and Contact Number'],
            ['3. Employer (Company Name)', '3. Company Address', '3. Job Title', '3. Dates Employed', '3. Work Description/ Responsibilities', '3. Salary', '3. Reason for Leaving', '3. Name of Supervisor/Team Lead and Contact Number'],
        ];

        foreach ($workBlocks as $block) {
            if (! empty($row[$block[0]])) {
                $applicant->workExperiences()->create([
                    'employer' => $row[$block[0]] ?? null,
                    'company_address' => $row[$block[1]] ?? null,
                    'job_title' => $row[$block[2]] ?? null,
                    'date_employed' => $row[$block[3]] ?? null,
                    'work_description' => $row[$block[4]] ?? null,
                    'salary' => $row[$block[5]] ?? null,
                    'reason_for_leaving' => $row[$block[6]] ?? null,
                    'name_supervisor' => $row[$block[7]] ?? null,
                    'is_deleted' => 0,
                    'created_by' => Auth::id() ?? 1,
                    'created_time' => now(),
                    'updated_by' => Auth::id() ?? 1,
                    'updated_time' => now(),
                ]);
            }
        }
    }

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

    protected function parseBirthday($rawDate)
    {
        if (! $rawDate) {
            return null;
        }

        // Remove the first 6 characters
        $cleaned = substr($rawDate, 6);

        // Replace 年 and 月 with '-', remove 日
        $cleaned = str_replace(['年', '月'], '-', $cleaned);
        $cleaned = str_replace('日', '', $cleaned);

        // Optionally, ensure proper zero padding for month/day
        $parts = explode('-', $cleaned);
        if (count($parts) === 3) {
            $year = $parts[0];
            $month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
            $day = str_pad($parts[2], 2, '0', STR_PAD_LEFT);

            return "$year-$month-$day";
        }

        return $cleaned;
    }

    protected function parseContactNumber($number)
    {
        if (empty($number)) {
            return null;
        }

        // Remove spaces and non-numeric characters except '+'
        $number = preg_replace('/[^\d\+]/', '', $number);

        // If it starts with '0', replace it with '+63'
        if (preg_match('/^0(\d{9})$/', $number, $matches)) {
            $number = '+63'.$matches[1];
        }
        // If it starts with '9' and is 10 digits, assume missing leading 0
        elseif (preg_match('/^9(\d{9})$/', $number)) {
            $number = '+63'.$number;
        }
        // If it already starts with +63, leave as is
        elseif (! preg_match('/^\+63\d{9}$/', $number)) {
            // If not matching any valid format, return null
            return null;
        }

        return $number;
    }
}
