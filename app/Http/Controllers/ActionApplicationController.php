<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicationRequest;
use App\Models\ActionApplication;
use App\Models\ActionApplicant;
use App\Models\ActionBatchModel;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActionApplicationController extends Controller
{
    /**
     * Display a listing of the applications.
     */
    public function index()
    {
        $search = request('search', '');

        $applications = ActionApplication::listPageData($search);

        return inertia('action/applications/ActionApplicationList', [
            'applications'       => $applications,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

    public function create()
    {
        return Inertia::render('action/applications/ActionApplicationRegister', [
            'actionApplicants' => ActionApplicant::pluck('email_address', 'id')->toArray(),
            'actionBatches' => ActionBatchModel::pluck('action_batch', 'id')->toArray(), // Assuming ActionBatch model exists
            'examVenues' => config('constants.examVenues'),
            'traineesFrom' => config('constants.traineesFrom'),
        ]);
    }

    public function store(RegisterActionApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            $application = ActionApplication::upsertByKeys($request->validated());

            Log::createLog(
                'ACTION_APPLICATION',
                "Application for applicant ID {$application->action_applicant_id} in batch {$application->action_batch_id} created/updated successfully.",
                $application->id
            );

            DB::commit();

            return redirect()
                ->route('action.applications.detail', ['id' => $application->id])
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('action/applications/ActionApplicationRegister', [
                'actionApplicants' => ActionApplicant::pluck('email_address', 'id')->toArray(),
                'actionBatches' => ActionBatchModel::pluck('name', 'id')->toArray(),
                'examVenues' => config('constants.examVenues'),
                'traineesFrom' => config('constants.traineesFrom'),
                'flash' => [
                    'error' => config('errors.transaction_failed.errorMessage'),
                ],
            ]);
        }
    }

    public function checkUnique(Request $request)
    {
        $applicantId = $request->input('action_applicant_id');
        $batchId = $request->input('action_batch_id');

        $exists = ActionApplication::where('action_applicant_id', $applicantId)
            ->where('action_batch_id', $batchId)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function show($id)
    {
        $application = ActionApplication::with('actionApplicant')->findOrFail($id);

        return Inertia::render('action/applications/ActionApplicationDetail', [
            'application' => $application,
        ]);
    }

}
