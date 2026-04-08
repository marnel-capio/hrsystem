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

            // Only success or failed
            if (isset($result['success']) && $result['success']) {
                $imported[] = $result['success'];
            }
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

        // Even empty rows get processed (will fail with error)
        if (empty($email) || empty($fullName)) {
            return [
                'error' => 'Row '.($rowIndex + 2).' - Missing email or name (FAILED)',
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

    /**
     * PERFECT Logic - 6 MONTHS FROM registered_date
     */
    private function handleApplicantEligibility(array $row, string $email, string $fullName, int $rowIndex): array
    {
        $applicant = IntermediateApplicant::where('email_address', $email)->first();

        if (! $applicant) {
            return $this->createNewApplicant($row, $fullName);
        }

        // **6-MONTH RULE: Use registered_date**
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $isRegistrationRecent = $applicant->registered_date >= $sixMonthsAgo;

        // **LATEST APP for status checks**
        $latestApp = $applicant->applications()
            ->latest('created_time')
            ->first(['id', 'exam_status', 'hr_interview_status', 'created_time']);

        \Log::info("Eligibility Debug [{$fullName}]:", [
            'registered_date' => $applicant->registered_date,
            'within_6mo_reg' => $isRegistrationRecent,
            'latest_app_exists' => $latestApp !== null,
            'latest_app_date' => $latestApp?->created_time,
        ]);

        // **CASE 0**: registered_date > 6 months → Update profile + NEW app
        if (! $isRegistrationRecent) {
            $parsedData = $this->parseApplicantData($row);

            $applicant->update(array_merge($parsedData['applicant'], [
                'remarks' => 'Profile updated - Registration expired (>6mo)',
                'updated_by' => Auth::id(),
                'updated_time' => now(),
            ]));

            $this->createNewApplication($applicant, $row, $fullName, 1, 'New app - Registration expired (>6mo)');
            $this->syncWorkExperiences($applicant, $row);

            return ['success' => "{$fullName} (UPDATED PROFILE + NEW APP - Reg >6mo)"];
        }

        // **REGISTRATION RECENT (≤6mo)** - Check latest app status
        if (! $latestApp) {
            // No apps = treat as fresh
            return $this->updateEligibleApplicant($applicant, $row, $fullName);
        }

        $examStatus = $latestApp->exam_status ?? 0;
        $hrStatus = $latestApp->hr_interview_status ?? 0;

        // CASE 1: exam_status=5 (recent registration)
        if ($examStatus == 5) {
            return $this->updateLatestAppStatus($applicant, $row, $fullName, 1, null, 'Exam passed - Recent reg');
        }

        // CASE 2: hr_status=3 or 4 (recent registration)
        elseif (in_array($hrStatus, [3, 4])) {
            return $this->updateLatestAppStatus($applicant, $row, $fullName, 3, 'HR Interview ready');
        }

        // DEFAULT: Update profile (recent reg, other status)
        return $this->updateEligibleApplicant($applicant, $row, $fullName);
    }

    /**
     * Update LATEST application status
     */
    private function updateLatestAppStatus($applicant, array $row, string $fullName, int $newStage, ?int $examStatus, string $remark): array
    {
        $latestApp = $applicant->applications()->latest('created_time')->first();

        $latestApp->update([
            'application_stage' => $newStage,
            'exam_status' => $examStatus,
            'remarks' => $remark,
            'updated_by' => Auth::id(),
            'updated_time' => now(),
        ]);

        $this->syncWorkExperiences($applicant, $row);

        return ['success' => "{$fullName} (UPDATED App Stage {$newStage})"];
    }

    /**
     * Create completely NEW application
     */
    private function createNewApplication($applicant, array $row, string $fullName, int $stage, string $remark): array
    {
        $this->createApplication($applicant, $row); // Creates with stage=1

        $applicant->applications()->latest()->first()->update([
            'application_stage' => $stage,
            'remarks' => $remark,
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

        $applicant->update(array_merge($parsedData['applicant'], [
            'application_stage' => 1,
            'remarks' => 'Still within 6 months eligibility',
        ]));

        $this->syncWorkExperiences($applicant, $row);

        return ['success' => "{$fullName} (UPDATED - Within 6 months)"];
    }

    /**
     * Parse applicant data from row
     */
    private function parseApplicantData(array $row): array
    {
        $nameParts = array_map('trim', explode(',', trim($row['Name (Last Name, Given Name, Middle Name)'] ?? '')));

        return [
            'applicant' => [
                'email_address' => trim($row['Email Address'] ?? ''),
                'first_name' => $nameParts[1] ?? null,
                'middle_name' => $nameParts[2] ?? null,
                'last_name' => $nameParts[0] ?? null,
                'address' => $row['Address'] ?? null,
                'contact_no' => $row['Contact Number'] ?? null,
                'birthdate' => $this->parseBirthday($row['Birthday'] ?? null),
                'age' => $row['Age'] ?? null,
                'school_graduated_from' => $row['School Graduated from'] ?? null,
                'course' => $row['Course/Degree taken'] ?? null,
                'year_attended' => $row['Inclusive Year Attended'] ?? null,
                'spouse_details' => $row['SPOUSE'] ?? null,
                'children' => is_numeric($row['CHILDREN']) ? (int) $row['CHILDREN'] : 0,
                'father_details' => $row['FATHER'] ?? null,
                'mother_details' => $row['MOTHER'] ?? null,
                'sibling_details' => $row['SIBLING/S'] ?? null,
                'emergency_contact_name' => $row['Person to notify in case of emergency:'] ?? null,
                'emergency_contact_number' => $row['Contact Details'] ?? null,
                'emergency_contact_address' => $row['Address'] ?? null,
                'registered_date' => $this->parseExcelDate($row['Timestamp'] ?? null),
                'registered_by' => Auth::id(),
            ],
        ];
    }

    /**
     * Create application record
     */
    private function createApplication($applicant, array $row)
    {
        $applicant->applications()->create([
            'position' => $row['Position you are applying for'] ?? null,
            'application_stage' => 1,
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
                    'date_employed' => $this->parseExcelDate($row[$block[3]] ?? null),
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
}
