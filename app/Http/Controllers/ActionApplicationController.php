<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicationRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionBatchModel;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActionApplicationController extends Controller
{

    /**
     * Show the form for creating a new application.
     */
    public function create()
    {
        return Inertia::render('action/applications/ActionApplicationRegister', [
            'actionBatches' => ActionBatchModel::pluck('action_batch', 'id')->toArray(),
            'examVenues' => config('constants.examVenues'),
            'examResults' => config('constants.examResults'),
            'examStatuses' => config('constants.examApplicationStatuses'),
            'interviewResults' => config('constants.interviewResults'),
            'interviewAppStatuses' => config('constants.applicationStatuses'),
            'jobOfferStatuses' => config('constants.jobOfferStatuses'),
        ]);
    }

    /**
     * Store newly created application
     */
    public function store(RegisterActionApplicationRequest $request)
    {
        DB::beginTransaction();

        try {

        //Test error
        throw new \Exception('');

            // Prepare data for insertion
            $data = $request->validated();

            // Handle file uploads
            if ($request->hasFile('upload_resume')) {
                $data['upload_resume'] = $this->uploadFile($request->file('upload_resume'), 'resumes');
            }
            if ($request->hasFile('upload_tor')) {
                $data['upload_tor'] = $this->uploadFile($request->file('upload_tor'), 'tors');
            }
            if ($request->hasFile('upload_pic')) {
                $data['upload_pic'] = $this->uploadFile($request->file('upload_pic'), 'pictures');
            }

            $application = ActionApplication::createApplication($data);
            $user = Auth::user();
            // Create log entry
            Log::createLog(
                'ACTION',
                "Application for {$application->applicant->email_address} registered successfully.",
                $user->id
            );

            DB::commit();

            return redirect()
                ->route('action.applications.show', $application->id)
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => config('errors.transaction_failed.errorMessage')]);
        }
    }

    /**
     * Display the specified application.
     */
    public function show($id)
    {
        $application = ActionApplication::with('applicant', 'batch')->findOrFail($id);

        return Inertia::render('action/applications/ActionApplicationDetail', [
            'application' => $application,
        ]);
    }

    /**
     * Get eligible applicants for a specific batch
     */

    public function getApplicantsForBatch($batchId)
    {
        try {
            \Log::info('Getting eligible applicants for batch: ' . $batchId);

            $eligibleApplicants = ActionApplicant::getEligibleApplicantsForBatch($batchId);

            \Log::info('Found ' . count($eligibleApplicants) . ' eligible applicants');

            return response()->json($eligibleApplicants);
        } catch (\Exception $e) {
            \Log::error('Error getting eligible applicants: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Handle file upload
     */
    private function uploadFile($file, $directory)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("uploads/{$directory}", $filename, 'public');
        return $path;
    }
}
