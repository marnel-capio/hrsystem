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
     * Display a listing of the applications.
     */
    public function index()
    {
        $search = request('search', '');

        $applications = ActionApplication::listPageData($search);

        return inertia('action/applications/ActionApplicationList', [
            'applications' => $applications,
            'filters' => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

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
            // Check eligibility before storing
            $isEligible = $this->checkApplicantEligibility(
                $request->action_applicant_id,
                $request->action_batch_id
            );

            if (!$isEligible) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['eligibility' => config('errors.ineligible_applicant.errorMessage')]);
            }

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

            // Create log entry
            Log::create([
                'module' => 'ACTION',
                'activity' => "Application for {$application->applicant->email_address} registered successfully.",
                'ip_address' => request()->ip(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_time' => now(),
                'updated_time' => now(),
            ]);

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
    public function getApplicantsForBatch(Request $request)
    {
        $batchId = $request->input('action_batch_id');

        $eligibleApplicants = ActionApplicant::getEligibleApplicantsForBatch($batchId);

        return response()->json($eligibleApplicants);
    }

    /**
     * Check applicant eligibility (if not in action batch chosen)
     */
    public function checkEligibility(Request $request)
    {
        $applicantId = $request->input('action_applicant_id');
        $batchId = $request->input('action_batch_id');

        $isEligible = $this->checkApplicantEligibility($applicantId, $batchId);

        return response()->json(['eligible' => $isEligible]);
    }

    /**
     * Check applicant eligibility (6 month condition)
     */
    private function checkApplicantEligibility($applicantId, $batchId)
    {
        $latestApplication = ActionApplication::where('action_applicant_id', $applicantId)
            ->orderBy('created_time', 'desc')
            ->first();

        if (!$latestApplication) {
            return true; // No previous application
        }

        $daysSinceLastApplication = now()->diffInDays($latestApplication->created_time);

        if ($daysSinceLastApplication > 180) {
            return true; // Older than 6 months
        }

        // Check for failed status within 6 months
        $failedStatuses = [
            'exam_application_status' => [6, 7], // Failed, No Show
            'initial_interview_result' => [3], // Failed
            'final_interview_result' => [3], // Failed
            'job_offer_status' => [4, 5, 6] // Decline, Withdraw, Retracted
        ];

        foreach ($failedStatuses as $field => $failedValues) {
            if (in_array($latestApplication->$field, $failedValues)) {
                return false;
            }
        }

        return true;
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
