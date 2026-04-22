<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntermediateApplicantSkillRequest;
use App\Models\IntermediateApplicantSkill;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntermediateApplicantSkillController extends Controller
{
    public function index($applicantId)
    {
        return response()->json(
            IntermediateApplicantSkill::forApplicant($applicantId)
        );
    }

public function store(IntermediateApplicantSkillRequest $request, $applicantId, LogService $logService)
{
    return DB::transaction(function () use ($request, $applicantId, $logService) {
        $validated = $request->validated();

        $skill = IntermediateApplicantSkill::addSkill($applicantId, $validated);

        $logService->createIntermediateSkillCreateLog($validated, $applicantId);

        return response()->json($skill);
    });
}

public function update(IntermediateApplicantSkillRequest $request, $applicantId, $skillId, LogService $logService)
{
    return DB::transaction(function () use ($request, $applicantId, $skillId, $logService) {
        $validated = $request->validated();

        $skill = IntermediateApplicantSkill::findOrFail($skillId);
        $oldData = $skill->only(['skill', 'remarks']);

        $updated = IntermediateApplicantSkill::updateSkill($skillId, $validated);

        $newData = $updated->only(['skill', 'remarks']);

        $logService->createIntermediateSkillUpdateLog($oldData, $newData, $applicantId);

        return response()->json($updated);
    });
}

public function destroy($applicantId, $skillId, LogService $logService)
{
    return DB::transaction(function () use ($applicantId, $skillId, $logService) {
        $skill = IntermediateApplicantSkill::findOrFail($skillId);
        $oldData = $skill->only(['skill', 'remarks']);

        IntermediateApplicantSkill::deleteSkill($skillId);

        $logService->createIntermediateSkillDeleteLog($oldData, $applicantId);

        return response()->json(['success' => true]);
    });
}

public function bulkDelete(Request $request, $applicantId, LogService $logService)
{
    return DB::transaction(function () use ($request, $applicantId, $logService) {
        $ids = $request->input('ids', []);

        $skills = IntermediateApplicantSkill::where('intermediate_applicant_id', $applicantId)
            ->whereIn('id', $ids)
            ->get(['skill', 'remarks'])
            ->toArray();

        IntermediateApplicantSkill::bulkDeleteSkills($applicantId, $ids);

        $logService->createIntermediateSkillBulkDeleteLog($skills, $applicantId);

        return response()->json(['success' => true]);
    });
}


}
