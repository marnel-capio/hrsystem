<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActionApplicant extends Model
{
    protected $table = 'action_applicants';
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email_address',
        'contact_number',
        'address',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public static function getAllActionApplicants()
    {
        $sourceTypes = config('constants.sourceTypes');
        $sources = config('constants.sources');
        $genders = config('constants.genders');

        return self::orderBy('created_time', 'desc')
            ->get()
            ->map(function ($a) use ($sourceTypes, $sources, $genders) {
                return [
                    'id' => $a->id,
                    'source_type' => $sourceTypes[$a->source_type] ?? '',
                    'source' => $sources[$a->source] ?? '',
                    'other_source' => $a->other_source,
                    'last_name' => $a->last_name,
                    'first_name' => $a->first_name,
                    'middle_name' => $a->middle_name,
                    'email_address' => $a->email_address,
                    'gender' => $genders[$a->gender] ?? '',
                    'age' => $a->age,
                    'school' => $a->school,
                    'degree' => $a->degree,
                    'others_degree' => $a->others_degree,
                    'expected_graduation' => $a->expected_graduation,
                    'awards_recognition' => $a->awards_recognition,
                    'other_examination_certificate' => $a->other_examination_certificate,
                    'thesis_project' => $a->thesis_project,
                    'extra_curricular' => $a->extra_curricular,
                    'remarks' => $a->remarks,
                ];
            });
    }


    /**
     * Get eligible applicants for a specific batch (STATIC METHOD - CORRECT)
     */
    public static function getEligibleApplicantsForBatch($batchId)
    {
        return self::select(
                'action_applicants.id',
                DB::raw("CONCAT(action_applicants.first_name, ' ', action_applicants.last_name, ' (', action_applicants.email_address, ')') as full_name")
            )
            // Exclude applicants who already applied for this batch
            ->whereNotExists(function($query) use ($batchId) {
                $query->select(DB::raw(1))
                    ->from('action_applicant_applications')
                    ->whereColumn('action_applicant_applications.action_applicant_id', 'action_applicants.id')
                    ->where('action_applicant_applications.action_batch_id', $batchId);
            })
            // Exclude applicants with failed applications in last 6 months
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('action_applicant_applications')
                    ->whereColumn('action_applicant_applications.action_applicant_id', 'action_applicants.id')
                    ->where('action_applicant_applications.created_time', '>=', now()->subDays(180))
                    ->where(function($q) {
                        $q->whereIn('action_applicant_applications.exam_application_status', [6, 7])
                          ->orWhere('action_applicant_applications.initial_interview_result', 3)
                          ->orWhere('action_applicant_applications.final_interview_result', 3)
                          ->orWhereIn('action_applicant_applications.job_offer_status', [4, 5, 6]);
                    });
            })
            ->orderBy('action_applicants.last_name')
            ->orderBy('action_applicants.first_name')
            ->get()
            ->pluck('full_name', 'id')
            ->toArray();
    }


    /**
     * Relationships
     */
    public function applications()
    {
        return $this->hasMany(ActionApplication::class, 'action_applicant_id', 'id');
    }

    public function latestApplication()
    {
        return $this->hasOne(ActionApplication::class, 'action_applicant_id', 'id')
            ->latest('created_time');
    }
}