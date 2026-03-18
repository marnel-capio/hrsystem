<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ActionApplicant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActionApplicantController extends Controller
{
    public function create()
    {
        return Inertia::render('action/applicants/Register');
    }

    public function index()
    {
        return Inertia::render('action/applicants/Index');
    }

    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'source_type' => 'required|integer|between:1,5',
            'source' => 'nullable|integer',
            'other_source' => 'nullable|string|max:80',
            'last_name' => 'required|string|max:80',
            'first_name' => 'required|string|max:80',
            'middle_name' => 'nullable|string|max:80',
            'email_address' => 'required|email|unique:action_applicants,email_address',
            'gender' => 'required|integer|in:1,2',
            'age' => 'required|integer|min:0|max:99',
            'school' => 'required|string|max:80',
            'degree' => 'required|string|max:80',
            'others_degree' => 'nullable|string|max:80',
            'expected_graduation' => 'required|string|max:20',
            'awards_recognition' => 'nullable|string|max:1024',
            'other_examination_certificate' => 'nullable|string|max:1024',
            'thesis_project' => 'nullable|string|max:1024',
            'extra_curricular' => 'nullable|string|max:1024',
            'remarks' => 'nullable|string|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

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
            'created_by' => Auth::id(), // current logged-in user
            'updated_by' => Auth::id(),
            'created_time' => $now,
            'updated_time' => $now,
        ]);

        return redirect()->route('action.applicants.index')
                         ->with('success', 'Applicant created successfully.');
    }
}