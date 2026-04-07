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
        'final_interview_sf',
        'final_interview_ib',
        'final_interview_rv',
        'final_interview_ma',
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

    public static function normalizeComputedFields(array $data): array
{
    $applicant = ActionApplicant::find($data['action_applicant_id'] ?? null);

    if (!$applicant) {
        return $data;
    }


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

logger([
    'computed_exam_application_status' => $computedExamStatus,
]);

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

    $failedMin = (float) ($rules['failed_min'] ?? 4.0);
    $p2Min = (float) ($rules['p2_min'] ?? 2.5);
    $passedMin = (float) ($rules['passed_min'] ?? 2.0);

    if ($score >= $failedMin) {
        return 5;
    }

    if ($score >= $p2Min) {
        return 4;
    }

    if ($score >= $passedMin) {
        return 3;
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

}
