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
        'source_date',
        'updated_by',
        'updated_time',
        'other_source',
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

public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id', 'id');
    }
}
