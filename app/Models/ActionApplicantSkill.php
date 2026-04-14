<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionApplicantSkill extends Model
{
    protected $table = 'action_applicants_skills';

    protected $fillable = [
        'action_applicant_id',
        'skill',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public $timestamps = false;

    /**
     * Relationship to applicant
     */
    public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id');
    }

    /**
     * Fetch all skills for a specific applicant
     */
    public static function forApplicant($applicantId)
    {
        return self::where('action_applicant_id', $applicantId)
            ->select('id', 'skill', 'remarks')
            ->get();
    }

    /**
     * Create a new skill
     */
    public static function addSkill($applicantId, array $data)
    {
        return self::create([
            'action_applicant_id' => $applicantId,
            'skill' => $data['skill'],
            'remarks' => $data['remarks'] ?? null,
            'created_by' => auth()->id() ?? 1,
            'created_time' => now(),
            'updated_by' => auth()->id() ?? 1,
            'updated_time' => now(),
        ]);
    }

    /**
     * Update existing skill
     */
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

    /**
     * Delete skill
     */
    public static function deleteSkill($skillId)
    {
        $skill = self::findOrFail($skillId);
        return $skill->delete();
    }

    /**
     * Bulk delete skills for an applicant
     */
    public static function bulkDeleteSkills($applicantId, array $ids)
    {
        return self::where('action_applicant_id', $applicantId)
            ->whereIn('id', $ids)
            ->delete();
    }
}