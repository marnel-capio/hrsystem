<?php

namespace App\Http\Controllers;


use App\Http\Requests\UpdateActionApplicantRequest;
use App\Models\ActionApplicant;
use App\Services\LogService;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\RegisterActionApplicantRequest;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActionApplicantController extends Controller
{
    public function index()
    {
        $applicants = ActionApplicant::with('programmingLanguages')
                ->orderBy('created_time', 'desc')
                ->get();

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
        $japaneseBackgrounds = Config::get('constants.japanese_backgrounds', []);
        $japaneseLevels = Config::get('constants.japanese_levels', []);

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
            'japaneseBackgrounds' => $japaneseBackgrounds,
            'japaneseLevels' => $japaneseLevels,
        ]);
    }

    public function edit($id)
    {
        $applicant = ActionApplicant::findOrFail($id);

        // Fetch constants for dropdowns
        $sourceTypes = config('constants.sourceTypes', []);
        $sources = config('constants.sources', []);
        $genders = config('constants.genders', []);
        $japaneseBackgrounds = config('constants.japanese_backgrounds', []);
        $japaneseLevels = config('constants.japanese_levels', []);

        return Inertia::render('action/applicants/Edit', [
            'applicant' => $applicant,
            'sourceTypes' => $sourceTypes,
            'sources' => $sources,
            'genders' => $genders,
            'japaneseBackgrounds' => $japaneseBackgrounds,
            'japaneseLevels' => $japaneseLevels,
        ]);
    }

    public function update(UpdateActionApplicantRequest $request, $id)
    {
        $applicant = ActionApplicant::findOrFail($id);

        DB::beginTransaction();

        try {
            $oldData = $applicant->only([
                'source_type', 'source', 'other_source', 'last_name', 'first_name', 'middle_name',
                'email_address', 'gender', 'age', 'school', 'degree', 'others_degree',
                'expected_graduation', 'awards_recognition', 'other_examination_certificate',
                'thesis_project', 'extra_curricular','japanese_background','japanese_level',
                'background_remarks',
            ]);

            // TEMPORARY: force an exception to test the catch block
            // throw new \Exception('');

            $applicant->updateWithRequest($request->validated(), auth()->id());

            $newData = $applicant->fresh()->only(array_keys($oldData));

            app(LogService::class)->createApplicantUpdateLog($oldData, $newData, $applicant->id);

            DB::commit();

            return redirect()
                ->route('action.applicants.detail', ['id' => $applicant->id])
                ->with('success', config('errors.record_updated_successfully.errorMessage'));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Inertia::back()->with('error', config('errors.record_updated_failed.errorMessage'));
        }
    }
    
    public function create()
    {
        return Inertia::render('action/applicants/Register', [
            'sourceTypes' => config('constants.sourceTypes'),
            'sources' => config('constants.sources'),
            'genders' => config('constants.genders'),
            'japaneseBackgrounds' => config('constants.japanese_backgrounds'),
            'japaneseLevels' => config('constants.japanese_levels'),
        ]);
    }

    public function store(RegisterActionApplicantRequest $request)
    {
        DB::beginTransaction();

        try {
            $applicant = ActionApplicant::upsertByEmail($request->validated());

            Log::createLog(
                'ACTION',
                "Applicant with {$applicant->email_address} email address registered/updated successfully.",
                $applicant->id
            );

            DB::commit();

            return redirect()
                ->route('action.applicants.detail', ['id' => $applicant->id])
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('action/applicants/Register', [
                'sourceTypes' => config('constants.sourceTypes'),
                'sources' => config('constants.sources'),
                'genders' => config('constants.genders'),
                'japaneseBackgrounds' => config('constants.japanese_backgrounds'),
                'japaneseLevels' => config('constants.japanese_levels'),
                'flash' => [
                    'error' => config('errors.transaction_failed.errorMessage'),
                ],
            ]);
        }
    }


    public function checkEmail(Request $request)
    {
        $email = $request->input('email_address');

        $exists = ActionApplicant::where('email_address', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

}
