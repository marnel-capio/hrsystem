<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceScheduleNotificationMail;
use App\Models\User;

class ResourceSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action_batch_id',
        'prev_batch_id',
        'target_location',
        'target_trainees',
        'deployment_date',
        'contact_schools_startdate',
        'contact_schools_enddate',
        'source_testing_startdate',
        'source_testing_enddate',
        'initial_interviews_startdate',
        'initial_interviews_enddate',
        'final_interviews_startdate',
        'final_interviews_enddate',
        'contract_offers_startdate',
        'contract_offers_enddate',
        'requirements_startdate',
        'requirements_enddate',
        'training_startdate',
        'training_enddate',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    // Relationships
    public function actionBatch()
    {
        return $this->belongsTo(ActionBatchModel::class, 'action_batch_id');
    }

    // Helpers
    public static function listPageData(?string $search = null)
    {
        return static::query()
            ->select('resource_schedules.*', 'action_batches.action_batch', 'action_batches.target_trainees')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action_batches.action_batch', 'like', "%{$search}%")
                      ->orWhere('resource_schedules.target_location', 'like', "%{$search}%")
                      ->orWhereRaw("DATE_FORMAT(resource_schedules.deployment_date, '%M %Y') LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('resource_schedules.created_time', 'desc')
            ->get();
    }

    public static function getActionBatches($excludeScheduled = true)
    {
        $scheduledBatchIds = self::pluck('action_batch_id')->toArray();

        $query = DB::table('action_batches')->select('id', 'action_batch', 'target_trainees', 'target_date');

        if ($excludeScheduled) {
            $query->whereNotIn('id', $scheduledBatchIds);
        } else {
            $query->whereIn('id', $scheduledBatchIds);
        }

        return $query->get();
    }

    public static function getWithActionBatch($id)
    {
        return self::select('resource_schedules.*', 'action_batches.action_batch')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->where('resource_schedules.id', $id)
            ->firstOrFail();
    }

    // ---------------------------------------
    // RECRUITMENT PROJECTION 
    // ---------------------------------------
    public function getRecruitmentProjection($batchId = null)
    {
        $batchId = $batchId ?? $this->action_batch_id;

        return DB::table('action_applicant_applications')
            ->where('action_batch_id', $batchId)
            ->get();
    }

    public function getProjection($prevSchedule = null)
    {
        $actualApps = $this->getRecruitmentProjection($this->action_batch_id);
        $planApps   = $prevSchedule ? $this->getRecruitmentProjection($prevSchedule->action_batch_id) : collect([]);

        $stages = [
            'examinees',
            'initial_interview',
            'final_interview',
            'accepted',
            'declined',
            'trainees_manila',
            'trainees_cebu',
        ];

        $counter = function($apps, $stage) {
            return match ($stage) {
                'examinees' => $apps->whereNotNull('exam_actual_date')->count(),
                'initial_interview' => $apps->whereNotNull('initial_interview_result')
                                            ->where('initial_interview_result', '!=', 1)->count(),
                'final_interview' => $apps->whereNotNull('final_interview_result')
                                          ->where('final_interview_result', '!=', 1)->count(),
                'accepted' => $apps->where('job_offer_status', 3)->count(),
                'declined' => $apps->where('job_offer_status', 4)->count(),
                'trainees_manila' => $apps->where('trainees_from', 1)->count(),
                'trainees_cebu' => $apps->where('trainees_from', 2)->count(),
                default => 0,
            };
        };

        $projection = [];

        foreach ($stages as $stage) {
            $actualNo = $counter($actualApps, $stage);
            $planNo   = $counter($planApps, $stage);

            $projection[$stage] = [
                'actual_no'  => $actualNo,
                'actual_pct' => $this->target_trainees > 0
                    ? round(($actualNo / $this->target_trainees) * 100, 2)
                    : 0,
                'plan_no'    => $planNo,
                'plan_pct'   => $this->target_trainees > 0
                    ? round(($planNo / $this->target_trainees) * 100, 2)
                    : 0,
            ];
        }

        return $projection;
    }

// Format WBS for frontend
public function formatWBS(): array
{
    return [
        'contact_schools' => ['start' => $this->contact_schools_startdate, 'end' => $this->contact_schools_enddate],
        'sourcing_testing' => ['start' => $this->source_testing_startdate, 'end' => $this->source_testing_enddate],
        'initial_interviews' => ['start' => $this->initial_interviews_startdate, 'end' => $this->initial_interviews_enddate],
        'final_interviews' => ['start' => $this->final_interviews_startdate, 'end' => $this->final_interviews_enddate],
        'contract_offers' => ['start' => $this->contract_offers_startdate, 'end' => $this->contract_offers_enddate],
        'requirements' => ['start' => $this->requirements_startdate, 'end' => $this->requirements_enddate],
        'training' => ['start' => $this->training_startdate, 'end' => $this->training_enddate],
    ];
}

// Get previous batch name
public function prevBatchName(): ?string
{
    if (!$this->prev_batch_id) return null;

    return DB::table('action_batches')
             ->where('id', $this->prev_batch_id)
             ->value('action_batch');
}

// Format target location as string
public function targetLocationName(): string
{
    return $this->target_location == 1 ? 'Manila' : 'Cebu';
}

// Format schedule for frontend (used in show)
public function formattedForShow(): array
{
    return [
        'id' => $this->id,
        'batch_name' => optional($this->actionBatch)->action_batch ?? 'Unknown',
        'prev_batch_name' => $this->prevBatchName(),
        'target_location' => $this->targetLocationName(),
        'target_trainees' => $this->target_trainees,
        'deployment_date' => $this->deployment_date,
        'wbs' => $this->formatWBS(),
        'job_acceptance' => $this->job_acceptance,
        'job_offer' => $this->job_offer,
        'final_interview' => $this->final_interview,
        'initial_interview' => $this->initial_interview,
        'screening' => $this->screening,
        'contact_schools' => $this->contact_schools,
        'remarks' => $this->remarks,
        'created_by' => $this->created_by,
        'created_time' => $this->created_time,
        'updated_by' => $this->updated_by,
        'updated_time' => $this->updated_time,
    ];
}

// Format schedule for edit page
public function formattedForEdit(): array
{
    return [
        'id' => $this->id,
        'action_batch_id' => (int)$this->action_batch_id,
        'prev_batch_id' => (int)$this->prev_batch_id,
        'target_location' => (string)$this->target_location,
        'target_trainees' => (int)$this->target_trainees,
        'deployment_date' => $this->deployment_date,
        'remarks' => $this->remarks,
        'contact_schools_startdate' => $this->contact_schools_startdate,
        'contact_schools_enddate' => $this->contact_schools_enddate,
        'source_testing_startdate' => $this->source_testing_startdate,
        'source_testing_enddate' => $this->source_testing_enddate,
        'initial_interviews_startdate' => $this->initial_interviews_startdate,
        'initial_interviews_enddate' => $this->initial_interviews_enddate,
        'final_interviews_startdate' => $this->final_interviews_startdate,
        'final_interviews_enddate' => $this->final_interviews_enddate,
        'contract_offers_startdate' => $this->contract_offers_startdate,
        'contract_offers_enddate' => $this->contract_offers_enddate,
        'requirements_startdate' => $this->requirements_startdate,
        'requirements_enddate' => $this->requirements_enddate,
        'training_startdate' => $this->training_startdate,
        'training_enddate' => $this->training_enddate,
    ];
}

// Current batch info for edit page
public function currentBatch()
{
    return DB::table('action_batches')->where('id', $this->action_batch_id)->first();
}


/**
 * Get original values of fields relevant for update comparison
 */
public function getOriginalValuesForUpdate(): array
{
    return $this->only([
        'action_batch_id',
        'prev_batch_id',
        'target_location',
        'target_trainees',
        'deployment_date',
        'contact_schools_startdate',
        'contact_schools_enddate',
        'source_testing_startdate',
        'source_testing_enddate',
        'initial_interviews_startdate',
        'initial_interviews_enddate',
        'final_interviews_startdate',
        'final_interviews_enddate',
        'contract_offers_startdate',
        'contract_offers_enddate',
        'requirements_startdate',
        'requirements_enddate',
        'training_startdate',
        'training_enddate',
        'remarks',
    ]);
}

}