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

        // NEW
        'scheduled_date',
        'actual_date',
        'status',
        'decline_reason',
        'pending_approval_notified_at',

        // existing
        'exam_atpp_result',
        'exam_git_result',
        'exam_prg_result',
        'exam_remarks',
        'initial_interview_result',
        'initial_interview_remark',
        'final_interview_result',
        'final_interview_remarks',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
    'pending_approval_notified_at' => 'datetime',
    'scheduled_date' => 'datetime',
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
        'role_label' => $interviewer?->role_label ?? 'N/A',
        'interview_type' => (int) $this->interview_type,
        'scheduled_date' => $this->scheduled_date,
        'status' => (int) ($this->status ?? config('constants.interview_assignment_status.pending_approval')),
        'decline_reason' => $this->decline_reason,
    ];
}
}
