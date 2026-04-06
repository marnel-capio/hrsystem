<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicationRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionBatchModel;
use App\Models\Log;
use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ActionApplicationInterview;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActionInterviewerPendingApprovalMail;
use App\Mail\ActionApplicantScheduledMail;
use App\Mail\ActionInterviewerApprovedMail;
use App\Mail\ActionApplicantFailedMail;

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
        return Inertia::render('action/applications/ActionApplicationRegister', [
            'actionBatches'        => ActionBatchModel::pluck('action_batch', 'id')->toArray(),
            'examVenues'           => config('constants.examVenues'),
            'examResults'          => config('constants.examResults'),
            'examStatuses'         => config('constants.examApplicationStatuses'),
            'interviewResults'     => config('constants.interviewResults'),
            'interviewAppStatuses' => config('constants.applicationStatuses'),
            'jobOfferStatuses'     => config('constants.jobOfferStatuses'),
            'applicationResultMap' => config('constants.application_result_map'),
            'applicationScoreRules'=> config('constants.application_score_rules'),
        ]);
    }

    /**
     * Store newly created application
     */
    public function store(RegisterActionApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data = $this->normalizeComputedFields($data);

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
    $application = ActionApplication::with([
        'applicant',
        'batch',
        'interviews.interviewer',
    ])->findOrFail($id);

    $positions = config('constants.positions');

    $interviews = $application->interviews->map(function ($row) use ($positions) {
        $interviewer = $row->interviewer;

return [
    'id' => $row->id,
    'interviewer_id' => $row->interviewer_id,
    'name' => $interviewer
        ? trim($interviewer->first_name . ' ' . $interviewer->last_name)
        : 'N/A',
    'email_address' => $interviewer?->email_address,
    'pending_approval_notified_at' => $row->pending_approval_notified_at,
    'role_label' => $interviewer ? match ((int) $interviewer->permissions) {
        config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
        config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
        config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
        config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
        default => 'User',
    } : 'N/A',
    'interview_type' => (int) $row->interview_type,
    'scheduled_date' => $row->scheduled_date,
    'status' => (int) ($row->status ?? 1),
];
    })->values();

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
    ->whereIn('permissions', [
        config('constants.HR_RECRUITER_PERMISSION.value'),
        config('constants.BU_MANAGER_PERMISSION.value'),
        config('constants.INTERVIEWER_PERMISSION.value'),
    ])
    ->orderBy('last_name')
    ->orderBy('first_name')
    ->get()
    ->map(function ($user) {
    return [
        'id' => $user->id,
        'name' => trim($user->first_name . ' ' . $user->last_name),
        'role_label' => match ((int) $user->permissions) {
            config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
            config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
            config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
            config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
            default => 'User',
        },
        'position' => $user->position,
        'permissions' => (int) $user->permissions,
        'email_address' => $user->email_address,
    ];
})
    ->values();

    return Inertia::render('action/applications/ActionApplicationDetail', [
        'application' => $application,

        'examVenues' => config('constants.examVenues'),
        'examResults' => config('constants.examResults'),
        'examStatuses' => config('constants.examApplicationStatuses'),
        'interviewResults' => config('constants.interviewResults'),
        'interviewStatuses' => config('constants.applicationStatuses'),
        'jobOfferStatuses' => config('constants.jobOfferStatuses'),

        'interviews' => $interviews,
        'availableInterviewers' => $availableInterviewers,
        'user_permissions' => auth()->user()->permissions,
        'user_id' => auth()->id(),
    ]);
}
public function edit($id)
{
    $application = ActionApplication::with([
        'applicant',
        'batch',
        'interviews',
    ])->findOrFail($id);

    $user = auth()->user();
    $permission = (int) $user->permissions;

    $fullAccessPermissions = [
        config('constants.HR_ADMIN_PERMISSION.value'),
        config('constants.HR_MANAGER_PERMISSION.value'),
        config('constants.HR_RECRUITER_PERMISSION.value'),
    ];

    $editableStages = [
        'exam' => false,
        'initial_interview' => false,
        'final_interview' => false,
        'job_offer' => false,
        'general' => false,
        'documents' => false,
    ];

    if (in_array($permission, $fullAccessPermissions, true)) {
        $editableStages = [
            'exam' => true,
            'initial_interview' => true,
            'final_interview' => true,
            'job_offer' => true,
            'general' => true,
            'documents' => true,
        ];
    } else {
        $approvedAssignments = $application->interviews
            ->where('interviewer_id', auth()->id())
            ->where('status', 2);

        foreach ($approvedAssignments as $assignment) {
            if ((int) $assignment->interview_type === 1) {
                $editableStages['exam'] = true;
            }

            if ((int) $assignment->interview_type === 2) {
                $editableStages['initial_interview'] = true;
            }

            if ((int) $assignment->interview_type === 3) {
                $editableStages['final_interview'] = true;
            }
        }
    }

    if (!in_array(true, $editableStages, true)) {
        abort(403, 'You are not allowed to edit this application.');
    }

    return Inertia::render('action/applications/ActionApplicationEdit', [
        'application' => $application,
        'examVenues' => config('constants.examVenues'),
        'examResults' => config('constants.examResults'),
        'examStatuses' => config('constants.examApplicationStatuses'),
        'interviewResults' => config('constants.interviewResults'),
        'interviewAppStatuses' => config('constants.applicationStatuses'),
        'jobOfferStatuses' => config('constants.jobOfferStatuses'),
        'applicationResultMap' => config('constants.application_result_map'),
        'applicationScoreRules' => config('constants.application_score_rules'),
        'editableStages' => $editableStages,
        'user_permissions' => $permission,
        'user_id' => auth()->id(),
    ]);
}

public function update(Request $request, $id)
{
    $application = ActionApplication::with('interviews')->findOrFail($id);
    $user = auth()->user();
    $permission = (int) $user->permissions;

    $validated = $request->validate([
        'exam_plan_date' => 'nullable|date',
        'exam_actual_date' => 'nullable|date',
        'exam_venue' => 'nullable|integer',
        'exam_atpp_result' => 'nullable|numeric',
        'exam_git_result' => 'nullable|numeric',
        'exam_prg_result' => 'nullable|numeric',
        'exam_result' => 'nullable|integer',
        'exam_application_status' => 'nullable|integer',
        'exam_remarks' => 'nullable|string',

        'initial_interview_plan_date' => 'nullable|date',
        'initial_interview_actual_date' => 'nullable|date',
        'initial_interview_venue' => 'nullable|integer',
        'initial_interview_final' => 'nullable|numeric',
        'initial_interview_result' => 'nullable|integer',
        'initial_interview_application_status' => 'nullable|integer',
        'initial_interview_remarks' => 'nullable|string',

        'final_interview_date' => 'nullable|date',
        'final_interview_sf' => 'nullable|numeric',
        'final_interview_ib' => 'nullable|numeric',
        'final_interview_rv' => 'nullable|numeric',
        'final_interview_ma' => 'nullable|numeric',
        'final_interview_final' => 'nullable|numeric',
        'final_interview_result' => 'nullable|integer',
        'final_interview_application_status' => 'nullable|integer',
        'final_interview_remarks' => 'nullable|string',

        'job_offer_schedule' => 'nullable|date',
        'job_offer_status' => 'nullable|integer',
        'job_offer_remarks' => 'nullable|string',
        'remarks' => 'nullable|string',

        'upload_resume' => 'nullable',
        'upload_tor' => 'nullable',
        'upload_pic' => 'nullable',
    ]);

    if ($request->hasFile('upload_resume')) {
        $validated['upload_resume'] = $this->uploadFile($request->file('upload_resume'), 'resumes');
    } elseif ($request->input('upload_resume') === '') {
        $validated['upload_resume'] = null;
    }

    if ($request->hasFile('upload_tor')) {
        $validated['upload_tor'] = $this->uploadFile($request->file('upload_tor'), 'tors');
    } elseif ($request->input('upload_tor') === '') {
        $validated['upload_tor'] = null;
    }

    if ($request->hasFile('upload_pic')) {
        $validated['upload_pic'] = $this->uploadFile($request->file('upload_pic'), 'pictures');
    } elseif ($request->input('upload_pic') === '') {
        $validated['upload_pic'] = null;
    }

    $fullAccessPermissions = [
        config('constants.HR_ADMIN_PERMISSION.value'),
        config('constants.HR_MANAGER_PERMISSION.value'),
        config('constants.HR_RECRUITER_PERMISSION.value'),
    ];

    $editableStages = [
        'exam' => false,
        'initial_interview' => false,
        'final_interview' => false,
        'job_offer' => false,
        'general' => false,
        'documents' => false,
    ];

    if (in_array($permission, $fullAccessPermissions, true)) {
        $editableStages = [
            'exam' => true,
            'initial_interview' => true,
            'final_interview' => true,
            'job_offer' => true,
            'general' => true,
            'documents' => true,
        ];
    } else {
        $approvedAssignments = $application->interviews
            ->where('interviewer_id', auth()->id())
            ->where('status', 2);

        foreach ($approvedAssignments as $assignment) {
            if ((int) $assignment->interview_type === 1) {
                $editableStages['exam'] = true;
            }

            if ((int) $assignment->interview_type === 2) {
                $editableStages['initial_interview'] = true;
            }

            if ((int) $assignment->interview_type === 3) {
                $editableStages['final_interview'] = true;
            }
        }
    }

    if (!in_array(true, $editableStages, true)) {
        abort(403, 'You are not allowed to update this application.');
    }

    $stageFields = [
        'exam' => [
            'exam_plan_date',
            'exam_actual_date',
            'exam_venue',
            'exam_atpp_result',
            'exam_git_result',
            'exam_prg_result',
            'exam_result',
            'exam_application_status',
            'exam_remarks',
        ],
        'initial_interview' => [
            'initial_interview_plan_date',
            'initial_interview_actual_date',
            'initial_interview_venue',
            'initial_interview_final',
            'initial_interview_result',
            'initial_interview_application_status',
            'initial_interview_remarks',
        ],
        'final_interview' => [
            'final_interview_date',
            'final_interview_sf',
            'final_interview_ib',
            'final_interview_rv',
            'final_interview_ma',
            'final_interview_final',
            'final_interview_result',
            'final_interview_application_status',
            'final_interview_remarks',
        ],
        'job_offer' => [
            'job_offer_schedule',
            'job_offer_status',
            'job_offer_remarks',
        ],
        'general' => [
            'remarks',
        ],
        'documents' => [
            'upload_resume',
            'upload_tor',
            'upload_pic',
        ],
    ];

    if (!in_array($permission, $fullAccessPermissions, true)) {
        $allowedFields = [];

        foreach ($stageFields as $stage => $fields) {
            if ($editableStages[$stage]) {
                $allowedFields = array_merge($allowedFields, $fields);
            }
        }

        $validated = array_intersect_key($validated, array_flip($allowedFields));
    }

    $validated = $this->normalizeComputedFields(array_merge($application->toArray(), $validated));

    $application->update($validated);
    $this->syncInterviewStatusesFromStageResults($application);

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

    $interviews = $application->interviews->map(function ($row) {
        $interviewer = $row->interviewer;

        return [
            'id' => $row->id,
            'name' => $interviewer
                ? trim($interviewer->first_name . ' ' . $interviewer->last_name)
                : 'N/A',
            'email_address' => $interviewer?->email_address,
            'role_label' => $interviewer ? match ((int) $interviewer->permissions) {
                config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
                config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
                config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
                config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
                default => 'User',
            } : 'N/A',
            'interview_type' => (int) $row->interview_type,
            'scheduled_date' => $row->scheduled_date,
            'status' => (int) ($row->status ?? 1),
            'decline_reason' => $row->decline_reason,
        ];
    })->values();

    return view('action.applications.print', [
        'application' => $application,
        'interviews' => $interviews,
        'examVenues' => config('constants.examVenues'),
    ]);
}

public function sendNotification(Request $request, $applicationId)
{
    $request->validate([
        'type' => ['required', 'string', 'in:interviewer_pending_approval,applicant_scheduled,applicant_failed'],
    ]);

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
    ->where('status', 1)
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

    usleep(9000000); // 9 second delay
}
                break;

            case 'applicant_scheduled':
                $approvedInterviews = $application->interviews->where('status', 2);

                if ($approvedInterviews->isEmpty()) {
                    return response()->json([
                        'message' => 'No approved interview schedules found.',
                    ], 422);
                }

                if (empty(optional($application->applicant)->email_address)) {
                    return response()->json([
                        'message' => 'Applicant email address is missing.',
                    ], 422);
                }

                Mail::to($application->applicant->email_address)->send(
                    new ActionApplicantScheduledMail($application, $approvedInterviews->values(), $link)
                );
                break;

            case 'applicant_failed':
                $failedStage = null;

                if ((int) $application->final_interview_result === 3) {
                    $failedStage = 'Final Interview';
                } elseif ((int) $application->initial_interview_result === 3) {
                    $failedStage = 'Initial Interview';
                } elseif ((int) $application->exam_result === 3) {
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
        }

        return response()->json([
            'message' => config('errors.email_sent_success.errorMessage'),
        ]);
    } catch (\Exception $e) {
    \Log::error('ACTION sendNotification failed', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);

    return response()->json([
        'message' => config('errors.email_sent_failed.errorMessage'),
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ], 500);
}
}

private function syncInterviewStatusesFromStageResults(ActionApplication $application): void
{
    // Initial Interview stage = interview_type 2
    if (in_array((int) $application->initial_interview_result, [2, 3], true)) {
        ActionApplicationInterview::where('action_application_id', $application->id)
            ->where('interview_type', 2)
            ->where('status', '!=', 3) // keep declined rows as Declined
            ->update([
                'status' => 4,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);
    }

    // Final Interview stage = interview_type 3
    if (in_array((int) $application->final_interview_result, [2, 3], true)) {
        ActionApplicationInterview::where('action_application_id', $application->id)
            ->where('interview_type', 3)
            ->where('status', '!=', 3) // keep declined rows as Declined
            ->update([
                'status' => 4,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);
    }
}

public function bulkUpdateInterviewSchedule(Request $request, $applicationId)
{
    $request->validate([
        'interview_ids' => ['required', 'array', 'min:1'],
        'interview_ids.*' => ['required', 'integer'],
        'scheduled_date' => ['required', 'date'],
    ]);

    $application = ActionApplication::findOrFail($applicationId);

    DB::beginTransaction();

    try {
        $interviewsToUpdate = ActionApplicationInterview::where('action_application_id', $application->id)
            ->whereIn('id', $request->interview_ids)
            ->get();

        foreach ($interviewsToUpdate as $interview) {
            $updateData = [
                'scheduled_date' => $request->scheduled_date,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ];

            // If previously declined, reset to Pending Approval
if ((int) $interview->status === 3) {
    $updateData['status'] = 1;
    $updateData['decline_reason'] = null;
    $updateData['pending_approval_notified_at'] = null;
}

            $interview->update($updateData);
        }

        $positions = config('constants.positions');

$rows = DB::table('action_application_interviews')
    ->leftJoin('users', 'action_application_interviews.interviewer_id', '=', 'users.id')
    ->where('action_application_interviews.action_application_id', $application->id)
    ->orderBy('action_application_interviews.id')
    ->get([
        'action_application_interviews.id',
        'action_application_interviews.interviewer_id',
        'action_application_interviews.interview_type',
        'action_application_interviews.scheduled_date',
        'action_application_interviews.status',
        'users.first_name',
        'users.last_name',
        'users.position',
        'users.permissions',
        'users.email_address',
        'action_application_interviews.pending_approval_notified_at',
    ]);

        DB::commit();

$interviews = $rows->map(function ($row) {
    return [
        'id' => $row->id,
        'interviewer_id' => $row->interviewer_id,
        'name' => trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: 'N/A',
        'email_address' => $row->email_address ?? null,
        'pending_approval_notified_at' => $row->pending_approval_notified_at,
        'role_label' => match ((int) $row->permissions) {
            config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
            config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
            config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
            config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
            default => 'User',
        },
        'interview_type' => (int) $row->interview_type,
        'scheduled_date' => $row->scheduled_date,
        'status' => (int) ($row->status ?? 1),
    ];
})->values();

        return response()->json([
            'message' => 'Selected interview schedules updated successfully.',
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

public function bulkAddInterviews(Request $request, $id)
{
    LaravelLog::info('--- BULK ADD HIT ---');
LaravelLog::info('DB name: ' . DB::connection()->getDatabaseName());
LaravelLog::info('Payload:', $request->all());

    $request->validate([
        'interviewer_ids' => ['required', 'array', 'min:1'],
        'interviewer_ids.*' => ['required', 'integer', 'exists:users,id'],
        'interview_type' => ['required', 'integer', 'in:1,2,3'],
        'scheduled_date' => ['required', 'date'],
    ]);

    $application = ActionApplication::findOrFail($id);
    $positions = config('constants.positions');

    DB::beginTransaction();

    try {
        foreach ($request->interviewer_ids as $interviewerId) {
            $existing = DB::table('action_application_interviews')
                ->where('action_application_id', $application->id)
                ->where('interviewer_id', $interviewerId)
                ->where('interview_type', $request->interview_type)
                ->first();

            if ($existing) {
                DB::table('action_application_interviews')
                    ->where('id', $existing->id)
                    ->update([
    'scheduled_date' => $request->scheduled_date,
    'status' => 1,
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
                    'status' => 1,
                    'decline_reason' => null,
                    'created_by' => auth()->id(),
                    'created_time' => now(),
                    'updated_by' => auth()->id(),
                    'updated_time' => now(),
                ]);

                LaravelLog::info("Inserted interviewer ID: {$interviewerId}");
            }
        }

$rows = DB::table('action_application_interviews')
    ->leftJoin('users', 'action_application_interviews.interviewer_id', '=', 'users.id')
    ->where('action_application_interviews.action_application_id', $application->id)
    ->orderBy('action_application_interviews.id')
    ->get([
        'action_application_interviews.id',
        'action_application_interviews.interviewer_id',
        'action_application_interviews.interview_type',
        'action_application_interviews.scheduled_date',
        'action_application_interviews.status',
        'action_application_interviews.pending_approval_notified_at',
        'users.first_name',
        'users.last_name',
        'users.position',
        'users.permissions',
        'users.email_address',
    ]);

            LaravelLog::info('Rows after insert:', $rows->toArray());

        if ($rows->isEmpty()) {
            throw new \RuntimeException('No interview rows were found after insert.');
        }

        DB::commit();

$interviews = $rows->map(function ($row) {
    return [
        'id' => $row->id,
        'interviewer_id' => $row->interviewer_id,
        'name' => trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: 'N/A',
        'email_address' => $row->email_address ?? null,
        'pending_approval_notified_at' => $row->pending_approval_notified_at,
        'role_label' => match ((int) $row->permissions) {
            config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
            config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
            config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
            config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
            default => 'User',
        },
        'interview_type' => (int) $row->interview_type,
        'scheduled_date' => $row->scheduled_date,
        'status' => (int) ($row->status ?? 1),
    ];
})->values();

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

public function submitInterviewDecision(Request $request, $applicationId, $interviewId)
{
    $request->validate([
        'decision' => ['required', 'in:accept,decline'],
        'reason' => ['nullable', 'string', 'max:1024'],
    ]);

    $interview = ActionApplicationInterview::where('action_application_id', $applicationId)
        ->findOrFail($interviewId);

if (!in_array((int) auth()->user()->permissions, [
    config('constants.HR_RECRUITER_PERMISSION.value'),
    config('constants.BU_MANAGER_PERMISSION.value'),
    config('constants.INTERVIEWER_PERMISSION.value'),
], true)) {
    abort(403, 'Only assigned recruiters, BU managers, or interviewers can submit a decision.');
}

    if ((int) $interview->interviewer_id !== (int) auth()->id()) {
        abort(403, 'You are not assigned to this interview.');
    }

    if ($request->decision === 'decline' && !filled($request->reason)) {
        return response()->json([
            'message' => 'Reason is required when declining.'
        ], 422);
    }

$interview->update([
    'status' => $request->decision === 'accept' ? 2 : 3,
    'decline_reason' => $request->decision === 'decline' ? $request->reason : null,
    'updated_by' => auth()->id(),
    'updated_time' => now(),
]);

    return response()->json([
        'message' => 'Decision submitted successfully.',
    ]);
}

    /**
     * Get eligible applicants for a specific batch
     */
    public function getApplicantsForBatch($batchId)
    {
        try {
            \LaravelLog::info('Getting eligible applicants for batch: ' . $batchId);

            $eligibleApplicants = ActionApplicant::getEligibleApplicantsForBatch($batchId);

            \LaravelLog::info('Found ' . count($eligibleApplicants) . ' eligible applicants');

            return response()->json($eligibleApplicants->values());
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

    private function normalizeComputedFields(array $data): array
    {
        $applicant = ActionApplicant::find($data['action_applicant_id'] ?? null);

        if (!$applicant) {
            return $data;
        }

        if (
            $this->hasValue($data, 'exam_atpp_result') &&
            $this->hasValue($data, 'exam_git_result') &&
            $this->hasValue($data, 'exam_prg_result')
        ) {
            $data['exam_application_status'] = $this->computeExamApplicationStatus(
                (float) $data['exam_atpp_result'],
                (float) $data['exam_git_result'],
                (float) $data['exam_prg_result'],
                $applicant
            );
        } elseif ($this->hasValue($data, 'exam_plan_date')) {
            $data['exam_application_status'] = 1; // Pending
        } else {
            $data['exam_application_status'] = null;
        }

        $data['exam_result'] = $this->hasValue($data, 'exam_application_status')
            ? $this->mapResultFromStatus('exam', (int) $data['exam_application_status'])
            : null;

        if ($this->hasValue($data, 'initial_interview_final')) {
            $data['initial_interview_application_status'] = $this->computeInitialInterviewApplicationStatus(
                (float) $data['initial_interview_final']
            );
        } elseif ($this->hasValue($data, 'initial_interview_plan_date')) {
            $data['initial_interview_application_status'] = 1; // Pending
        } else {
            $data['initial_interview_application_status'] = null;
        }

        $data['initial_interview_result'] = $this->hasValue($data, 'initial_interview_application_status')
            ? $this->mapResultFromStatus('initial_interview', (int) $data['initial_interview_application_status'])
            : null;

        if (!$this->hasValue($data, 'final_interview_application_status') && $this->hasValue($data, 'final_interview_date')) {
            $data['final_interview_application_status'] = 1; // Pending
        }

        $data['final_interview_result'] = $this->hasValue($data, 'final_interview_application_status')
            ? $this->mapResultFromStatus('final_interview', (int) $data['final_interview_application_status'])
            : null;

        if (!$this->hasValue($data, 'job_offer_status') && $this->hasValue($data, 'job_offer_schedule')) {
            $data['job_offer_status'] = 1; // Pending
        }

        return $data;
    }

    private function computeExamApplicationStatus(
        float $attp,
        float $git,
        float $prg,
        ActionApplicant $applicant
    ): int {
        $rules = config('constants.application_score_rules.exam');
        $category = $this->resolveExamCategory($applicant);
        $categoryRules = $rules[$category] ?? [];

        $passed = $categoryRules['passed'] ?? null;
        $p2 = $categoryRules['p2'] ?? null;

        if (
            $passed &&
            $attp >= $passed['attp'] &&
            $git >= $passed['git'] &&
            $prg >= $passed['prg']
        ) {
            return 5; // Passed
        }

        if (
            $p2 &&
            $attp >= $p2['attp'] &&
            $git >= $p2['git'] &&
            $prg >= $p2['prg']
        ) {
            return 3; // 2nd Priority (P2)
        }

        return 6; // Failed
    }

    private function computeInitialInterviewApplicationStatus(float $score): int
    {
        $rules = config('constants.application_score_rules.initial_interview');

        $failedMin = (float) ($rules['failed_min'] ?? 4.0);
        $p2Min = (float) ($rules['p2_min'] ?? 2.5);
        $passedMin = (float) ($rules['passed_min'] ?? 2.0);

        if ($score >= $failedMin) {
            return 5; // Failed
        }

        if ($score >= $p2Min) {
            return 4; // P2
        }

        if ($score >= $passedMin) {
            return 3; // Passed
        }

        return 2; // Done
    }

    private function mapResultFromStatus(string $type, int $status): ?int
    {
        $map = config("constants.application_result_map.{$type}", []);

        return $map[$status] ?? null;
    }

    private function resolveExamCategory(ActionApplicant $applicant): string
    {
        $age = (int) $applicant->age;

        if ($age >= 25) {
            return 'adult';
        }

        $rawDegree = !empty($applicant->others_degree)
            ? $applicant->others_degree
            : $applicant->degree;

        $normalizedDegree = $this->normalizeDegree((string) $rawDegree);

        return $this->isTechDegree($normalizedDegree) ? 'young_it' : 'young_other';
    }

    private function normalizeDegree(string $degree): string
    {
        $degree = strtolower(trim($degree));
        $degree = preg_replace('/[^a-z0-9\s]/', ' ', $degree);
        $degree = preg_replace('/\s+/', ' ', $degree);

        return $degree;
    }

    private function isTechDegree(string $degree): bool
    {
        $patterns = config('constants.tech_degree_patterns', []);

        foreach ($patterns as $pattern) {
            $normalizedPattern = $this->normalizeDegree((string) $pattern);

            if ($normalizedPattern === '') {
                continue;
            }

            if (in_array($normalizedPattern, ['it', 'cs', 'cpe', 'bsit', 'bscs', 'bsce'], true)) {
                if (preg_match('/\b' . preg_quote($normalizedPattern, '/') . '\b/', $degree)) {
                    return true;
                }
                continue;
            }

            if (str_contains($degree, $normalizedPattern)) {
                return true;
            }
        }

        return false;
    }

    private function hasValue(array $data, string $key): bool
    {
        return array_key_exists($key, $data)
            && $data[$key] !== null
            && $data[$key] !== '';
    }
}
