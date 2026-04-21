<?php

namespace App\Http\Controllers;

use App\Models\IntermediateApplicantSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\IntermediateApplicantSkillRequest;

class IntermediateApplicantSkillController extends Controller
{
    public function index($applicantId)
    {
        return response()->json(
            IntermediateApplicantSkill::forApplicant($applicantId)
        );
    }

    public function store(IntermediateApplicantSkillRequest $request, $applicantId)
    {
        return DB::transaction(function () use ($request, $applicantId) {
            return IntermediateApplicantSkill::addSkill($applicantId, $request->validated());
        });
    }

    public function update(IntermediateApplicantSkillRequest $request, $applicantId, $skillId)
    {
        return DB::transaction(function () use ($request, $skillId) {
            return IntermediateApplicantSkill::updateSkill($skillId, $request->validated());
        });
    }

    public function destroy($applicantId, $skillId)
    {
        return DB::transaction(function () use ($skillId) {
            return IntermediateApplicantSkill::deleteSkill($skillId);
        });
    }

    public function bulkDelete(Request $request, $applicantId)
    {
        return DB::transaction(function () use ($request, $applicantId) {
            return IntermediateApplicantSkill::bulkDeleteSkills($applicantId, $request->ids);
        });
    }
}
