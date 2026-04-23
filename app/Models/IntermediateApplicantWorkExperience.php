<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntermediateApplicantWorkExperience extends Model
{
    protected $table = 'intermediate_applicants_work_experiences';

    protected $fillable = [
        'intermediate_applicant_id',
        'employer',
        'company_address',
        'job_title',
        'date_employed',
        'work_description',
        'salary',
        'reason_for_leaving',
        'name_supervisor',
        'remarks',
        'is_deleted',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public $timestamps = false;

    public function applicant()
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }

    public static function forApplicant($applicantId)
    {
        return self::where('intermediate_applicant_id', $applicantId)
            ->where('is_deleted', 0)
            ->get([
                'id',
                'employer',
                'company_address',
                'job_title',
                'date_employed',
                'work_description',
                'salary',
                'reason_for_leaving',
                'name_supervisor',
                'remarks',
            ]);
    }

    public static function addWorkExperience($applicantId, array $data)
    {
        return self::create([
            'intermediate_applicant_id' => $applicantId,
            'employer' => $data['employer'] ?? null,
            'company_address' => $data['company_address'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'date_employed' => $data['date_employed'] ?? null,
            'work_description' => $data['work_description'] ?? null,
            'salary' => $data['salary'] ?? null,
            'reason_for_leaving' => $data['reason_for_leaving'] ?? null,
            'name_supervisor' => $data['name_supervisor'] ?? null,
            'remarks' => $data['remarks'] ?? null,
            'is_deleted' => 0,
            'created_by' => auth()->id() ?? 1,
            'created_time' => now(),
            'updated_by' => auth()->id() ?? 1,
            'updated_time' => now(),
        ]);
    }

    public static function updateWorkExperience($id, array $data)
    {
        $work = self::findOrFail($id);

        $work->employer = $data['employer'] ?? null;
        $work->company_address = $data['company_address'] ?? null;
        $work->job_title = $data['job_title'] ?? null;
        $work->date_employed = $data['date_employed'] ?? null;
        $work->work_description = $data['work_description'] ?? null;
        $work->salary = $data['salary'] ?? null;
        $work->reason_for_leaving = $data['reason_for_leaving'] ?? null;
        $work->name_supervisor = $data['name_supervisor'] ?? null;
        $work->remarks = $data['remarks'] ?? null;
        $work->updated_by = auth()->id() ?? 1;
        $work->updated_time = now();
        $work->save();

        return $work;
    }

    public static function deleteWorkExperience($id)
    {
        $work = self::findOrFail($id);
        $work->is_deleted = 1;
        $work->updated_by = auth()->id() ?? 1;
        $work->updated_time = now();
        $work->save();

        return $work;
    }

    public static function bulkDeleteWorkExperiences($applicantId, array $ids)
    {
        return self::where('intermediate_applicant_id', $applicantId)
            ->whereIn('id', $ids)
            ->update([
                'is_deleted' => 1,
                'updated_by' => auth()->id() ?? 1,
                'updated_time' => now(),
            ]);
    }
}
