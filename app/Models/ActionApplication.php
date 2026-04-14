<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionApplication extends Model
{
    use HasFactory;

    protected $table = 'action_applicant_applications';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'action_applicant_id',
        'action_batch_id',
        'upload_resume',
        'upload_tor',
        'upload_pic',
        'exam_plan_date',
        'exam_actual_date',
        'exam_venue',
        'exam_atpp_part1_correct',
        'exam_atpp_part1_wrong',
        'exam_atpp_part2_correct',
        'exam_atpp_part2_wrong',
        'exam_atpp_part3_correct',
        'exam_atpp_part3_wrong',
        'exam_atpp_result',
        'exam_git_result',
        'exam_prg_result',
        'exam_result',
        'exam_application_status',
        'exam_remarks',
        'initial_interview_plan_date',
        'initial_interview_actual_date',
        'initial_interview_venue',
        'initial_interview_final',
        'initial_interview_result',
        'initial_interview_application_status',
        'initial_interview_remarks',
        'final_interview_date',
        'final_interview_final',
        'final_interview_result',
        'final_interview_application_status',
        'final_interview_remarks',
        'job_offer_schedule',
        'job_offer_status',
        'job_offer_remarks',
        'trainees_from',
        'source_date',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public static function listPageData(?string $search = null)
    {
        return static::query()
            ->select(
                'action_applicant_applications.id',
                'resource_schedules.target_location',
                'action_applicant_applications.action_applicant_id',
                'action_applicant_applications.action_batch_id',
                'action_batches.action_batch',
                'action_applicants.first_name',
                'action_applicants.middle_name',
                'action_applicants.last_name'
            )
            ->join('action_batches', 'action_applicant_applications.action_batch_id', '=', 'action_batches.id')
            ->join('action_applicants', 'action_applicant_applications.action_applicant_id', '=', 'action_applicants.id')
            ->leftJoin('resource_schedules', 'action_batches.id', '=', 'resource_schedules.action_batch_id')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action_applicants.first_name', 'like', "%{$search}%")
                        ->orWhere('action_applicants.last_name', 'like', "%{$search}%")
                        ->orWhere('action_batches.action_batch', 'like', "%{$search}%")
                        ->orWhereRaw("COALESCE(resource_schedules.target_location, '') LIKE ?", ["%{$search}%"])
                        ->orWhere('action_applicant_applications.id', 'like', "%{$search}%");
                });
            })
            ->orderBy('action_applicant_applications.created_time', 'desc')
            ->get();
    }

    public static function createApplication(array $data)
    {
        $data['created_time'] = now();
        $data['updated_time'] = now();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return self::create($data);
    }

    public function updateApplication(array $data)
    {
        $data['updated_time'] = now();
        $data['updated_by'] = Auth::id();
        $this->update($data);

        return $this;
    }

    public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(ActionBatchModel::class, 'action_batch_id', 'id');
    }

    public function interviews()
    {
        return $this->hasMany(ActionApplicationInterview::class, 'action_application_id', 'id');
    }

    public static function updateOrCreateFromRow($applicantId, $batchId, array $row, $exam_application_status, $exam_plan_date, $updatedTime, $targetLocation = null, $createdTime = null)
    {
        $createdTime = $createdTime ?? $updatedTime;

        return self::updateOrCreate(
            [
                'action_applicant_id' => $applicantId,
                'action_batch_id' => $batchId,
            ],
            [
                'upload_resume' => trim($row['Upload your updated resume'] ?? ''),
                'exam_application_status' => $exam_application_status,
                'exam_plan_date' => $exam_plan_date,
                'created_by' => Auth::id(),
                'created_time' => now(),
                'source_date' => $createdTime,
                'updated_by' => Auth::id(),
                'updated_time' => now(),
                'trainees_from' => $targetLocation,
            ]
        );
    }

protected static function computeAtppResult(array $data): ?float
{
    $fields = [
        'exam_atpp_part1_correct',
        'exam_atpp_part1_wrong',
        'exam_atpp_part2_correct',
        'exam_atpp_part2_wrong',
        'exam_atpp_part3_correct',
        'exam_atpp_part3_wrong',
    ];

    $hasAny = collect($fields)->contains(function ($field) use ($data) {
        return array_key_exists($field, $data)
            && $data[$field] !== null
            && $data[$field] !== '';
    });

    if (!$hasAny) {
        return isset($data['exam_atpp_result']) && $data['exam_atpp_result'] !== ''
            ? (float) $data['exam_atpp_result']
            : null;
    }

    $p1c = (float) ($data['exam_atpp_part1_correct'] ?? 0);
    $p1w = (float) ($data['exam_atpp_part1_wrong'] ?? 0);
    $p2c = (float) ($data['exam_atpp_part2_correct'] ?? 0);
    $p2w = (float) ($data['exam_atpp_part2_wrong'] ?? 0);
    $p3c = (float) ($data['exam_atpp_part3_correct'] ?? 0);
    $p3w = (float) ($data['exam_atpp_part3_wrong'] ?? 0);

    $totalCorrect = $p1c + $p2c + $p3c;
    $totalWrong = ($p1w + $p2w + $p3w) / 4;

    return $totalCorrect - $totalWrong;
}

    public static function normalizeComputedFields(array $data): array
{
    $applicant = ActionApplicant::find($data['action_applicant_id'] ?? null);

    if (!$applicant) {
        return $data;
    }

    $data['exam_atpp_result'] = static::computeAtppResult($data);

    if (
        static::hasValue($data, 'exam_atpp_result') &&
        static::hasValue($data, 'exam_git_result') &&
        static::hasValue($data, 'exam_prg_result')
    ) {
$computedExamStatus = static::computeExamApplicationStatus(
    (float) $data['exam_atpp_result'],
    (float) $data['exam_git_result'],
    (float) $data['exam_prg_result'],
    $applicant
);


$data['exam_application_status'] = $computedExamStatus;
    } elseif (static::hasValue($data, 'exam_plan_date')) {
        $data['exam_application_status'] = config('constants.exam_status.pending');
    } else {
        $data['exam_application_status'] = null;
    }

    $data['exam_result'] = static::hasValue($data, 'exam_application_status')
        ? static::mapResultFromStatus('exam', (int) $data['exam_application_status'])
        : null;

    if (static::hasValue($data, 'initial_interview_final')) {
        $data['initial_interview_application_status'] = static::computeInitialInterviewApplicationStatus(
            (float) $data['initial_interview_final']
        );
    } elseif (static::hasValue($data, 'initial_interview_plan_date')) {
        $data['initial_interview_application_status'] = 1;
    } else {
        $data['initial_interview_application_status'] = null;
    }

    $data['initial_interview_result'] = static::hasValue($data, 'initial_interview_application_status')
        ? static::mapResultFromStatus('initial_interview', (int) $data['initial_interview_application_status'])
        : null;

    if (!static::hasValue($data, 'final_interview_application_status') && static::hasValue($data, 'final_interview_date')) {
        $data['final_interview_application_status'] = 1;
    }

    $data['final_interview_result'] = static::hasValue($data, 'final_interview_application_status')
        ? static::mapResultFromStatus('final_interview', (int) $data['final_interview_application_status'])
        : null;

    if (!static::hasValue($data, 'job_offer_status') && static::hasValue($data, 'job_offer_schedule')) {
        $data['job_offer_status'] = 1;
    }

    return $data;


}



protected static function computeExamApplicationStatus(
    float $attp,
    float $git,
    float $prg,
    ActionApplicant $applicant
): int {
    $rules = config('constants.application_score_rules.exam');
    $category = $applicant->resolveExamCategory();
    $categoryRules = $rules[$category] ?? [];

    $passed = $categoryRules['passed'] ?? null;
    $p2 = $categoryRules['p2'] ?? null;
logger([
    'category' => $category,
    'attp' => $attp,
    'git' => $git,
    'prg' => $prg,
    'rules' => config('constants.application_score_rules.exam'),
]);
    if (
        $passed &&
        $attp >= $passed['attp'] &&
        $git >= $passed['git'] &&
        $prg >= $passed['prg']
    ) {
        return 5;
    }

    if (
        $p2 &&
        $attp >= $p2['attp'] &&
        $git >= $p2['git'] &&
        $prg >= $p2['prg']
    ) {
        return 3;
    }

    return 6;
}

protected static function computeInitialInterviewApplicationStatus(float $score): int
{
    $rules = config('constants.application_score_rules.initial_interview');

    $passedMax = (float) ($rules['passed_min'] ?? 2.0);
    $p2Max = (float) ($rules['p2_min'] ?? 2.5);
    $failedMax = (float) ($rules['failed_min'] ?? 4.0);

    if ($score <= $passedMax) {
        return 3;
    }

    if ($score <= $p2Max) {
        return 4;
    }

    if ($score <= $failedMax) {
        return 5;
    }

    return 2;
}

protected static function mapResultFromStatus(string $type, int $status): ?int
{
    return config("constants.application_result_map.{$type}.{$status}");
}

protected static function hasValue(array $data, string $key): bool
{
    return array_key_exists($key, $data)
        && $data[$key] !== null
        && $data[$key] !== '';
}

public function getEditableStagesFor(User $user): array
{
    $permission = (int) $user->permissions;

    $editableStages = [
        'exam' => false,
        'initial_interview' => false,
        'final_interview' => false,
        'job_offer' => false,
        'general' => false,
        'documents' => false,
    ];

    if (in_array($permission, config('constants.full_edit_permissions', []), true)) {
        return [
            'exam' => true,
            'initial_interview' => true,
            'final_interview' => true,
            'job_offer' => true,
            'general' => true,
            'documents' => true,
        ];
    }

    $approvedAssignments = $this->interviews
    ->where('interviewer_id', $user->id)
    ->whereIn('status', [
        config('constants.interview_assignment_status.approved'),
        config('constants.interview_assignment_status.completed'),
    ]);

    foreach ($approvedAssignments as $assignment) {
        if ((int) $assignment->interview_type === config('constants.interview_types.exam')) {
            $editableStages['exam'] = true;
        }

        if ((int) $assignment->interview_type === config('constants.interview_types.initial')) {
            $editableStages['initial_interview'] = true;
        }

        if ((int) $assignment->interview_type === config('constants.interview_types.final')) {
            $editableStages['final_interview'] = true;
        }
    }

    return $editableStages;
}

public function hasAnyEditableStageFor(User $user): bool
{
    return in_array(true, $this->getEditableStagesFor($user), true);
}

public function syncInterviewStatusesFromStageResults(): void
{
    if (in_array((int) $this->exam_result, [
        config('constants.application_results.passed'),
        config('constants.application_results.failed'),
    ], true)) {
        ActionApplicationInterview::where('action_application_id', $this->id)
            ->where('interview_type', config('constants.interview_types.exam'))
            ->where('status', '!=', config('constants.interview_assignment_status.declined'))
            ->update([
                'status' => config('constants.interview_assignment_status.completed'),
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);
    }

    if (in_array((int) $this->initial_interview_result, [
        config('constants.application_results.passed'),
        config('constants.application_results.failed'),
    ], true)) {
        ActionApplicationInterview::where('action_application_id', $this->id)
            ->where('interview_type', config('constants.interview_types.initial'))
            ->where('status', '!=', config('constants.interview_assignment_status.declined'))
            ->update([
                'status' => config('constants.interview_assignment_status.completed'),
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);
    }

    if (in_array((int) $this->final_interview_result, [
        config('constants.application_results.passed'),
        config('constants.application_results.failed'),
    ], true)) {
        ActionApplicationInterview::where('action_application_id', $this->id)
            ->where('interview_type', config('constants.interview_types.final'))
            ->where('status', '!=', config('constants.interview_assignment_status.declined'))
            ->update([
                'status' => config('constants.interview_assignment_status.completed'),
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);
    }
}

public function finalInterviewAssignments()
{
    return $this->hasMany(ActionApplicationInterview::class, 'action_application_id', 'id')
        ->where('interview_type', config('constants.interview_types.final'));
}

public function initialInterviewAssignments()
{
    return $this->hasMany(ActionApplicationInterview::class, 'action_application_id', 'id')
        ->where('interview_type', config('constants.interview_types.initial'));
}

public function examAssignments()
{
    return $this->hasMany(ActionApplicationInterview::class, 'action_application_id', 'id')
        ->where('interview_type', config('constants.interview_types.exam'));
}
public function computeFinalInterviewOverallResult(): ?int
{
    $rows = $this->finalInterviewAssignments()->get();

    if ($rows->isEmpty()) {
        return null;
    }

    $evaluatedRows = $rows->filter(fn ($row) => in_array((int) $row->evaluation_result, [
        config('constants.application_results.passed'),
        config('constants.application_results.failed'),
    ], true));

    if ($evaluatedRows->isEmpty()) {
        return config('constants.application_results.pending');
    }

    $allPassed = $evaluatedRows->every(fn ($row) =>
        (int) $row->evaluation_result === config('constants.application_results.passed')
    );

    if ($allPassed && $evaluatedRows->count() === $rows->count()) {
        return config('constants.application_results.passed');
    }

    $allFailed = $evaluatedRows->every(fn ($row) =>
        (int) $row->evaluation_result === config('constants.application_results.failed')
    );

    if ($allFailed && $evaluatedRows->count() === $rows->count()) {
        return config('constants.application_results.failed');
    }

    return null;
}

public function hasMixedFinalInterviewEvaluations(): bool
{
    $rows = $this->finalInterviewAssignments()->get();

    $evaluatedRows = $rows->filter(fn ($row) => in_array((int) $row->evaluation_result, [
        config('constants.application_results.passed'),
        config('constants.application_results.failed'),
    ], true));

    if ($evaluatedRows->count() < 2) {
        return false;
    }

    $hasPassed = $evaluatedRows->contains(fn ($row) =>
        (int) $row->evaluation_result === config('constants.application_results.passed')
    );

    $hasFailed = $evaluatedRows->contains(fn ($row) =>
        (int) $row->evaluation_result === config('constants.application_results.failed')
    );

    return $hasPassed && $hasFailed;
}

public function syncFinalInterviewOutcomeFromAssignments(bool $allowManualMixed = true): void
{
    $overallResult = $this->computeFinalInterviewOverallResult();

    if ($overallResult !== null) {
        $this->update([
            'final_interview_result' => $overallResult,
            'final_interview_application_status' => $overallResult === config('constants.application_results.passed')
                ? 3
                : 5,
            'updated_by' => auth()->id(),
            'updated_time' => now(),
        ]);

        return;
    }

    if ($this->hasMixedFinalInterviewEvaluations()) {
        if (!$allowManualMixed) {
            $this->update([
                'final_interview_result' => config('constants.application_results.pending'),
                'final_interview_application_status' => 2,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);
        }
    }
}

public function getEvaluatedFinalInterviewAssignments()
{
    return $this->finalInterviewAssignments()
        ->whereIn('evaluation_result', [
            config('constants.application_results.passed'),
            config('constants.application_results.failed'),
        ])
        ->get();
}

public function allFinalInterviewersPassed(): bool
{
    $rows = $this->getEvaluatedFinalInterviewAssignments();

    return $rows->isNotEmpty() &&
        $rows->every(fn ($row) =>
            (int) $row->evaluation_result === config('constants.application_results.passed')
        );
}

public function allFinalInterviewersFailed(): bool
{
    $rows = $this->getEvaluatedFinalInterviewAssignments();

    return $rows->isNotEmpty() &&
        $rows->every(fn ($row) =>
            (int) $row->evaluation_result === config('constants.application_results.failed')
        );
}
public function hasMixedFinalInterviewResults(): bool
{
    $rows = $this->getEvaluatedFinalInterviewAssignments();

    if ($rows->count() < 2) {
        return false;
    }

    $hasPassed = $rows->contains(fn ($row) =>
        (int) $row->evaluation_result === config('constants.application_results.passed')
    );

    $hasFailed = $rows->contains(fn ($row) =>
        (int) $row->evaluation_result === config('constants.application_results.failed')
    );

    return $hasPassed && $hasFailed;
}

public function syncOverallFinalInterviewResult(?int $manualResult = null, bool $allowManual = false): void
{
    $result = null;
    $status = null;

    if ($this->allFinalInterviewersPassed()) {
        $result = config('constants.application_results.passed');
        $status = 3; // Passed
    } elseif ($this->allFinalInterviewersFailed()) {
        $result = config('constants.application_results.failed');
        $status = 5; // Failed
    } elseif ($this->hasMixedFinalInterviewResults()) {
        if (
            $allowManual &&
            in_array($manualResult, [
                config('constants.application_results.passed'),
                config('constants.application_results.failed'),
            ], true)
        ) {
            $result = $manualResult;
            $status = $manualResult === config('constants.application_results.passed') ? 3 : 5;
        } else {
            $result = config('constants.application_results.pending');
            $status = 2; // Done / for deliberation
        }
    } elseif ($this->finalInterviewAssignments()->exists()) {
        $result = config('constants.application_results.pending');
        $status = 1; // Pending
    }

    $this->update([
        'final_interview_result' => $result,
        'final_interview_application_status' => $status,
        'updated_by' => auth()->id(),
        'updated_time' => now(),
    ]);
}

public function hasFailedStage(string $stage): bool
{
    return match ($stage) {
        'exam' => (int) $this->exam_result === config('constants.application_results.failed'),
        'initial' => (int) $this->initial_interview_result === config('constants.application_results.failed'),
        'final' => (int) $this->final_interview_result === config('constants.application_results.failed'),
        default => false,
    };
}

public function isStageBlocked(string $stage): bool
{
    return match ($stage) {
        'initial' => $this->hasFailedStage('exam'),
        'final'   => $this->hasFailedStage('exam') || $this->hasFailedStage('initial'),
        'job_offer' => $this->hasFailedStage('exam')
            || $this->hasFailedStage('initial')
            || $this->hasFailedStage('final'),
        default => false,
    };
}

public function clearBlockedStages(): void
{
    if ($this->hasFailedStage('exam')) {
        $this->update([
            'initial_interview_plan_date' => null,
            'initial_interview_actual_date' => null,
            'initial_interview_final' => null,
            'initial_interview_result' => null,
            'initial_interview_application_status' => null,
            'initial_interview_remarks' => null,
        ]);
    }

    if ($this->hasFailedStage('exam') || $this->hasFailedStage('initial')) {
        $this->update([
            'final_interview_date' => null,
            'final_interview_final' => null,
            'final_interview_result' => null,
            'final_interview_application_status' => null,
            'final_interview_remarks' => null,
        ]);

        ActionApplicationInterview::where('action_application_id', $this->id)
            ->where('interview_type', config('constants.interview_types.final'))
            ->delete();
    }

    if ($this->hasFailedStage('exam') || $this->hasFailedStage('initial') || $this->hasFailedStage('final')) {
        $this->update([
            'job_offer_schedule' => null,
            'job_offer_status' => null,
            'job_offer_remarks' => null,
        ]);
    }
}
}
