<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionApplicant extends Model
{
    protected $table = 'action_applicants';

    public $timestamps = false; // since you use created_time / updated_time

    protected $fillable = [
        'source_type',
        'source',
        'other_source',
        'last_name',
        'first_name',
        'middle_name',
        'email_address',
        'gender',
        'age',
        'school',
        'degree',
        'others_degree',
        'expected_graduation',
        'awards_recognition',
        'other_examination_certificate',
        'thesis_project',
        'extra_curricular',
        'remarks',
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

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function applications()
    {
        return $this->hasMany(ActionApplication::class, 'action_applicant_id');
    }

    public function programmingLanguages()
    {
        return $this->hasMany(ActionApplicantProgrammingLanguage::class, 'action_applicant_id', 'id');
    }

    public function updateWithRequest(array $data, ?int $userId = null)
    {
        // Handle source / other_source logic first
        $sourceType = (int) ($data['source_type'] ?? $this->source_type);

        if ($sourceType === 3) {
            $this->source = $data['source'] ?? null;
            $this->other_source = $data['other_source'] ?? null; // keep if provided
        } elseif (in_array($sourceType, [1, 2, 4, 5])) {
            $this->other_source = $data['other_source'] ?? null;
            $this->source = null;
        } else {
            $this->source = $data['source'] ?? null;
            $this->other_source = $data['other_source'] ?? null;
        }

        // Mass assignment for fillable fields (except created_by / created_time)
        $fieldsToUpdate = [
            'source_type',
            'source',
            'other_source',
            'last_name',
            'first_name',
            'middle_name',
            'email_address',
            'gender',
            'age',
            'school',
            'degree',
            'others_degree',
            'expected_graduation',
            'awards_recognition',
            'other_examination_certificate',
            'thesis_project',
            'extra_curricular',
            'remarks',
        ];

        foreach ($fieldsToUpdate as $field) {
            if (isset($data[$field])) {
                $this->$field = $data[$field];
            }
        }

        // Update audit fields
        $this->updated_by = $userId;
        $this->updated_time = now();

        return $this->save();
    }
}