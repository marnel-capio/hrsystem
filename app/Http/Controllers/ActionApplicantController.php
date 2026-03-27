<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateActionApplicantRequest;
use App\Models\ActionApplicant;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class ActionApplicantController extends Controller
{
    public function index()
    {
        $applicants = ActionApplicant::getAllActionApplicants();

        return Inertia::render('action/applicants/Index', [
            'applicants' => $applicants,
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

    public function show($id)
    {
        // Fetch applicant with relations: updatedBy, application, programmingLanguages
        $applicant = ActionApplicant::with(['updatedBy', 'applications', 'programmingLanguages'])
            ->findOrFail($id);

        // Fetch sources and source types from config/constants.php
        $sources = Config::get('constants.sources', []);
        $sourceTypes = Config::get('constants.source_types', []);

        // Add human-readable source labels
        $applicant->source_label = $sources[$applicant->source] ?? null;
        $applicant->source_type_label = $sourceTypes[$applicant->source_type] ?? null;

        // Add Updated By full name
        $applicant->updated_by_name = $applicant->updatedBy
            ? trim("{$applicant->updatedBy->first_name} {$applicant->updatedBy->middle_name} {$applicant->updatedBy->last_name}")
            : null;

        return Inertia::render('action/applicants/Detail', [
            'applicant' => $applicant,
            'user_permissions' => auth()->user()?->permissions ?? 0,
        ]);
    }

    public function edit($id)
    {
        $applicant = ActionApplicant::findOrFail($id);

        // Fetch constants for dropdowns
        $sourceTypes = config('constants.sourceTypes', []);
        $sources = config('constants.sources', []);
        $genders = config('constants.genders', []);

        return Inertia::render('action/applicants/Edit', [
            'applicant' => $applicant,
            'sourceTypes' => $sourceTypes,
            'sources' => $sources,
            'genders' => $genders,
        ]);
    }

    public function update(UpdateActionApplicantRequest $request, $id)
    {
        $applicant = ActionApplicant::findOrFail($id);

        $applicant->updateWithRequest($request->validated(), auth()->id());

        return redirect()
            ->route('action.applicants.detail', $applicant->id)
            ->with('success', 'ACTION Applicant updated successfully.');
    }
}
