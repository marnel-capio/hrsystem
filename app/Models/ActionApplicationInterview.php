<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActionApplicationInterview extends Model
{
    use HasFactory;

    protected $table = 'action_application_interviews';
    protected $primaryKey = 'id';
    public $timestamps = false;

protected $fillable = [
    'interviewer_id',
    'action_application_id',
    'interview_type',

    'scheduled_date',
    'actual_date',
    'status',
    'decline_reason',
    'pending_approval_notified_at',

    'exam_atpp_result',
    'exam_git_result',
    'exam_prg_result',
    'exam_remarks',
    'initial_interview_result',
    'initial_interview_remark',
    'final_interview_result',
    'final_interview_remarks',

    'score',
    'evaluation_result',
    'evaluation_remarks',

    'remarks',
    'created_by',
    'created_time',
    'updated_by',
    'updated_time',
];
protected $casts = [
    'pending_approval_notified_at' => 'datetime',
    'scheduled_date' => 'datetime',
    'actual_date' => 'datetime',
    'score' => 'decimal:2',
    'evaluation_result' => 'integer',
    'created_time' => 'datetime',
    'updated_time' => 'datetime',
];

    public function application()
    {
        return $this->belongsTo(ActionApplication::class, 'action_application_id', 'id');
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id', 'id');
    }

    public static function createInterview(array $data)
    {
        $data['created_by'] = Auth::id();
        $data['created_time'] = now();
        $data['updated_by'] = Auth::id();
        $data['updated_time'] = now();

        return self::create($data);
    }

    public function updateInterview(array $data)
    {
        $data['updated_by'] = Auth::id();
        $data['updated_time'] = now();

        $this->update($data);

        return $this;
    }

public function toDisplayArray(): array
{
    $interviewer = $this->interviewer;

    return [
        'id' => $this->id,
        'interviewer_id' => $this->interviewer_id,
        'name' => $interviewer?->full_name ?: 'N/A',
        'email_address' => $interviewer?->email_address,
        'pending_approval_notified_at' => $this->pending_approval_notified_at,
        'decline_reason' => $this->decline_reason,
        'role_label' => $interviewer ? match ((int) $interviewer->permissions) {
            config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
            config('constants.HR_MANAGER_PERMISSION.value') => 'HR Manager',
            config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
            config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
            config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
            default => 'User',
        } : 'N/A',
        'interview_type' => (int) $this->interview_type,
        'scheduled_date' => $this->scheduled_date
            ? \Carbon\Carbon::parse($this->scheduled_date)->format('Y-m-d H:i:s')
            : null,
        'actual_date' => $this->actual_date
            ? \Carbon\Carbon::parse($this->actual_date)->format('Y-m-d H:i:s')
            : null,
        'status' => (int) ($this->status ?? 1),
        'score' => $this->score !== null ? (float) $this->score : null,
        'evaluation_result' => $this->evaluation_result !== null ? (int) $this->evaluation_result : null,
        'evaluation_remarks' => $this->evaluation_remarks,
    ];
}

public function isFinalInterview(): bool
{
    return (int) $this->interview_type === (int) config('constants.interview_types.final');
}

public function isEvaluationComplete(): bool
{
    return in_array((int) $this->evaluation_result, [
        config('constants.application_results.passed'),
        config('constants.application_results.failed'),
    ], true);
}

}
