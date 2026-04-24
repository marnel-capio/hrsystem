<?php

namespace App\Http\Controllers;

use App\Models\IntermediateApplication;
use App\Models\IntermediateInterviewer;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IntermediateInterviewerController extends Controller
{
    /**
     * Get all interviewers for an application
     */
    public function index($applicationId)
    {
        $interviews = IntermediateInterviewer::with('interviewer') // ✅ Add eager loading
            ->where('intermediate_application_id', $applicationId)
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'interviewer_id' => $interview->interviewer_id,
                    'name' => $interview->interviewer->full_name ?? 'Unknown',
                    'email_address' => $interview->interviewer->email_address ?? '',
                    'role_label' => $interview->interviewer->role_label ?? 'Interviewer',
                    'interview_type' => $interview->interview_type,
                    'scheduled_date' => $interview->scheduled_date,
                    'status' => $interview->interview_status,
                    'decline_reason' => $interview->decline_reason,
                    'pending_approval_notified_at' => $interview->pending_approval_notified_at,
                ];
            });

        return response()->json(['interviews' => $interviews]);
    }

    /**
     * Get available interviewers (users who can be assigned)
     */
    public function available()
    {
        $interviewers = User::where('permissions', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_label' => $this->getRoleLabel($user->permissions),
                ];
            });

        return response()->json(['interviewers' => $interviewers]);
    }

    /**
     * Bulk add interviewers
     */
    public function bulkAdd(Request $request, $applicationId)
    {
        date_default_timezone_set('Asia/Manila');

        // ✅ DEBUG: Log all incoming data
        \Log::info('=== BULK ADD DEBUG ===');
        \Log::info('Application ID: '.$applicationId);
        \Log::info('Request all data: ', $request->all());
        \Log::info('Scheduled date raw: '.$request->input('scheduled_date'));
        \Log::info('Interview type: '.$request->input('interview_type'));
        \Log::info('Interviewer IDs: ', $request->input('interviewer_ids', []));

        $validator = Validator::make($request->all(), [
            'interviewer_ids' => 'required|array|min:1',
            'interviewer_ids.*' => 'required|integer|exists:users,id',
            'interview_type' => 'required|integer|in:1,2,3',
            'scheduled_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $application = IntermediateApplication::findOrFail($applicationId);
            $added = [];

            $scheduledDate = Carbon::parse($request->scheduled_date)
                ->timezone('Asia/Manila');

            foreach ($request->interviewer_ids as $interviewerId) {
                // Check if already assigned for this stage
                $exists = IntermediateInterviewer::where('intermediate_application_id', $applicationId)
                    ->where('interviewer_id', $interviewerId)
                    ->where('interview_type', $request->interview_type)
                    ->exists();

                if (! $exists) {
                    $interview = IntermediateInterviewer::create([
                        'interviewer_id' => $interviewerId,
                        'intermediate_application_id' => $applicationId,
                        'interview_type' => $request->interview_type,
                        'scheduled_date' => $request->scheduled_date,
                        'interview_status' => 1, // Pending Approval
                    ]);

                    $added[] = $interview;
                }
            }

            // Update application stage dates if needed
            $this->syncApplicationDates($application);

            // Log the action
            Log::createLog(
                'Intermediate',
                count($added)." interviewer(s) added to application #{$applicationId}",
                $application->intermediate_applicant_id
            );

            DB::commit();

            // Return fresh interviews with eager loaded interviewer
            $interviews = IntermediateInterviewer::with('interviewer')
                ->where('intermediate_application_id', $applicationId)
                ->get()
                ->map(function ($interview) {
                    return [
                        'id' => $interview->id,
                        'interviewer_id' => $interview->interviewer_id,
                        'name' => $interview->interviewer->full_name ?? 'Unknown',
                        'email_address' => $interview->interviewer->email_address ?? '',
                        'role_label' => $interview->interviewer->role_label ?? 'Interviewer',
                        'interview_type' => $interview->interview_type,
                        'scheduled_date' => $interview->scheduled_date,
                        'status' => $interview->interview_status,
                        'decline_reason' => $interview->decline_reason,
                        'pending_approval_notified_at' => $interview->pending_approval_notified_at,
                    ];
                });

            return response()->json([
                'message' => count($added).' interviewer(s) added successfully.',
                'interviews' => $interviews,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Bulk add interviewers failed: '.$e->getMessage());

            return response()->json([
                'message' => 'Failed to add interviewers',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Bulk update schedule
     */
    public function bulkUpdateSchedule(Request $request, $applicationId)
    {
        date_default_timezone_set('Asia/Manila');
        $validator = Validator::make($request->all(), [
            'interview_ids' => 'required|array|min:1',
            'interview_ids.*' => 'required|integer|exists:intermediate_application_interviews,id',
            'scheduled_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $application = IntermediateApplication::findOrFail($applicationId);

            $scheduledDate = Carbon::parse($request->scheduled_date)
                ->timezone('Asia/Manila');

            $updated = IntermediateInterviewer::whereIn('id', $request->interview_ids)
                ->update(['scheduled_date' => $request->scheduled_date]);

            // Sync application dates
            $this->syncApplicationDates($application);

            // Log the action
            Log::createLog(
                'Intermediate',
                $updated." interview schedule(s) updated for application #{$applicationId}",
                $application->intermediate_applicant_id
            );

            DB::commit();

            // Return fresh interviews with eager loaded interviewer
            $interviews = IntermediateInterviewer::with('interviewer')
                ->where('intermediate_application_id', $applicationId)
                ->get()
                ->map(function ($interview) {
                    return [
                        'id' => $interview->id,
                        'interviewer_id' => $interview->interviewer_id,
                        'name' => $interview->interviewer->full_name ?? 'Unknown',
                        'email_address' => $interview->interviewer->email_address ?? '',
                        'role_label' => $interview->interviewer->role_label ?? 'Interviewer',
                        'interview_type' => $interview->interview_type,
                        'scheduled_date' => $interview->scheduled_date,
                        'status' => $interview->interview_status,
                        'decline_reason' => $interview->decline_reason,
                        'pending_approval_notified_at' => $interview->pending_approval_notified_at,
                    ];
                });

            return response()->json([
                'message' => 'Schedules updated successfully!',
                'interviews' => $interviews,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Bulk update schedule failed: '.$e->getMessage());

            return response()->json([
                'message' => 'Failed to update schedules',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Accept or decline interview assignment
     */
    public function decision(Request $request, $applicationId, $interviewId)
    {
        $validator = Validator::make($request->all(), [
            'decision' => 'required|in:accept,decline',
            'reason' => 'required_if:decision,decline|nullable|string|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $interview = IntermediateInterviewer::where('intermediate_application_id', $applicationId)
                ->findOrFail($interviewId);

            $interview->interview_status = $request->decision === 'accept' ? 2 : 3; // 2=Approved, 3=Declined

            if ($request->decision === 'decline') {
                $interview->decline_reason = $request->reason;
            }

            $interview->save();

            // Check if all interviewers for this stage have approved
            $this->checkAndNotifyApplicant($applicationId, $interview->interview_type);

            // Log the action
            $action = $request->decision === 'accept' ? 'accepted' : 'declined';
            Log::createLog(
                'Intermediate',
                "Interviewer {$interview->interviewer->name} {$action} assignment for application #{$applicationId}",
                $interview->application->intermediate_applicant_id ?? null
            );

            DB::commit();

            return response()->json([
                'message' => $request->decision === 'accept'
                    ? 'Interview assignment accepted successfully!'
                    : 'Interview assignment declined.',
                'status' => $interview->interview_status,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Interview decision failed: '.$e->getMessage());

            return response()->json([
                'message' => 'Failed to submit decision',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Send notification
     */
    public function sendNotification(Request $request, $applicationId)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:interviewer_pending_approval,applicant_failed,hr_recruiters_job_offer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: Implement email notification logic
        // This is a placeholder for the email sending functionality

        if ($request->type === 'interviewer_pending_approval') {
            IntermediateInterviewer::where('intermediate_application_id', $applicationId)
                ->where('interview_status', 1)
                ->whereNull('pending_approval_notified_at')
                ->update(['pending_approval_notified_at' => now()]);
        }

        Log::createLog(
            'Intermediate',
            "Notification '{$request->type}' sent for application #{$applicationId}",
            IntermediateApplication::find($applicationId)->intermediate_applicant_id ?? null
        );

        return response()->json(['message' => 'Email sent successfully.']);
    }

    /**
     * Get initial interview assignments (approved interviewers with scores)
     */
    public function initialAssignments($applicationId)
    {
        return $this->getAssignmentsByType($applicationId, 2);
    }

    /**
     * Get final interview assignments
     */
    public function finalAssignments($applicationId)
    {
        return $this->getAssignmentsByType($applicationId, 3);
    }

    /**
     * Check if assignments have mixed results
     */
    public function hasMixedResults($applicationId, $interviewType)
    {
        $results = IntermediateInterviewer::where('intermediate_application_id', $applicationId)
            ->where('interview_type', $interviewType)
            ->where('interview_status', 2) // Approved only
            ->whereNotNull('evaluation_results')
            ->pluck('evaluation_results')
            ->toArray();

        $hasPassed = in_array(3, $results); // 3 = Passed
        $hasFailed = in_array(5, $results); // 5 = Failed

        return response()->json(['has_mixed' => $hasPassed && $hasFailed]);
    }

    /**
     * Helper: Sync application dates from interviews
     */
    private function syncApplicationDates($application): void
    {
        $exam = IntermediateInterviewer::where('intermediate_application_id', $application->id)
            ->where('interview_type', 1)
            ->first();

        $initial = IntermediateInterviewer::where('intermediate_application_id', $application->id)
            ->where('interview_type', 2)
            ->first();

        $final = IntermediateInterviewer::where('intermediate_application_id', $application->id)
            ->where('interview_type', 3)
            ->first();

        $updated = false;

        if ($exam) {
            $application->exam_plan_date = $exam->scheduled_date;
            $updated = true;
        }
        if ($initial) {
            $application->initial_interview_plan_date = $initial->scheduled_date;
            $updated = true;
        }
        if ($final) {
            $application->final_interview_date = $final->scheduled_date;
            $updated = true;
        }

        if ($updated) {
            $application->save();
        }
    }

    /**
     * Helper: Check if all interviewers approved and notify applicant
     */
    private function checkAndNotifyApplicant($applicationId, $interviewType): void
    {
        $pendingCount = IntermediateInterviewer::where('intermediate_application_id', $applicationId)
            ->where('interview_type', $interviewType)
            ->where('interview_status', 1) // Pending Approval
            ->count();

        if ($pendingCount === 0) {
            // All interviewers have responded - trigger applicant notification
            // TODO: Send email to applicant
            \Log::info("All interviewers have responded for application #{$applicationId}, stage #{$interviewType}");
        }
    }

    /**
     * Helper: Get assignments by interview type
     */
    private function getAssignmentsByType($applicationId, $interviewType)
    {
        $assignments = IntermediateInterviewer::with('interviewer')
            ->where('intermediate_application_id', $applicationId)
            ->where('interview_type', $interviewType)
            ->where('interview_status', 2) // Approved only
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'name' => $interview->interviewer->name ?? 'Unknown',
                    'role_label' => $this->getRoleLabel($interview->interviewer->permissions ?? 0),
                    'score' => $interview->evaluation_score,
                    'evaluation_result' => $interview->evaluation_results,
                    'evaluation_remarks' => $interview->evaluation_remarks,
                ];
            });

        return response()->json(['assignments' => $assignments]);
    }

    /**
     * Helper: Get role label from permissions
     */
    private function getRoleLabel($permissions): string
    {
        return match ((int) $permissions) {
            1 => 'HR Staff',
            2 => 'HR Manager',
            3 => 'Admin',
            5 => 'BU Manager',
            6 => 'Interviewer',
            default => 'Staff',
        };
    }

    /**
     * Update schedule for entire stage and reset all to pending
     */
    public function stageUpdateSchedule(Request $request, $applicationId)
    {
        date_default_timezone_set('Asia/Manila');
        $validator = Validator::make($request->all(), [
            'interview_type' => 'required|integer|in:1,2,3',
            'scheduled_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $application = IntermediateApplication::findOrFail($applicationId);
            $stage = $request->interview_type;

            $scheduledDate = Carbon::parse($request->scheduled_date)
                ->timezone('Asia/Manila');

            // Update ALL interviewers in this stage
            $updated = IntermediateInterviewer::where('intermediate_application_id', $applicationId)
                ->where('interview_type', $stage)
                ->update([
                    'scheduled_date' => $request->scheduled_date,
                    'interview_status' => 1, // Reset to Pending Approval
                    'decline_reason' => null, // Clear any decline reasons
                    'pending_approval_notified_at' => null, // Reset notification flag
                ]);

            // Update the application's plan date
            $applicationDate = null;
            if ($stage == 1) {
                $application->exam_plan_date = $request->scheduled_date;
                $applicationDate = $application->exam_plan_date;
            } elseif ($stage == 2) {
                $application->initial_interview_plan_date = $request->scheduled_date;
                $applicationDate = $application->initial_interview_plan_date;
            } elseif ($stage == 3) {
                $application->final_interview_date = $request->scheduled_date;
                $applicationDate = $application->final_interview_date;
            }
            $application->save();

            // Get stage name for logging
            $stageName = match ($stage) {
                1 => 'Exam',
                2 => 'Initial Interview',
                3 => 'Final Interview',
                default => 'Unknown'
            };

            // Log the action
            Log::createLog(
                'Intermediate',
                "{$stageName} schedule updated and reset to pending for application #{$applicationId} ({$updated} interviewer(s))",
                $application->intermediate_applicant_id
            );

            DB::commit();

            // Return fresh interviews with eager loaded interviewer
            $interviews = IntermediateInterviewer::with('interviewer')
                ->where('intermediate_application_id', $applicationId)
                ->get()
                ->map(function ($interview) {
                    return [
                        'id' => $interview->id,
                        'interviewer_id' => $interview->interviewer_id,
                        'name' => $interview->interviewer->full_name ?? 'Unknown',
                        'email_address' => $interview->interviewer->email_address ?? '',
                        'role_label' => $interview->interviewer->role_label ?? 'Interviewer',
                        'interview_type' => $interview->interview_type,
                        'scheduled_date' => $interview->scheduled_date,
                        'status' => $interview->interview_status,
                        'decline_reason' => $interview->decline_reason,
                        'pending_approval_notified_at' => $interview->pending_approval_notified_at,
                    ];
                });

            return response()->json([
                'message' => "{$stageName} schedule updated successfully! All interviewers reset to pending approval.",
                'interviews' => $interviews,
                'application_date' => $applicationDate,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Stage schedule update failed: '.$e->getMessage());

            return response()->json([
                'message' => 'Failed to update stage schedule',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
