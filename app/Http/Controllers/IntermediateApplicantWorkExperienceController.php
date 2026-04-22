<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntermediateApplicantWorkExperienceRequest;
use App\Models\IntermediateApplicantWorkExperience;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntermediateApplicantWorkExperienceController extends Controller
{
    public function index($applicantId)
    {
        return response()->json(
            IntermediateApplicantWorkExperience::forApplicant($applicantId)
        );
    }

    public function store(
        IntermediateApplicantWorkExperienceRequest $request,
        $applicantId,
        LogService $logService
    ) {
        return DB::transaction(function () use ($request, $applicantId, $logService) {
            $validated = $request->validated();

            $work = IntermediateApplicantWorkExperience::addWorkExperience($applicantId, $validated);

            $logService->createIntermediateWorkExperienceCreateLog($validated, $applicantId);

            return response()->json($work);
        });
    }

    public function update(
        IntermediateApplicantWorkExperienceRequest $request,
        $applicantId,
        $workId,
        LogService $logService
    ) {
        return DB::transaction(function () use ($request, $applicantId, $workId, $logService) {
            $validated = $request->validated();

            $work = IntermediateApplicantWorkExperience::findOrFail($workId);
            $oldData = $work->only([
                'employer',
                'company_address',
                'job_title',
                'date_employed',
                'work_description',
                'salary',
                'reason_for_leaving',
                'name_supervisor',
                'remarks',
            ]);

            $updated = IntermediateApplicantWorkExperience::updateWorkExperience($workId, $validated);

            $newData = $updated->only([
                'employer',
                'company_address',
                'job_title',
                'date_employed',
                'work_description',
                'salary',
                'reason_for_leaving',
                'name_supervisor',
                'remarks',
            ]);

            $logService->createIntermediateWorkExperienceUpdateLog($oldData, $newData, $applicantId);

            return response()->json($updated);
        });
    }

    public function destroy($applicantId, $workId, LogService $logService)
    {
        return DB::transaction(function () use ($applicantId, $workId, $logService) {
            $work = IntermediateApplicantWorkExperience::findOrFail($workId);

            $oldData = $work->only([
                'employer',
                'company_address',
                'job_title',
                'date_employed',
                'work_description',
                'salary',
                'reason_for_leaving',
                'name_supervisor',
                'remarks',
            ]);

            IntermediateApplicantWorkExperience::deleteWorkExperience($workId);

            $logService->createIntermediateWorkExperienceDeleteLog($oldData, $applicantId);

            return response()->json(['success' => true]);
        });
    }

    public function bulkDelete(Request $request, $applicantId, LogService $logService)
    {
        return DB::transaction(function () use ($request, $applicantId, $logService) {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No work experiences selected.',
                ], 422);
            }

            $works = IntermediateApplicantWorkExperience::where('intermediate_applicant_id', $applicantId)
                ->whereIn('id', $ids)
                ->get([
                    'employer',
                    'company_address',
                    'job_title',
                    'date_employed',
                    'work_description',
                    'salary',
                    'reason_for_leaving',
                    'name_supervisor',
                    'remarks',
                ])
                ->toArray();

            if (empty($works)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching work experiences found.',
                ], 404);
            }

            IntermediateApplicantWorkExperience::bulkDeleteWorkExperiences($applicantId, $ids);

            $logService->createIntermediateWorkExperienceBulkDeleteLog($works, $applicantId);

            return response()->json([
                'success' => true,
                'message' => 'Work experiences deleted successfully.',
            ]);
        });
    }
}
