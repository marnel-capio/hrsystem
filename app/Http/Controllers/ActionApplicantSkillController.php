<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionApplicantSkillRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplicantSkill;
use App\Models\Log;
use App\Services\LogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActionApplicantSkillController extends Controller
{
    public function index($applicantId)
    {
        return response()->json(ActionApplicantSkill::forApplicant($applicantId));
    }

    public function store(ActionApplicantSkillRequest $request, $applicantId): JsonResponse
    {
        try {
            $skill = DB::transaction(function () use ($applicantId, $request) {

                $skill = ActionApplicantSkill::addSkill($applicantId, $request->validated());

                $applicant = ActionApplicant::find($applicantId);

                Log::createLog(
                    'ACTION',
                    "Added {$skill->skill} skill to {$applicant->email_address}.",
                    $applicantId
                );

                return $skill;
            });

            return response()->json([
                'data' => $skill,
                'responseMessage' => [
                    'errorCode' => 'RECORD_CREATED_SUCCESSFULLY',
                    'errorMessage' => config('errors.record_created_successfully.errorMessage'),
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'TRANSACTION_FAILED',
                    'errorMessage' => config('errors.transaction_failed.errorMessage'),
                ],
            ], 500);
        }
    }

    public function update(ActionApplicantSkillRequest $request, $applicantId, $skillId): JsonResponse
    {
        try {
            $skill = DB::transaction(function () use ($skillId, $request, $applicantId) {

                $skillModel = ActionApplicantSkill::findOrFail($skillId);
                $oldData = $skillModel->toArray();

                $skill = ActionApplicantSkill::updateSkill($skillId, $request->validated());
                $newData = $skill->fresh()->toArray();

                app(LogService::class)
                    ->createSkillUpdateLog($oldData, $newData, $applicantId);

                return $skill;
            });

            return response()->json([
                'data' => $skill,
                'responseMessage' => [
                    'errorCode' => 'RECORD_UPDATED_SUCCESSFULLY',
                    'errorMessage' => config('errors.record_updated_successfully.errorMessage'),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'UPDATE_FAILED',
                    'errorMessage' => config('errors.update_failed.errorMessage'),
                ],
            ], 500);
        }
    }

    public function destroy($applicantId, $skillId): JsonResponse
    {
        try {
            DB::transaction(function () use ($skillId, $applicantId) {

                $skill = ActionApplicantSkill::find($skillId);
                ActionApplicantSkill::deleteSkill($skillId);

                $applicant = ActionApplicant::find($applicantId);

                Log::createLog(
                    'ACTION',
                    "Deleted {$skill->skill} skill of {$applicant->email_address}.",
                    $applicantId
                );
            });

            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_SUCCESSFULLY',
                    'errorMessage' => config('errors.record_deleted_successfully.errorMessage'),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_FAILED',
                    'errorMessage' => config('errors.record_deleted_failed.errorMessage'),
                ],
            ], 500);
        }
    }

    public function bulkDelete(Request $request, $applicantId): JsonResponse
    {
        try {
            DB::transaction(function () use ($applicantId, $request) {

                $skills = ActionApplicantSkill::whereIn('id', $request->ids)->get();

                ActionApplicantSkill::bulkDeleteSkills($applicantId, $request->ids);

                $applicant = ActionApplicant::find($applicantId);

                $skillNames = $skills->pluck('skill')->implode(', ');

                Log::createLog(
                    'ACTION',
                    "Deleted {$skillNames} skill(s) of {$applicant->email_address}.",
                    $applicantId
                );
            });

            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_SUCCESSFULLY',
                    'errorMessage' => config('errors.record_deleted_successfully.errorMessage'),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_FAILED',
                    'errorMessage' => config('errors.record_deleted_failed.errorMessage'),
                ],
            ], 500);
        }
    }
}