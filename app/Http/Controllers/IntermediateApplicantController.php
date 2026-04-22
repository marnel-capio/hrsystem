<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterIntermediateApplicantRequest;
use App\Models\IntermediateApplicant;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Http\Requests\UpdateIntermediateApplicantRequest;
use App\Services\LogService;
use Illuminate\Support\Facades\Config;

class IntermediateApplicantController extends Controller
{
    public function index()
    {
        $applicants = IntermediateApplicant::getAllIntermediateApplicants();

        return Inertia::render('intermediate/applicants/IntermediateApplicantList', [
            'applicants' => $applicants,
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

    public function create()
    {
        return Inertia::render('intermediate/applicants/IntermediateApplicantRegister', [
            'sourceTypes' => config('constants.intermediateSourceTypes'),
            'sources' => config('constants.intermediateSources'),
            'genders' => config('constants.genders'),
            'japaneseBackgrounds' => config('constants.japanese_backgrounds'),
            'japaneseLevels' => config('constants.japanese_levels'),
        ]);
    }

    public function store(RegisterIntermediateApplicantRequest $request)
    {
        DB::beginTransaction();

        try {
            $applicant = IntermediateApplicant::upsertByEmail($request->validated());

            Log::createLog(
                'Intermediate',
                "Applicant with {$applicant->email_address} email address registered/updated successfully.",
                $applicant->id
            );

            DB::commit();

            return redirect()
                ->route('intermediate.applicants.show', ['id' => $applicant->id])
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('intermediate/applicants/IntermediateApplicantRegister', [
                'sourceTypes' => config('constants.intermediateSourceTypes'),
                'sources' => config('constants.intermediateSources'),
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

        $exists = IntermediateApplicant::where('email_address', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function show($id)
{
    $applicant = IntermediateApplicant::with(['skills', 'applications'])->findOrFail($id);

    return Inertia::render('intermediate/applicants/IntermediateApplicantDetails', [
        'applicant' => $applicant,
        'user_permissions' => auth()->user()?->permissions ?? 0,
        'japaneseBackgrounds' => config('constants.japanese_backgrounds'),
        'japaneseLevels' => config('constants.japanese_levels'),
    ]);
}

public function edit($id)
{
    $applicant = IntermediateApplicant::findOrFail($id);

    return Inertia::render('intermediate/applicants/IntermediateApplicantEdit', [
        'applicant' => $applicant,
        'sourceTypes' => config('constants.intermediateSourceTypes'),
        'sources' => config('constants.intermediateSources'),
        'genders' => config('constants.genders'),
        'japaneseBackgrounds' => config('constants.japanese_backgrounds'),
        'japaneseLevels' => config('constants.japanese_levels'),
    ]);
}

public function update(UpdateIntermediateApplicantRequest $request, $id)
{
    $applicant = IntermediateApplicant::findOrFail($id);

    DB::beginTransaction();

    try {
        $oldData = $applicant->only([
            'source_type',
            'source',
            'other_source',
            'last_name',
            'first_name',
            'middle_name',
            'gender',
            'birthdate',
            'age',
            'address',
            'email_address',
            'contact_no',
            'school_graduated_from',
            'course',
            'year_attended',
            'others',
            'japanese_background',
            'japanese_level',
            'background_remarks',
            'spouse_details',
            'children',
            'father_details',
            'mother_details',
            'sibling_details',
            'emergency_contact_name',
            'emergency_contact_number',
            'emergency_contact_address',
            'remarks',
        ]);

        $applicant->updateApplicant($request->validated());

        $newData = $applicant->fresh()->only(array_keys($oldData));

        DB::commit();

        return redirect()
            ->route('intermediate.applicants.show', ['id' => $applicant->id])
            ->with('success', config('errors.record_updated_successfully.errorMessage'));
    } catch (\Throwable $e) {
        DB::rollBack();

        return Inertia::back()->with('error', config('errors.record_updated_failed.errorMessage'));
    }
}

}
