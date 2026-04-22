<?php

namespace App\Services;

use App\Models\ActionApplicant;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\IntermediateApplicant;

class LogService
{
    /**
     * Create a detailed log for updated user fields
     */
    public function createUserUpdateLog(array $oldData, array $newData, ?int $userId = null): void
    {
        $userId = $userId ?? Auth::id();
        $ipAddress = request()->ip();
        $activityLines = [];
        $activityLines[] = "Updated user details for {$oldData['first_name']} {$oldData['last_name']}.";
        $activityLines[] = 'Details:';

        $fields = [
            'first_name', 'last_name', 'middle_name', 'address',
            'contact_no', 'email_address', 'password',
            'position', 'permissions', 'active_status',
        ];

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ($field === 'password') {
                if (! empty($newValue)) {
                    $activityLines[] = 'password: [HIDDEN] -> [UPDATED]';
                }

                continue;
            }

            $oldValueStr = is_bool($oldValue) ? (int) $oldValue : (string) $oldValue;
            $newValueStr = is_bool($newValue) ? (int) $newValue : (string) $newValue;

            if ($oldValueStr !== $newValueStr) {
                $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
            }
        }

        $activity = implode("\n", $activityLines);

        Log::create([
            'module' => 'Users',
            'activity' => $activity,
            'ip_address' => $ipAddress,
            'created_by' => $userId,
            'updated_by' => $userId,
            'create_time' => now(),
            'update_time' => now(),
        ]);
    }

    public function createProgrammingLanguageUpdateLog(array $oldData, array $newData, int $applicantId): void
    {
        $applicant = ActionApplicant::find($applicantId);
        $ipAddress = request()->ip();
        $activityLines = [];

        $activityLines[] = "Updated programming language for {$applicant->email_address}.";
        $activityLines[] = 'Details:';

        // List of fields to track
        $fields = ['program_language', 'remarks']; // add other relevant fields

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ((string) $oldValue !== (string) $newValue) {
                $activityLines[] = "{$field}: {$oldValue} -> {$newValue}";
            }
        }

        $activity = implode("\n", $activityLines);

        Log::createLog('ACTION', $activity, $applicantId, $ipAddress);
    }

    public function createApplicantUpdateLog(array $oldData, array $newData, int $applicantId): void
    {
        $applicant = ActionApplicant::find($applicantId);

        if (! $applicant) {
            return;
        }

        $ipAddress = request()->ip();

        $activityLines = [];

        $activityLines[] = "Updated ACTION Applicant: {$applicant->first_name} {$applicant->last_name}.";
        $activityLines[] = 'Details:';

        // ✅ Use config consistently
        $sourceTypes = config('constants.sourceTypes', []);
        $sources = config('constants.sources', []);
        $genders = config('constants.genders', []);

        // ✅ Japanese mappings (you already defined them — now centralized)
        $japaneseBackground = [
            1 => 'None',
            2 => 'Self Study / University Level',
            3 => 'JLPT Certification',
        ];

        $japaneseLevel = [
            5 => 'N5 (lowest)',
            4 => 'N4',
            3 => 'N3',
            2 => 'N2',
            1 => 'N1',
        ];

        $fields = [
            'source_type',
            'source',
            'other_source',
            'last_name',
            'first_name',
            'middle_name',
            'email_address',
            'gender',
            'age',
            'school',
            'degree',
            'others_degree',
            'expected_graduation',
            'awards_recognition',
            'other_examination_certificate',
            'thesis_project',
            'extra_curricular',
            'japanese_background',
            'japanese_level',
            'background_remarks',
        ];

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            // default fallback (safe)
            $oldValueStr = $oldValue;
            $newValueStr = $newValue;

            // ✅ Mapping layer
            switch ($field) {
                case 'source_type':
                    $oldValueStr = $sourceTypes[$oldValue] ?? $oldValue;
                    $newValueStr = $sourceTypes[$newValue] ?? $newValue;
                    break;

                case 'source':
                    $oldValueStr = $sources[$oldValue] ?? $oldValue;
                    $newValueStr = $sources[$newValue] ?? $newValue;
                    break;

                case 'gender':
                    $oldValueStr = $genders[$oldValue] ?? $oldValue;
                    $newValueStr = $genders[$newValue] ?? $newValue;
                    break;

                    // ✅ NEW: Japanese background
                case 'japanese_background':
                    $oldValueStr = $japaneseBackground[$oldValue] ?? $oldValue;
                    $newValueStr = $japaneseBackground[$newValue] ?? $newValue;
                    break;

                    // ✅ NEW: Japanese level
                case 'japanese_level':
                    $oldValueStr = $japaneseLevel[$oldValue] ?? $oldValue;
                    $newValueStr = $japaneseLevel[$newValue] ?? $newValue;
                    break;

                default:
                    $oldValueStr = (string) $oldValue;
                    $newValueStr = (string) $newValue;
                    break;
            }

            if ($oldValueStr !== $newValueStr) {
                $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
            }
        }

        $activity = implode("\n", $activityLines);

        Log::create([
            'module' => 'ACTION',
            'activity' => $activity,
            'ip_address' => $ipAddress,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'create_time' => now(),
            'update_time' => now(),
        ]);
    }

    public function createIntermediateApplicantUpdateLog(array $oldData, array $newData, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);

    if (! $applicant) {
        return;
    }

    $ipAddress = request()->ip();

    $activityLines = [];

    $activityLines[] = "Updated INTERMEDIATE Applicant: {$applicant->first_name} {$applicant->last_name}.";
    $activityLines[] = 'Details:';

    $sourceTypes = config('constants.intermediateSourceTypes', []);
    $sources = config('constants.intermediateSources', []);
    $genders = config('constants.genders', []);
    $japaneseBackgrounds = config('constants.japanese_backgrounds', []);
    $japaneseLevels = config('constants.japanese_levels', []);

    $fields = [
        'source_type',
        'source',
        'other_source',
        'last_name',
        'first_name',
        'middle_name',
        'email_address',
        'gender',
        'birthdate',
        'age',
        'address',
        'contact_no',
        'school_graduated_from',
        'course',
        'year_attended',
        'others',
        'japanese_background',
        'japanese_level',
        'background_remarks',
        'spouse_details',
        'children',
        'father_details',
        'mother_details',
        'sibling_details',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_address',
        'remarks',
    ];

    foreach ($fields as $field) {
        $oldValue = $oldData[$field] ?? null;
        $newValue = $newData[$field] ?? null;

        $oldValueStr = $oldValue;
        $newValueStr = $newValue;

        switch ($field) {
            case 'source_type':
                $oldValueStr = $sourceTypes[$oldValue] ?? $oldValue;
                $newValueStr = $sourceTypes[$newValue] ?? $newValue;
                break;

            case 'source':
                $oldValueStr = $sources[$oldValue] ?? $oldValue;
                $newValueStr = $sources[$newValue] ?? $newValue;
                break;

            case 'gender':
                $oldValueStr = $genders[$oldValue] ?? $oldValue;
                $newValueStr = $genders[$newValue] ?? $newValue;
                break;

            case 'japanese_background':
                $oldValueStr = $japaneseBackgrounds[$oldValue] ?? $oldValue;
                $newValueStr = $japaneseBackgrounds[$newValue] ?? $newValue;
                break;

            case 'japanese_level':
                $oldValueStr = $japaneseLevels[$oldValue] ?? $oldValue;
                $newValueStr = $japaneseLevels[$newValue] ?? $newValue;
                break;

            default:
                $oldValueStr = (string) $oldValue;
                $newValueStr = (string) $newValue;
                break;
        }

        if ($oldValueStr !== $newValueStr) {
            $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
        }
    }

    if (count($activityLines) <= 2) {
        return;
    }

    $activity = implode("\n", $activityLines);

    Log::create([
        'module' => 'INTERMEDIATE',
        'activity' => $activity,
        'ip_address' => $ipAddress,
        'created_by' => auth()->id(),
        'updated_by' => auth()->id(),
        'create_time' => now(),
        'update_time' => now(),
    ]);
}

    public function createSkillUpdateLog(array $oldData, array $newData, int $applicantId): void
    {
        $applicant = ActionApplicant::find($applicantId);
        $ipAddress = request()->ip();
        $activityLines = [];

        $activityLines[] = "Updated skill for {$applicant->email_address}.";
        $activityLines[] = 'Details:';

        // Fields to track
        $fields = ['skill', 'remarks'];

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ((string) $oldValue !== (string) $newValue) {
                $activityLines[] = "{$field}: {$oldValue} -> {$newValue}";
            }
        }

        $activity = implode("\n", $activityLines);

        Log::createLog('ACTION', $activity, $applicantId, $ipAddress);
    }

public function createIntermediateSkillCreateLog(array $data, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);

    if (! $applicant) return;

    $ipAddress = request()->ip();

    $skill = $data['skill'] ?? '';

    $activity = "Added {$skill} skill to {$applicant->email_address}.";

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateSkillUpdateLog(array $oldData, array $newData, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);

    if (! $applicant) return;

    $ipAddress = request()->ip();

    $activityLines = [];
    $activityLines[] = "Updated skill for {$applicant->email_address}.";
    $activityLines[] = "Details:";

    $hasChanges = false;

    if (($oldData['skill'] ?? '') !== ($newData['skill'] ?? '')) {
        $activityLines[] = "skill: {$oldData['skill']} -> {$newData['skill']}";
        $hasChanges = true;
    }

    if (($oldData['remarks'] ?? '') !== ($newData['remarks'] ?? '')) {
        $activityLines[] = "remarks: {$oldData['remarks']} -> {$newData['remarks']}";
        $hasChanges = true;
    }

    if (! $hasChanges) return;

    $activity = implode("\n", $activityLines);

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateSkillDeleteLog(array $oldData, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);

    if (! $applicant) return;

    $ipAddress = request()->ip();

    $skill = $oldData['skill'] ?? '';

    $activity = "Deleted {$skill} skill of {$applicant->email_address}.";

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateSkillBulkDeleteLog(array $skills, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);

    if (! $applicant) return;

    $ipAddress = request()->ip();

    $skillNames = collect($skills)
        ->pluck('skill')
        ->filter()
        ->implode(', ');

    $activity = "Deleted {$skillNames} skill(s) of {$applicant->email_address}.";

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateWorkExperienceCreateLog(array $data, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);
    if (! $applicant) return;

    $ipAddress = request()->ip();
    $label = $data['employer'] ?? $data['job_title'] ?? 'work experience';

    $activity = "Added {$label} work experience to {$applicant->email_address}.";

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateWorkExperienceUpdateLog(array $oldData, array $newData, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);
    if (! $applicant) return;

    $ipAddress = request()->ip();

    $activityLines = [];
    $activityLines[] = "Updated work experience for {$applicant->email_address}.";
    $activityLines[] = "Details:";
    $activityLines[] = "※Only updated fields will reflect changes in DB";

    $fields = [
        'employer',
        'company_address',
        'job_title',
        'date_employed',
        'work_description',
        'salary',
        'reason_for_leaving',
        'name_supervisor',
        'remarks',
    ];

    $hasChanges = false;

    foreach ($fields as $field) {
        $old = $oldData[$field] ?? '';
        $new = $newData[$field] ?? '';

        if ((string) $old !== (string) $new) {
            $activityLines[] = "{$field}: {$old} -> {$new}";
            $hasChanges = true;
        }
    }

    if (! $hasChanges) return;

    $activity = implode("\n", $activityLines);

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateWorkExperienceDeleteLog(array $oldData, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);
    if (! $applicant) return;

    $ipAddress = request()->ip();
    $label = $oldData['employer'] ?? $oldData['job_title'] ?? 'work experience';

    $activity = "Deleted {$label} work experience of {$applicant->email_address}.";

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}

public function createIntermediateWorkExperienceBulkDeleteLog(array $works, int $applicantId): void
{
    $applicant = IntermediateApplicant::find($applicantId);
    if (! $applicant) return;

    $ipAddress = request()->ip();

    $labels = collect($works)
        ->map(fn ($w) => $w['employer'] ?? $w['job_title'] ?? 'work experience')
        ->implode(', ');

    $activity = "Deleted {$labels} work experience(s) of {$applicant->email_address}.";

    Log::createLog('INTERMEDIATE', $activity, $applicantId, $ipAddress);
}
}
