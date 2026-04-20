<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterIntermediateApplicantRequest;
use App\Models\IntermediateApplicant;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
        ]);
    }

    public function store(RegisterIntermediateApplicantRequest $request)
    {
        DB::beginTransaction();

        try {
            $applicant = IntermediateApplicant::upsertByEmail($request->validated());

            Log::createLog(
                'INTERMEDIATE',
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
        $applicant = IntermediateApplicant::with('skills')->findOrFail($id);

        return Inertia::render('intermediate/applicants/IntermediateApplicantDetails', [
            'applicant' => $applicant,
        ]);
    }
}
