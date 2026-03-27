<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

//relationships
    public function applicant()
        {
            return $this->belongsTo(ActionApplicant::class, 'action_applicant_id', 'id');
        }

         /**
     * Get all applications with mapped human-readable fields
     */
    public static function getAllApplications()
    {
        $examVenues = config('constants.examVenues');
        $examResults = config('constants.examResults');
        $examStatuses = config('constants.examApplicationStatuses');
        $traineesFrom = config('constants.traineesFrom');

        return self::with('actionApplicant')
            ->orderBy('created_time', 'desc')
            ->get()
            ->map(function ($app) use ($examVenues, $examResults, $examStatuses, $traineesFrom) {
                return [
                    'id' => $app->id,
                    'action_applicant_id' => $app->action_applicant_id,
                    'applicant_name' => $app->actionApplicant ?
                        trim($app->actionApplicant->first_name . ' ' . $app->actionApplicant->last_name) : '',
                    'action_batch_id' => $app->action_batch_id,
                    'upload_resume' => $app->upload_resume,
                    'upload_tor' => $app->upload_tor,
                    'upload_pic' => $app->upload_pic,
                    'exam_plan_date' => $app->exam_plan_date,
                    'exam_actual_date' => $app->exam_actual_date,
                    'exam_venue' => $examVenues[$app->exam_venue] ?? '',
                    'exam_atpp_result' => $app->exam_atpp_result,
                    'exam_git_result' => $app->exam_git_result,
                    'exam_prg_result' => $app->exam_prg_result,
                    'exam_result' => $examResults[$app->exam_result] ?? '',
                    'exam_application_status' => $examStatuses[$app->exam_application_status] ?? '',
                    'exam_remarks' => $app->exam_remarks,
                    'initial_interview_plan_date' => $app->initial_interview_plan_date,
                    'initial_interview_actual_date' => $app->initial_interview_actual_date,
                    'initial_interview_final' => $app->initial_interview_final,
                    'initial_interview_result' => config('constants.interviewResults')[$app->initial_interview_result] ?? '',
                    'initial_interview_application_status' => config('constants.applicationStatuses')[$app->initial_interview_application_status] ?? '',
                    'final_interview_date' => $app->final_interview_date,
                    'final_interview_sf' => $app->final_interview_sf,
                    'final_interview_ib' => $app->final_interview_ib,
                    'final_interview_rv' => $app->final_interview_rv,
                    'final_interview_ma' => $app->final_interview_ma,
                    'final_interview_final' => $app->final_interview_final,
                    'final_interview_result' => config('constants.interviewResults')[$app->final_interview_result] ?? '',
                    'final_interview_application_status' => config('constants.applicationStatuses')[$app->final_interview_application_status] ?? '',
                    'job_offer_schedule' => $app->job_offer_schedule,
                    'job_offer_status' => config('constants.jobOfferStatuses')[$app->job_offer_status] ?? '',
                    'trainees_from' => $traineesFrom[$app->trainees_from] ?? '',
                    'remarks' => $app->remarks,
                ];
            });
    }

    /**
     * Create a new application
     */
    public static function createApplication(array $data)
    {
        $data['created_time'] = now();
        $data['updated_time'] = now();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return self::create($data);
    }

    /**
     * Update an existing application
     */
    public function updateApplication(array $data)
    {
        $data['updated_time'] = now();
        $data['updated_by'] = Auth::id();
        $this->update($data);
        return $this;
    }

    /**
     * Upsert application by applicant_id and batch_id
     */
    public static function upsertByKeys(array $data)
    {
        $applicantId = $data['action_applicant_id'] ?? null;
        $batchId = $data['action_batch_id'] ?? null;

        if (!$applicantId || !$batchId) {
            throw new \Exception('Action applicant ID and batch ID are required for upsert.');
        }

        $application = self::where('action_applicant_id', $applicantId)
                         ->where('action_batch_id', $batchId)
                         ->first();

        if ($application) {
            return $application->updateApplication($data);
        }

        return self::createApplication($data);
    }

}
