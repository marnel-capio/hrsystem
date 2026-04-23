<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class IntermediateInterviewer extends Model
{
    protected $table = 'intermediate_application_interviews';

    public $timestamps = true;
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    protected $fillable = [
        'interviewer_id',
        'intermediate_application_id',
        'interview_type',
        'scheduled_date',
        'interview_status',
        'decline_reason',
        'pending_approval_notified_at',
        'evaluation_score',
        'evaluation_results',
        'evaluation_remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'pending_approval_notified_at' => 'datetime',
        'evaluation_score' => 'decimal:2',
        'interview_type' => 'integer',
        'interview_status' => 'integer',
        'evaluation_results' => 'integer',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(IntermediateApplication::class, 'intermediate_application_id');
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Status labels
    public function getStatusLabelAttribute(): string
    {
        return match ($this->interview_status) {
            1 => 'Pending Approval',
            2 => 'Approved',
            3 => 'Declined',
            4 => 'Done',
            default => '-'
        };
    }

    public function getStageLabelAttribute(): string
    {
        return match ($this->interview_type) {
            1 => 'Exam',
            2 => 'Initial Interview',
            3 => 'Final Interview',
            default => '-'
        };
    }

    public function getEvaluationResultLabelAttribute(): string
    {
        return match ($this->evaluation_results) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Passed',
            4 => 'P2',
            5 => 'Failed',
            default => '-'
        };
    }

    // Boot
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? 1;
            $model->updated_by = auth()->id() ?? 1;
            $model->interview_status = $model->interview_status ?? 1;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? 1;
        });
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::instance($date)
            ->timezone('Asia/Manila')
            ->format('Y-m-d H:i:s');
    }
}