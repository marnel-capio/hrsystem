<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionApplication extends Model
{
    protected $table = 'action_applicant_applications';
    public $timestamps = false;

    protected $fillable = [
        'action_applicant_id',
        'action_batch_id',
        'upload_resume',
        'exam_application_status',
        'exam_plan_date',
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
            'resource_schedules.trainees_from', // ✅ use resource_schedules
            'action_applicant_applications.action_applicant_id',
            'action_applicant_applications.action_batch_id',
            'action_batches.action_batch',
            'action_applicants.first_name',
            'action_applicants.last_name'
        )
        ->join('action_batches', 'action_applicant_applications.action_batch_id', '=', 'action_batches.id')
        ->join('action_applicants', 'action_applicant_applications.action_applicant_id', '=', 'action_applicants.id')
        ->join('resource_schedules', 'action_batches.resource_schedule_id', '=', 'resource_schedules.id') // ✅ join schedule
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('action_applicants.first_name', 'like', "%{$search}%")
                  ->orWhere('action_applicants.last_name', 'like', "%{$search}%")
                  ->orWhere('action_batches.action_batch', 'like', "%{$search}%")
                  ->orWhereRaw("resource_schedules.trainees_from LIKE ?", ["%{$search}%"]) // ✅ filter by schedule
                  ->orWhere('action_applicant_applications.id', 'like', "%{$search}%");
            });
        })
        ->orderBy('action_applicant_applications.created_time', 'desc')
        ->get();
}
public static function updateOrCreateFromRow($applicantId, $batchId, array $row, $exam_application_status, $exam_plan_date, $now)
{
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
            'created_time' => $now,
            'updated_by' => Auth::id(),
            'updated_time' => $now,
        ]
    );
}
}