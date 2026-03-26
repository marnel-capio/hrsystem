<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionApplicantProgrammingLanguageRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplicantProgrammingLanguage;
use App\Models\Log;
use App\Services\LogService;
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

                // TEMPORARY: force an exception to test the catch block
                // throw new \Exception('');

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

    public function update(ActionApplicantProgrammingLanguageRequest $request, $applicantId, $langId): JsonResponse
    {
        try {
            $lang = DB::transaction(function () use ($langId, $request, $applicantId) {

                // TEMPORARY: force an exception to test the catch block
                // throw new \Exception('');

                // Find the existing language first (old snapshot)
                $langModel = ActionApplicantProgrammingLanguage::findOrFail($langId);
                $oldData = $langModel->toArray();

                // Update the language
                $lang = ActionApplicantProgrammingLanguage::updateLanguage($langId, $request->validated());
                $newData = $lang->fresh()->toArray();

                // Logging using centralized service
                app(LogService::class)
                    ->createProgrammingLanguageUpdateLog($oldData, $newData, $applicantId);

                return $lang;
            });

            return response()->json([
                'data' => $lang,
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

    public function destroy($applicantId, $langId): JsonResponse
    {
        try {
            DB::transaction(function () use ($langId, $applicantId) {

                // TEMPORARY: force an exception to test the catch block
                // throw new \Exception('');

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

                // TEMPORARY: force an exception to test the catch block
                // throw new \Exception('');
                
                // Get all languages being deleted
                $langs = ActionApplicantProgrammingLanguage::whereIn('id', $request->ids)->get();

                // Perform the bulk delete
                ActionApplicantProgrammingLanguage::bulkDeleteLanguages($applicantId, $request->ids);

                // Logging: collect all program_languages and log once
                $applicant = ActionApplicant::find($applicantId);
                $languageNames = $langs->pluck('program_language')->implode(', ');

                Log::createLog(
                    'ACTION',
                    "Deleted {$languageNames} programming language(s) of {$applicant->email_address}.",
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
