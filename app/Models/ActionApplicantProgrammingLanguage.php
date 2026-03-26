<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionApplicantProgrammingLanguage extends Model
{
    protected $table = 'action_applicants_programming_languages';

    protected $fillable = [
        'action_applicant_id',
        'program_language',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public $timestamps = false;

    // Relationship to applicant
    public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id');
    }

    /**
     * Fetch all languages for a specific applicant
     */
    public static function forApplicant($applicantId)
    {
        return self::where('action_applicant_id', $applicantId)
            ->select('id', 'program_language', 'remarks')
            ->get();
    }

    /**
     * Create a new programming language for an applicant
     */
    public static function addLanguage($applicantId, array $data)
    {
        return self::create([
            'action_applicant_id' => $applicantId,
            'program_language' => $data['program_language'],
            'remarks' => $data['remarks'] ?? null,
            'created_by' => auth()->id() ?? 1,
            'created_time' => now(),
            'updated_by' => auth()->id() ?? 1,
            'updated_time' => now(),
        ]);
    }

    /**
     * Update an existing language
     */
    public static function updateLanguage($langId, array $data)
    {
        $lang = self::findOrFail($langId);
        $lang->program_language = $data['program_language'];
        $lang->remarks = $data['remarks'] ?? null;
        $lang->updated_by = auth()->id() ?? 1;
        $lang->updated_time = now();
        $lang->save();

        return $lang;
    }

    /**
     * Delete a language
     */
    public static function deleteLanguage($langId)
    {
        $lang = self::findOrFail($langId);
        return $lang->delete();
    }

    /**
     * Bulk delete languages for an applicant
     */
    public static function bulkDeleteLanguages($applicantId, array $ids)
    {
        return self::where('action_applicant_id', $applicantId)
            ->whereIn('id', $ids)
            ->delete();
    }
}