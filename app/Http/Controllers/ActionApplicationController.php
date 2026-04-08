<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicationRequest;
use App\Http\Requests\UpdateActionApplicationRequest;
use App\Http\Requests\SendActionApplicationNotificationRequest;
use App\Http\Requests\BulkUpdateActionInterviewScheduleRequest;
use App\Http\Requests\BulkAddActionInterviewsRequest;
use App\Http\Requests\SubmitActionInterviewDecisionRequest;
use App\Mail\ActionJobOfferScheduledMail;
use App\Mail\ActionApplicantFailedMail;
use App\Mail\ActionApplicantScheduledMail;
use App\Mail\ActionInterviewerPendingApprovalMail;
use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionApplicationInterview;
use App\Models\ActionBatchModel;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        $actionBatches = ActionBatchModel::getWithTargetLocationAndApplications();

        return inertia('action/applications/ActionApplicationList', [
            'applications'    => $applications,
            'actionBatches'   => $actionBatches,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

    /**
     * Show the form for creating a new application.
     */
    public function create()
    {
        return Inertia::render('action/applications/ActionApplicationRegister', array_merge(
            [
                'actionBatches' => ActionBatchModel::pluck('action_batch', 'id')->toArray(),
            ],
            $this->applicationFormOptions()
        ));
    }

    /**
     * Store newly created application.
     */
    public function store(RegisterActionApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data = ActionApplication::normalizeComputedFields($data);
            $data = $this->handleUploads($request, $data);

            $application = ActionApplication::createApplication($data);
            $user = Auth::user();

            Log::createLog(
                'ACTION',
                "Application for {$application->applicant->email_address} registered successfully.",
                $user->id
            );

            DB::commit();

            return redirect()
                ->route('action.applications.show', $application->id)
                ->with('success', config('errors.record_created_successfully.errorMessage'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
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
        $application = ActionApplication::with([
            'applicant',
            'batch',
            'interviews.interviewer',
        ])->findOrFail($id);

        $interviews = $application->interviews
            ->map(fn ($interview) => $interview->toDisplayArray())
            ->values();

        $availableInterviewers = User::query()
            ->select([
                'id',
                'first_name',
                'last_name',
                'middle_name',
                'position',
                'permissions',
                'email_address',
            ])
            ->actionInterviewers()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(fn ($user) => $user->toInterviewerOption())
            ->values();

        return Inertia::render('action/applications/ActionApplicationDetail', array_merge(
            [
                'application' => $application,
                'interviews' => $interviews,
                'availableInterviewers' => $availableInterviewers,
                'user_permissions' => auth()->user()->permissions,
                'user_id' => auth()->id(),
            ],
            $this->applicationFormOptions()
        ));
    }

    /**
     * Show the form for editing the specified application.
     */
    public function edit($id)
    {
        $application = ActionApplication::with([
            'applicant',
            'batch',
            'interviews',
        ])->findOrFail($id);

        $user = auth()->user();
        $permission = (int) $user->permissions;
        $editableStages = $application->getEditableStagesFor($user);

        if (!in_array(true, $editableStages, true)) {
            abort(403, 'You are not allowed to edit this application.');
        }

        return Inertia::render('action/applications/ActionApplicationEdit', array_merge(
            [
                'application' => $application,
                'editableStages' => $editableStages,
                'user_permissions' => $permission,
                'user_id' => auth()->id(),
            ],
            $this->applicationFormOptions()
        ));
    }

    /**
     * Update the specified application.
     */
    public function update(UpdateActionApplicationRequest $request, $id)
    {
        $application = ActionApplication::with(['interviews', 'applicant'])->findOrFail($id);
        $originalData = $application->toArray();

        $validated = $request->validated();
        $validated = $this->handleUploads($request, $validated, true);

        $permission = (int) auth()->user()->permissions;
        $editableStages = $application->getEditableStagesFor(auth()->user());

        if (!in_array(true, $editableStages, true)) {
            abort(403, 'You are not allowed to update this application.');
        }

        if (!in_array($permission, config('constants.full_edit_permissions', []), true)) {
            $validated = $this->filterValidatedFieldsByEditableStages($validated, $editableStages);
        }

        $validated = ActionApplication::normalizeComputedFields(
            array_merge($application->toArray(), $validated)
        );

        $application->updateApplication($validated);
        $application->syncInterviewStatusesFromStageResults();

        $application->refresh();
        $application->load('applicant');

        $detailLines = $this->buildApplicationUpdateDetailLines($originalData, $application);

        $activity = 'Updated ACTION Application: ' .
            ($application->applicant->first_name ?? '') . ' ' .
            ($application->applicant->last_name ?? '') . ".\n" .
            "Details:\n" .
            implode("\n", $detailLines);

        Log::createLog(
            'ACTION',
            $activity,
            auth()->id()
        );

        return redirect()
            ->route('action.applications.show', $application->id)
            ->with('success', config('errors.record_updated_successfully.errorMessage'));
    }

    public function print($id)
    {
        $application = ActionApplication::with([
            'applicant',
            'batch',
            'interviews.interviewer',
        ])->findOrFail($id);

        $interviews = $application->interviews
            ->map(fn ($interview) => $interview->toDisplayArray())
            ->values();

        return view('action.applications.print', [
            'application' => $application,
            'interviews' => $interviews,
            'examVenues' => config('constants.examVenues'),
        ]);
    }

    public function sendNotification(SendActionApplicationNotificationRequest $request, $applicationId)
    {
        $application = ActionApplication::with([
            'applicant',
            'batch',
            'interviews.interviewer',
        ])->findOrFail($applicationId);

        $link = url("/action/applications/{$application->id}");

        try {
            switch ($request->type) {
                case 'interviewer_pending_approval':
                    $pendingInterviews = $application->interviews
                        ->where('status', config('constants.interview_assignment_status.pending_approval'))
                        ->filter(fn ($interview) => is_null($interview->pending_approval_notified_at));

                    if ($pendingInterviews->isEmpty()) {
                        return response()->json([
                            'message' => 'No pending approval interviewers found.',
                        ], 422);
                    }

                    foreach ($pendingInterviews as $interview) {
                        if (!optional($interview->interviewer)->email_address) {
                            continue;
                        }

                        Mail::to($interview->interviewer->email_address)->send(
                            new ActionInterviewerPendingApprovalMail($application, $interview, $link)
                        );

                        $interview->update([
                            'pending_approval_notified_at' => now(),
                            'updated_by' => auth()->id(),
                            'updated_time' => now(),
                        ]);

                        usleep(9000000); // 9 seconds delay bc of mailtrap limit
                    }

                    break;

            case 'applicant_scheduled':
    $approvedInterviews = $application->interviews
        ->where('status', config('constants.interview_assignment_status.approved'));

    if ($approvedInterviews->isEmpty()) {
        return response()->json([
            'message' => 'No approved interview schedules found.',
        ], 422);
    }

    // Pick the latest/current stage that has approved assignments.
    $currentStage = null;

    if ($approvedInterviews->where('interview_type', config('constants.interview_types.final'))->isNotEmpty()) {
        $currentStage = config('constants.interview_types.final');
    } elseif ($approvedInterviews->where('interview_type', config('constants.interview_types.initial'))->isNotEmpty()) {
        $currentStage = config('constants.interview_types.initial');
    } elseif ($approvedInterviews->where('interview_type', config('constants.interview_types.exam'))->isNotEmpty()) {
        $currentStage = config('constants.interview_types.exam');
    }

    $stageInterviews = $approvedInterviews
        ->where('interview_type', $currentStage)
        ->values();

    // Make sure all assignments for that stage are approved before sending.
    $stageAllInterviews = $application->interviews
        ->where('interview_type', $currentStage);

    $hasUnapprovedForStage = $stageAllInterviews->contains(function ($interview) {
        return (int) $interview->status !== config('constants.interview_assignment_status.approved');
    });

    if ($hasUnapprovedForStage) {
        return response()->json([
            'message' => 'Applicant schedule email can only be sent after all assigned interviewers for this stage have approved.',
        ], 422);
    }

    if (empty(optional($application->applicant)->email_address)) {
        return response()->json([
            'message' => 'Applicant email address is missing.',
        ], 422);
    }

    Mail::to($application->applicant->email_address)->send(
        new ActionApplicantScheduledMail($application, $stageInterviews, $link)
    );

    break;
                case 'applicant_failed':
                    $failedStage = null;

                    if ((int) $application->final_interview_result === config('constants.application_results.failed')) {
                        $failedStage = 'Final Interview';
                    } elseif ((int) $application->initial_interview_result === config('constants.application_results.failed')) {
                        $failedStage = 'Initial Interview';
                    } elseif ((int) $application->exam_result === config('constants.application_results.failed')) {
                        $failedStage = 'Exam';
                    }

                    if (!$failedStage) {
                        return response()->json([
                            'message' => 'This application does not have a failed stage yet.',
                        ], 422);
                    }

                    if (empty(optional($application->applicant)->email_address)) {
                        return response()->json([
                            'message' => 'Applicant email address is missing.',
                        ], 422);
                    }

                    Mail::to($application->applicant->email_address)->send(
                        new ActionApplicantFailedMail($application, $failedStage, $link)
                    );

                    break;

                    case 'hr_recruiters_job_offer':
    if (empty($application->job_offer_schedule)) {
        return response()->json([
            'message' => 'No scheduled job offer found.',
        ], 422);
    }

    $hrRecruiters = User::hrRecruiters();

    $emails = $hrRecruiters
        ->pluck('email_address')
        ->filter()
        ->values()
        ->all();

    if (empty($emails)) {
        return response()->json([
            'message' => 'No HR recruiters found.',
        ], 422);
    }

    Mail::to($emails)->send(
        new ActionJobOfferScheduledMail($application, $link)
    );

    break;

                    break;
            }

$recipientList = [];

if ($request->type === 'interviewer_pending_approval') {
    $recipientList = $pendingInterviews
        ->map(function ($interview) {
            $interviewer = $interview->interviewer;

            return $interviewer?->full_name ?: ($interviewer?->email_address ?? 'Unknown');
        })
        ->filter()
        ->values()
        ->all();
}

if (in_array($request->type, ['applicant_scheduled', 'applicant_failed'], true)) {
    $recipientList = [
        $application->applicant?->full_name ?: ($application->applicant?->email_address ?? 'Unknown')
    ];
}

if ($request->type === 'hr_recruiters_job_offer') {
    $hrRecruiters = User::hrRecruiters(); // re-fetch here

    $recipientList = $hrRecruiters
        ->map(function ($user) {
            return $user->full_name ?: ($user->email_address ?? 'Unknown');
        })
        ->filter()
        ->values()
        ->all();
}

            if (in_array($request->type, ['applicant_scheduled', 'applicant_failed'], true)) {
                $recipientList = [
                    $application->applicant?->full_name ?: ($application->applicant?->email_address ?? 'Unknown')
                ];
            }

            Log::createLog(
                'ACTION',
                'Sent ' . $request->type . ' email/s to ' . implode(', ', $recipientList) .
                ' for application of ' .
                ($application->applicant->first_name ?? '') . ' ' .
                ($application->applicant->last_name ?? '') . '.',
                auth()->id()
            );

            return response()->json([
                'message' => config('errors.email_sent_success.errorMessage'),
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => config('errors.email_sent_failed.errorMessage'),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function bulkUpdateInterviewSchedule(BulkUpdateActionInterviewScheduleRequest $request, $applicationId)
    {
        $application = ActionApplication::with('applicant')->findOrFail($applicationId);

        DB::beginTransaction();

        try {
            $interviewsToUpdate = ActionApplicationInterview::where('action_application_id', $application->id)
                ->whereIn('id', $request->interview_ids)
                ->get();

            $oldScheduleMap = $interviewsToUpdate
                ->pluck('scheduled_date', 'id')
                ->toArray();

            foreach ($interviewsToUpdate as $interview) {
                $updateData = [
                    'scheduled_date' => $request->scheduled_date,
                    'updated_by' => auth()->id(),
                    'updated_time' => now(),
                ];

                if ((int) $interview->status === config('constants.interview_assignment_status.declined')) {
                    $updateData['status'] = config('constants.interview_assignment_status.pending_approval');
                    $updateData['decline_reason'] = null;
                    $updateData['pending_approval_notified_at'] = null;
                }

                $interview->update($updateData);
            }

            $rows = ActionApplicationInterview::with('interviewer')
                ->where('action_application_id', $application->id)
                ->orderBy('id')
                ->get();

            DB::commit();

            $detailLines = [];

            foreach ($interviewsToUpdate as $interview) {
                $oldDate = $oldScheduleMap[$interview->id] ?? '-';
                $detailLines[] = ($oldDate ?: '-') . ' -> ' . ($request->scheduled_date ?: '-');
            }

            Log::createLog(
                'ACTION',
                'Updated ' . $interviewsToUpdate->count() .
                ' interviewer(s) for application of ' .
                ($application->applicant->first_name ?? '') . ' ' .
                ($application->applicant->last_name ?? '') . ".\n" .
                "Details:\n" .
                implode("\n", $detailLines),
                auth()->id()
            );

            $interviews = $rows
                ->map(fn ($interview) => $interview->toDisplayArray())
                ->values();

            return response()->json([
                'message' => 'Record updated successfully.',
                'interviews' => $interviews,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update selected interview schedules.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function bulkAddInterviews(BulkAddActionInterviewsRequest $request, $id)
    {
        $application = ActionApplication::findOrFail($id);

        DB::beginTransaction();

        try {
            $existingRows = DB::table('action_application_interviews')
                ->where('action_application_id', $application->id)
                ->where('interview_type', $request->interview_type)
                ->whereIn('interviewer_id', $request->interviewer_ids)
                ->get()
                ->keyBy('interviewer_id');

            foreach ($request->interviewer_ids as $interviewerId) {
                $existing = $existingRows->get($interviewerId);

                if ($existing) {
                    DB::table('action_application_interviews')
                        ->where('id', $existing->id)
                        ->update([
                            'scheduled_date' => $request->scheduled_date,
                            'status' => config('constants.interview_assignment_status.pending_approval'),
                            'decline_reason' => null,
                            'pending_approval_notified_at' => null,
                            'updated_by' => auth()->id(),
                            'updated_time' => now(),
                        ]);
                } else {
                    DB::table('action_application_interviews')->insert([
                        'action_application_id' => $application->id,
                        'interviewer_id' => $interviewerId,
                        'interview_type' => $request->interview_type,
                        'scheduled_date' => $request->scheduled_date,
                        'status' => config('constants.interview_assignment_status.pending_approval'),
                        'decline_reason' => null,
                        'created_by' => auth()->id(),
                        'created_time' => now(),
                        'updated_by' => auth()->id(),
                        'updated_time' => now(),
                    ]);
                }
            }

            $rows = ActionApplicationInterview::with('interviewer')
                ->where('action_application_id', $application->id)
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                throw new \RuntimeException('No interview rows were found after insert.');
            }

            DB::commit();

            $interviews = $rows
                ->map(fn ($interview) => $interview->toDisplayArray())
                ->values();

            Log::createLog(
                'ACTION',
                'Added ' . count($request->interviewer_ids) .
                ' pending interviewer/conductor(s) to application #' . $application->id,
                auth()->id()
            );

            return response()->json([
                'message' => 'Interviewer(s) added successfully.',
                'interviews' => $interviews,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to add interviewer(s).',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function submitInterviewDecision(SubmitActionInterviewDecisionRequest $request, $applicationId, $interviewId)
    {
        $interview = ActionApplicationInterview::where('action_application_id', $applicationId)
            ->findOrFail($interviewId);

        if (!in_array((int) auth()->user()->permissions, [
            config('constants.HR_RECRUITER_PERMISSION.value'),
            config('constants.BU_MANAGER_PERMISSION.value'),
            config('constants.INTERVIEWER_PERMISSION.value'),
            config('constants.HR_MANAGER_PERMISSION.value'),
        ], true)) {
            abort(403, 'Only assigned recruiters, BU managers, or interviewers can submit a decision.');
        }

        if ((int) $interview->interviewer_id !== (int) auth()->id()) {
            abort(403, 'You are not assigned to this interview.');
        }

        $interview->update([
            'status' => $request->decision === 'accept'
                ? config('constants.interview_assignment_status.approved')
                : config('constants.interview_assignment_status.declined'),
            'decline_reason' => $request->decision === 'decline' ? $request->reason : null,
            'updated_by' => auth()->id(),
            'updated_time' => now(),
        ]);

        $application = ActionApplication::with('applicant')->findOrFail($applicationId);

        $interviewerName = auth()->user()->full_name;

        $activity = 'Interviewer ' . $interviewerName . ' ' .
            ($request->decision === 'accept' ? 'accepted' : 'declined') .
            ' assignment for application of ' .
            ($application->applicant->first_name ?? '') . ' ' .
            ($application->applicant->last_name ?? '') . '.';

        if ($request->decision === 'decline' && filled($request->reason)) {
            $activity .= "\nReason: " . $request->reason;
        }

        Log::createLog(
            'ACTION',
            $activity,
            auth()->id()
        );

        return response()->json([
            'message' => 'Decision submitted successfully.',
        ]);
    }

    /**
     * Get eligible applicants for a specific batch.
     */
    public function getApplicantsForBatch($batchId)
    {
        try {
            $eligibleApplicants = ActionApplicant::getEligibleApplicantsForBatch($batchId);
            return response()->json($eligibleApplicants->values());
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Shared options for application pages.
     */
    private function applicationFormOptions(): array
    {
        return [
            'examVenues' => config('constants.examVenues'),
            'examResults' => config('constants.examResults'),
            'examStatuses' => config('constants.examApplicationStatuses'),
            'interviewResults' => config('constants.interviewResults'),
            'interviewStatuses' => config('constants.applicationStatuses'),
            'interviewAppStatuses' => config('constants.applicationStatuses'),
            'jobOfferStatuses' => config('constants.jobOfferStatuses'),
            'applicationResultMap' => config('constants.application_result_map'),
            'applicationScoreRules' => config('constants.application_score_rules'),
        ];
    }

    /**
     * Handle uploads for create/update.
     */
    private function handleUploads($request, array $data, bool $allowNullReset = false): array
    {
        $uploadDirectories = [
            'upload_resume' => 'resumes',
            'upload_tor' => 'tors',
            'upload_pic' => 'pictures',
        ];

        foreach ($uploadDirectories as $field => $directory) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), $directory);
            } elseif ($allowNullReset && $request->input($field) === '') {
                $data[$field] = null;
            }
        }

        return $data;
    }

    /**
     * Filter allowed fields based on editable stages.
     */
private function filterValidatedFieldsByEditableStages(array $validated, array $editableStages): array
{
    $stageFields = config('constants.action_application_stage_fields', []);
    $allowedFieldMap = [];

    foreach ($editableStages as $stage => $isEditable) {
        if (!$isEditable || empty($stageFields[$stage])) {
            continue;
        }

        foreach ($stageFields[$stage] as $field) {
            $allowedFieldMap[$field] = true;
        }
    }

    return array_intersect_key($validated, $allowedFieldMap);
}

    /**
     * Build detailed update log lines.
     */
private function buildApplicationUpdateDetailLines(array $originalData, ActionApplication $application): array
{
    $stageFields = config('constants.action_application_stage_fields', []);
    $labels = config('constants.action_application_field_labels', []);

    // Flatten all stage fields into one unique list
    $fieldsToTrack = array_values(array_unique(array_merge(
        ...array_values($stageFields)
    )));

    $detailLines = [];

    foreach ($fieldsToTrack as $field) {
        $oldValue = $originalData[$field] ?? null;
        $newValue = $application->getAttribute($field);

        $oldText = blank($oldValue) ? '-' : (string) $oldValue;
        $newText = blank($newValue) ? '-' : (string) $newValue;

        if ($oldText === $newText) {
            continue;
        }

        $label = $labels[$field] ?? $field;

        $detailLines[] = "{$label}: {$oldText} -> {$newText}";
    }

    return $detailLines;
}

    /**
     * Handle file upload.
     */
    private function uploadFile($file, $directory)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs("uploads/{$directory}", $filename, 'public');
    }
}
