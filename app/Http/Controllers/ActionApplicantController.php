<?php

namespace App\Http\Controllers;

use App\Models\ActionApplicant;
use Inertia\Inertia;
use Illuminate\Support\Facades\Config;
use App\Models\ActionApplicantProgrammingLanguage;


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
        // Fetch applicant with the updatedBy relation
        $applicant = ActionApplicant::with(['updatedBy'])->findOrFail($id);

        // Fetch sources and source types from config/constants.php
        $sources = Config::get('constants.sources', []);
        $sourceTypes = Config::get('constants.source_types', []);

        // Add a human-readable source label
        $applicant->source_label = $sources[$applicant->source] ?? null;

        // Add human-readable source type label
        $applicant->source_type_label = $sourceTypes[$applicant->source_type] ?? null;

        // Add Updated By full name
        $applicant->updated_by_name = $applicant->updatedBy
            ? trim("{$applicant->updatedBy->first_name} {$applicant->updatedBy->middle_name} {$applicant->updatedBy->last_name}")
            : null;

        // Fetch programming languages for this applicant
        $languages = ActionApplicantProgrammingLanguage::where('action_applicant_id', $id)->get();

        return Inertia::render('action/applicants/Detail', [
            'applicant' => $applicant,
            'languages' => $languages, // pass languages along with applicant
        ]);
    }

}