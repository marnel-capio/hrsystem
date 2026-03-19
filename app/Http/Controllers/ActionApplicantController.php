<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicantRequest;
use App\Models\ActionApplicant;
use App\Services\Log;
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
            $applicant = ActionApplicant::createApplicant($request->validated());

            // TEMPORARY: force an exception to test the catch block
            // throw new \Exception('');

            // Optional: log action (same as your UserController)
            Log::createLog(
                'ACTION',
                "Applicant with {$applicant->email_address} email address is registered successfully.",
                $applicant->id
            );

            DB::commit();

            return redirect()
                ->route('action.applicants.index')
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('action.applicants.index')
                ->with('error', config('errors.transaction_failed.errorMessage'));
        }
    }
}
