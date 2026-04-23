<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntermediateApplicantSkill extends Model
{
    protected $table = 'intermediate_applicants_skills';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'intermediate_applicant_id',
        'skill',
        'remarks',
        'is_deleted',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    /**
     * Indicates if the model should be timestamped.
     * (You are using custom timestamp columns)
     */
    public $timestamps = false;

    /**
     * Default attribute casting
     */
    protected $casts = [
        'is_deleted' => 'integer',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    /**
     * Scope: only active (not deleted) records
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }

    /**
     * Scope: only deleted records
     */
    public function scopeDeleted($query)
    {
        return $query->where('is_deleted', 1);
    }

    public function applicant()
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id');
    }

    public static function forApplicant($applicantId)
{
    return self::where('intermediate_applicant_id', $applicantId)
        ->active()
        ->select('id', 'skill', 'remarks')
        ->get();
}

public static function addSkill($applicantId, array $data)
{
    return self::create([
        'intermediate_applicant_id' => $applicantId,
        'skill' => $data['skill'],
        'remarks' => $data['remarks'] ?? null,
        'is_deleted' => 0,
        'created_by' => auth()->id() ?? 1,
        'created_time' => now(),
        'updated_by' => auth()->id() ?? 1,
        'updated_time' => now(),
    ]);
}

public static function updateSkill($skillId, array $data)
{
    $skill = self::findOrFail($skillId);

    $skill->skill = $data['skill'];
    $skill->remarks = $data['remarks'] ?? null;
    $skill->updated_by = auth()->id() ?? 1;
    $skill->updated_time = now();

    $skill->save();

    return $skill;
}

public static function deleteSkill($skillId)
{
    $skill = self::findOrFail($skillId);

    // SOFT DELETE (since you have is_deleted)
    $skill->is_deleted = 1;
    $skill->updated_by = auth()->id() ?? 1;
    $skill->updated_time = now();
    $skill->save();

    return $skill;
}

public static function bulkDeleteSkills($applicantId, array $ids)
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
