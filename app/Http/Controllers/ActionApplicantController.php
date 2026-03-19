<?php

namespace App\Http\Controllers;

use App\Models\ActionApplicant;
use Inertia\Inertia;

class ActionApplicantController extends Controller
{
    public function index()
    {
        $sourceTypes = config('constants.sourceTypes');
        $sources = config('constants.sources');

        $genders = [
            1 => 'Male',
            2 => 'Female'
        ];

        // Fetch applicants ordered by created_time descending
        $applicants = ActionApplicant::orderBy('created_time', 'desc')->get()->map(function ($a) use ($sourceTypes, $sources, $genders) {
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

        return Inertia::render('action/applicants/Index', [
            'applicants' => $applicants
        ]);
    }
}