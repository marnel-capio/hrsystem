<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicantRequest;
use App\Models\ActionApplicant;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActionApplicantController extends Controller
{
    public function create()
    {
        return Inertia::render('action/applicants/Register', [
            'sourceTypes' => config('constants.sourceTypes'),
            'sources' => config('constants.sources'),
            'genders' => config('constants.genders'),
        ]);
    }

    public function index()
    {
        return Inertia::render('action/applicants/Index');
    }

    public function store(RegisterActionApplicantRequest $request)
    {
        DB::beginTransaction();

        try {
            // TEMPORARY: force an exception to test the catch block
            // throw new \Exception('');
            $email = $request->input('email_address');

            $applicant = ActionApplicant::where('email_address', $email)->first();

            if ($applicant) {
                // Update existing
                $applicant->update($request->validated());
            } else {
                // Create new
                $applicant = ActionApplicant::createApplicant($request->validated());
            }

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

    public function show($id)
    {
        $applicant = ActionApplicant::findOrFail($id);

        return Inertia::render('action/applicants/Detail', [
            'applicant' => $applicant,
        ]);
    }
}
