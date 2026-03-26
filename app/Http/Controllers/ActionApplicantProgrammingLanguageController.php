<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionApplicantProgrammingLanguageRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplicantProgrammingLanguage;
use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActionApplicantProgrammingLanguageController extends Controller
{
    public function index($applicantId)
    {
        return response()->json(ActionApplicantProgrammingLanguage::forApplicant($applicantId));
    }

    public function store(ActionApplicantProgrammingLanguageRequest $request, $applicantId): JsonResponse
    {
        try {
            $lang = DB::transaction(function () use ($applicantId, $request) {
                $lang = ActionApplicantProgrammingLanguage::addLanguage($applicantId, $request->validated());

                // Logging
                $applicant = ActionApplicant::find($applicantId);
                Log::createLog(
                    'ACTION',
                    "Added {$lang->program_language} programming language to {$applicant->email_address}.",
                    $applicantId
                );

                return $lang;
            });

            return response()->json([
                'data' => $lang,
                'responseMessage' => [
                    'errorCode' => 'RECORD_CREATED_SUCCESSFULLY',
                    'errorMessage' => 'Record created successfully.',
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'TRANSACTION_FAILED',
                    'errorMessage' => 'An error occurred while creating the record. Please try again.',
                ],
            ], 500);
        }
    }

    public function update(ActionApplicantProgrammingLanguageRequest $request, $applicantId, $langId): JsonResponse
    {
        try {
            $lang = DB::transaction(function () use ($langId, $request, $applicantId) {
                $lang = ActionApplicantProgrammingLanguage::updateLanguage($langId, $request->validated());

                // Logging
                $applicant = ActionApplicant::find($applicantId);
                Log::createLog(
                    'ACTION',
                    "Updated {$lang->program_language} programming language to {$applicant->email_address}.",
                    $applicantId
                );

                return $lang;
            });

            return response()->json([
                'data' => $lang,
                'responseMessage' => [
                    'errorCode' => 'RECORD_UPDATED_SUCCESSFULLY',
                    'errorMessage' => 'Record updated successfully.',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'UPDATE_FAILED',
                    'errorMessage' => 'An error occurred while saving the record. Please try again.',
                ],
            ], 500);
        }
    }

    public function destroy($applicantId, $langId): JsonResponse
    {
        try {
            DB::transaction(function () use ($langId, $applicantId) {
                $lang = ActionApplicantProgrammingLanguage::find($langId);
                ActionApplicantProgrammingLanguage::deleteLanguage($langId);

                // Logging
                $applicant = ActionApplicant::find($applicantId);
                Log::createLog(
                    'ACTION',
                    "Deleted {$lang->program_language} programming language of {$applicant->email_address}.",
                    $applicantId
                );
            });

            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_SUCCESSFULLY',
                    'errorMessage' => 'Record successfully deleted.',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_FAILED',
                    'errorMessage' => 'An error occurred while deleting the record. Please try again.',
                ],
            ], 500);
        }
    }

    public function bulkDelete(Request $request, $applicantId): JsonResponse
    {
        try {
            DB::transaction(function () use ($applicantId, $request) {
                $langs = ActionApplicantProgrammingLanguage::whereIn('id', $request->ids)->get();

                ActionApplicantProgrammingLanguage::bulkDeleteLanguages($applicantId, $request->ids);

                // Logging for each deleted language
                $applicant = ActionApplicant::find($applicantId);
                foreach ($langs as $lang) {
                    Log::createLog(
                        'ACTION',
                        "Deleted {$lang->program_language} programming language of {$applicant->email_address}.",
                        $applicantId
                    );
                }
            });

            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_SUCCESSFULLY',
                    'errorMessage' => 'Record successfully deleted.',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'responseMessage' => [
                    'errorCode' => 'RECORD_DELETED_FAILED',
                    'errorMessage' => 'An error occurred while deleting the record. Please try again.',
                ],
            ], 500);
        }
    }
}
