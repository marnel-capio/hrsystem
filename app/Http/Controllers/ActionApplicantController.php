<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ActionApplicant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterActionApplicantRequest;

class ActionApplicantController extends Controller
{
    public function create()
    {
        return Inertia::render('action/applicants/Register', [
            'sourceTypes' => config('constants.sourceTypes'),
            'sources' => config('constants.sources'),
            'genders' => config('constants.genders'),
        ]);
    }

    public function index()
    {
        return Inertia::render('action/applicants/Index');
    }

    public function store(RegisterActionApplicantRequest $request)
    {
        $now = now(); // current timestamp

        ActionApplicant::create([
            'source_type' => $request->source_type,
            'source' => $request->source,
            'other_source' => $request->other_source,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'email_address' => $request->email_address,
            'gender' => $request->gender,
            'age' => $request->age,
            'school' => $request->school,
            'degree' => $request->degree,
            'others_degree' => $request->others_degree,
            'expected_graduation' => $request->expected_graduation,
            'awards_recognition' => $request->awards_recognition,
            'other_examination_certificate' => $request->other_examination_certificate,
            'thesis_project' => $request->thesis_project,
            'extra_curricular' => $request->extra_curricular,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_time' => $now,
            'updated_time' => $now,
        ]);

        return redirect()->route('action.applicants.index')
                         ->with('success', 'Applicant created successfully.');
    }
}