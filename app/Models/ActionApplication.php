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

    /**
     * List page data with search functionality
     */
    public static function listPageData(?string $search = null)
    {
        return static::query()
            ->select(
                'action_applicant_applications.id',
                'action_applicant_applications.action_applicant_id',
                'action_applicant_applications.action_batch_id',
                'action_batches.action_batch',
                'action_applicants.first_name',
                'action_applicants.last_name',
                'action_applicants.email_address'
            )
            ->join('action_batches', 'action_applicant_applications.action_batch_id', '=', 'action_batches.id')
            ->join('action_applicants', 'action_applicant_applications.action_applicant_id', '=', 'action_applicants.id')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action_applicants.first_name', 'like', "%{$search}%")
                      ->orWhere('action_applicants.last_name', 'like', "%{$search}%")
                      ->orWhere('action_applicants.email_address', 'like', "%{$search}%")
                      ->orWhere('action_batches.action_batch', 'like', "%{$search}%")
                      ->orWhere('action_applicant_applications.id', 'like', "%{$search}%");
                });
            })
            ->orderBy('action_applicant_applications.created_time', 'desc')
            ->get();
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
     * Relationships
     */
    public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(ActionBatchModel::class, 'action_batch_id', 'id');
    }
}
