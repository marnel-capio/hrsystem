<?php

namespace App\Http\Controllers;

use App\Models\ActionApplicantProgrammingLanguage;
use Illuminate\Http\Request;

class ActionApplicantProgrammingLanguageController extends Controller
{
    // List all languages for an applicant, including remarks
    public function index($applicantId)
    {
        return ActionApplicantProgrammingLanguage::where('action_applicant_id', $applicantId)
            ->select('id', 'program_language', 'remarks') // explicitly include remarks
            ->get();
    }

    // Add a new language with optional remarks
    public function store(Request $request, $applicantId)
    {
        $request->validate([
            'program_language' => 'required|string|max:80',
            'remarks' => 'nullable|string|max:255', // allow remarks
        ]);

        $lang = ActionApplicantProgrammingLanguage::create([
            'action_applicant_id' => $applicantId,
            'program_language' => $request->program_language,
            'remarks' => $request->remarks ?? null, // save remarks
            'created_by' => auth()->id() ?? 1,
            'created_time' => now(),
            'updated_by' => auth()->id() ?? 1,
            'updated_time' => now(),
        ]);

        return response()->json($lang);
    }

    // Update an existing language with remarks
    public function update(Request $request, $applicantId, $langId)
    {
        $request->validate([
            'program_language' => 'required|string|max:80',
            'remarks' => 'nullable|string|max:255',
        ]);

        $lang = ActionApplicantProgrammingLanguage::findOrFail($langId);
        $lang->program_language = $request->program_language;
        $lang->remarks = $request->remarks ?? null;
        $lang->updated_by = auth()->id() ?? 1;
        $lang->updated_time = now();
        $lang->save();

        return response()->json($lang);
    }

    // Delete a single language
    public function destroy($applicantId, $langId)
    {
        $lang = ActionApplicantProgrammingLanguage::findOrFail($langId);
        $lang->delete(); // hard delete

        return response()->json(['success' => true]);
    }

    // Bulk delete selected languages
    public function bulkDelete(Request $request, $applicantId)
    {
        ActionApplicantProgrammingLanguage::where('action_applicant_id', $applicantId)
            ->whereIn('id', $request->ids)
            ->delete(); // hard delete

        return response()->json(['success' => true]);
    }
}