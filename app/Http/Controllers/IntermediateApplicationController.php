<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntermediateApplicationRequest;
use App\Mail\ATS0004Mail;
use App\Mail\ATS0007Mail;
use App\Mail\ATS0008Mail;
use App\Mail\ATS0009Mail;
use App\Models\IntermediateApplicant;
use App\Models\IntermediateApplication;
use App\Models\IntermediateInterviewer;
use App\Models\IntermediateRequisitionModel;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class IntermediateApplicationController extends Controller
{
    /**
     * Display listing of intermediate applications.
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->input('search'),
        ];

        $query = IntermediateApplication::with('intermediateApplicant');

        // Optional search filter
        if (! empty($filters['search'])) {
            $query->whereHas('intermediateApplicant', function ($q) use ($filters) {
                $search = $filters['search'];
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        $applications = $query
            ->orderBy('created_time', 'desc')
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'first_name' => $application->intermediateApplicant?->first_name ?? 'Unknown',
                    'last_name' => $application->intermediateApplicant?->last_name ?? '',
                    'applicant_name' => $application->fullApplicantName,
                    'project_name' => $application->projectName,
                    'position' => $application->position,
                    'application_stage' => $application->application_stage,
                    'job_offer_status' => $application->job_offer_status,
                    'remarks' => $application->remarks,
                ];
            });

        return Inertia::render('intermediate/applications/Index', [
            'applications' => $applications,
            'filters' => $filters,
            'userPermissions' => auth()->user()->permissions ?? 0,
            'errorsConfig' => [
                'field_required' => 'This field is required.',
                'file_too_large' => 'File size must not exceed 10MB.',
            ],
        ]);
    }

    public function create()
    {
        $cutoffDate = Carbon::now()->subMonths(6);

        $intermediateApplicants = IntermediateApplicant::query()
            ->with(['workExperiences', 'skills']) // ✅ Eager load relationships
            ->where(function ($query) use ($cutoffDate) {
                $query->whereDoesntHave('applications')
                    ->orWhereHas('latestApplication', function ($q) use ($cutoffDate) {
                        $q->where('created_time', '<', $cutoffDate);
                    });
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(fn ($applicant) => [
                'id' => $applicant->id,
                'name' => trim($applicant->first_name.' '.$applicant->last_name),
                'email_address' => $applicant->email_address,
                // ✅ Include work experience job titles
                'work_experiences' => $applicant->workExperiences
                    ->where('is_deleted', '!=', 1)
                    ->pluck('job_title')
                    ->filter()
                    ->values()
                    ->toArray(),
                // ✅ Include skills
                'skills' => $applicant->skills
                    ->where('is_deleted', '!=', 1)
                    ->pluck('skill')
                    ->filter()
                    ->values()
                    ->toArray(),
            ])
            ->toArray();

        return Inertia::render('intermediate/applications/Register', [
            'examStatuses' => config('constants.exam_statuses'),
            'hrStatuses' => config('constants.interview_statuses'),
            'finalStatuses' => config('constants.interview_statuses'),
            'jobOfferStatuses' => config('constants.job_offer_statuses'),
            'applicationResultMap' => config('constants.application_result_map'),

            'sourceProjects' => IntermediateRequisitionModel::query()
                ->whereHas('project')
                ->with('project')
                ->orderBy('created_time', 'desc')
                ->get()
                ->map(fn ($requisition) => [
                    'value' => $requisition->id,
                    'label' => $requisition->resource.' - '.
                              ($requisition->project->project_name ?? 'No Project').' ('.
                              $requisition->getLocationAssignmentLabelAttribute().')',
                ])
                ->toArray(),

            'intermediateApplicants' => $intermediateApplicants,
            'currentUserInfo' => [
                'id' => auth()->id(),
                'first_name' => auth()->user()->first_name,
                'last_name' => auth()->user()->last_name,
                'name' => auth()->user()->full_name ?? auth()->user()->name,
            ],
            
        ]);
    }

    public function store(StoreIntermediateApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            // throw new \Exception("Test error");

            $dateFields = [
                'exam_plan_date',
                'exam_actual_date',
                'initial_interview_plan_date',
                'initial_interview_actual_date',
                'final_interview_date',
                'job_offer_schedule',
                'contacted_date',
                'replied_date',
            ];

            foreach ($dateFields as $field) {
                if (! empty($data[$field])) {
                    $data[$field] = Carbon::parse($data[$field])->timezone('Asia/Manila');
                }
            }

            //  DEFAULT VALUES (non-null)
            $data['paper_screening_status'] = 1;  // Screening Pending
            $data['application_stage'] = 1;       // New/Screening

            //  UPGRADE if advanced data exists
            $data['application_stage'] = $this->determineApplicationStage($data);

            // File uploads
            $resumePath = $request->file('upload_resume')
                ? $request->file('upload_resume')->store('resumes', 'public')
                : null;

            $picPath = $request->file('upload_pic')
                ? $request->file('upload_pic')->store('pictures', 'public')
                : null;

            $data['upload_resume'] = $resumePath ? basename($resumePath) : null;
            $data['upload_pic'] = $picPath ? basename($picPath) : null;

            $requisition = IntermediateRequisitionModel::with('project')
                ->find($data['resource_schedule_id'] ?? null);
            $data['source_project_id'] = $requisition?->project_id;

            $data['fy_week'] = $this->getFyWeekFromCreatedDate(Carbon::now());

            $application = IntermediateApplication::create($data);

            // update applicant registered date
            $application->intermediateApplicant()->update([
                'registered_date' => $application->created_time ?? now(),
            ]);

            Log::createLog(
                'Intermediate',
                "Application for {$application->intermediateApplicant?->email_address} registered successfully.",
                $application->intermediate_applicant_id
            );

            DB::commit();

            return redirect()->route('intermediate.applications.show', $application->id)
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($resumePath)) {
                \Storage::disk('public')->delete($resumePath);
            }
            if (isset($picPath)) {
                \Storage::disk('public')->delete($picPath);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => config('errors.transaction_failed.errorMessage')]);
        }
    }

    private function determineApplicationStage(array &$data): int
    {
        // Priority 1: Job Offer
        if ($this->hasJobOfferData($data)) {
            $data['paper_screening_status'] = 3;  // Passed

            return 5;
        }

        // Priority 2: Final Interview
        if ($this->hasFinalInterviewData($data)) {
            $data['paper_screening_status'] = 3;  // Passed

            return 4;
        }

        // Priority 3: Initial Interview
        if ($this->hasInitialInterviewData($data)) {
            $data['paper_screening_status'] = 3;  // Passed

            return 3;
        }

        // Priority 4: Exam
        if ($this->hasExamData($data)) {
            $data['paper_screening_status'] = 3;  // Passed

            return 2;
        }

        $data['paper_screening_status'] = 1;  // Screening Pending
        $data['application_stage'] = 1;       // New/Screening

        return 1;
    }

    private function getFyWeekFromCreatedDate(Carbon|string|null $createdDate): ?int
    {
        if (! $createdDate) {
            return null;
        }

        $date = $createdDate instanceof Carbon
            ? $createdDate->copy()
            : Carbon::parse($createdDate);

        // FY starts April
        $fyYear = $date->month >= 4 ? $date->year : $date->year - 1;

        $aprilStart = Carbon::create($fyYear, 4, 1)->startOfDay();

        // Find first Monday of April FY
        $weekStart = $aprilStart->copy()->startOfWeek(Carbon::MONDAY);

        // ensure it doesn't start before April 1
        if ($weekStart->lt($aprilStart)) {
            $weekStart->addWeek();
        }

        // compute FY week number
        return intdiv($weekStart->diffInDays($date), 7) + 1;
    }

    /**
     * Check if any Job Offer fields have data
     */
    private function hasJobOfferData(array $data): bool
    {
        return ! empty($data['job_offer_schedule']) ||
               ! empty($data['job_offer_status']) ||
               ! empty($data['job_offer_remarks']);
    }

    /**
     * Check if any Final Interview fields have data
     */
    private function hasFinalInterviewData(array $data): bool
    {
        return ! empty($data['final_interview_date']) ||
               ! empty($data['final_interview_final']) ||
               ! empty($data['final_interview_result']) ||
               ! empty($data['final_interview_application_status']) ||
               ! empty($data['final_interview_remarks']);
    }

    /**
     * Check if any Initial Interview fields have data
     */
    private function hasInitialInterviewData(array $data): bool
    {
        return ! empty($data['initial_interview_plan_date']) ||
               ! empty($data['initial_interview_actual_date']) ||
               ! empty($data['initial_interview_venue']) ||
               ! empty($data['initial_interview_final']) ||
               ! empty($data['initial_interview_result']) ||
               ! empty($data['initial_interview_application_status']) ||
               ! empty($data['initial_interview_remarks']);
    }

    /**
     * Check if any Exam fields have data
     */
    private function hasExamData(array $data): bool
    {
        return ! empty($data['exam_plan_date']) ||
               ! empty($data['exam_actual_date']) ||
               ! empty($data['exam_venue']) ||
               ! empty($data['exam_atpp_part1_correct']) ||
               ! empty($data['exam_atpp_part1_wrong']) ||
               ! empty($data['exam_atpp_part2_correct']) ||
               ! empty($data['exam_atpp_part2_wrong']) ||
               ! empty($data['exam_atpp_part3_correct']) ||
               ! empty($data['exam_atpp_part3_wrong']) ||
               ! empty($data['exam_atpp_result']) ||
               ! empty($data['exam_tech_result']) ||
               ! empty($data['exam_result']) ||
               ! empty($data['exam_application_status']) ||
               ! empty($data['exam_remarks']);
    }

    public function show($id)
    {
        // Eager load the relationships
        $application = IntermediateApplication::with([
            'intermediateApplicant',
            'resourceSchedule.project',
        ])->findOrFail($id);

        $contactedByName = null;
        if ($application->contacted_by) {
            $contactedByUser = User::find($application->contacted_by);
            $contactedByName = $contactedByUser ? trim($contactedByUser->first_name . ' ' . $contactedByUser->last_name) : null;
        }

        // Get project name
        $projectName = null;
        if ($application->resourceSchedule && $application->resourceSchedule->project) {
            $projectName = $application->resourceSchedule->project->project_name;
        }

        $workExperiences = $application->intermediateApplicant?->workExperiences()
            ->where('is_deleted', '!=', 1)
            ->orWhereNull('is_deleted')
            ->get() ?? collect([]);

        $skills = $application->intermediateApplicant?->skills()
            ->where('is_deleted', '!=', 1)
            ->orWhereNull('is_deleted')
            ->get() ?? collect([]);

        $interviews = IntermediateInterviewer::with('interviewer')
            ->where('intermediate_application_id', $id)
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
                    'evaluation_score' => $interview->evaluation_score,
                    'evaluation_results' => $interview->evaluation_results,
                    'evaluation_remarks' => $interview->evaluation_remarks,
                ];
            });

        // Get available interviewers
        $availableInterviewers = User::actionInterviewers()
            ->where('active_status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($user) {
                return $user->toInterviewerOption();
            })
            ->values()
            ->toArray();

        return Inertia::render('intermediate/applications/Detail', [
            'application' => [
                'id' => $application->id,
                'position' => $application->position,
                'upload_pic' => $application->upload_pic,
                'upload_resume' => $application->upload_resume,
                'application_stage' => $application->application_stage,
                'project_name' => $projectName,
                'resource_schedule_id' => $application->resource_schedule_id,
                'paper_screening_status' => $application->paper_screening_status,
                'location_assignment' => $application->resourceSchedule->location_assignment ?? null,
                // Screening Questions
                'answer_q1' => $application->answer_q1,
                'answer_q2' => $application->answer_q2,
                'answer_q3' => $application->answer_q3,
                'answer_q4' => $application->answer_q4,

                // Availability & Preferences
                'availability_date' => $application->availability_date,
                'work_preference' => $application->work_preference,

                // Compensation
                'basic_pay' => $application->basic_pay,
                'bonuses' => $application->bonuses,
                'allowances' => $application->allowances,

                // Benefits
                'hmo' => $application->hmo,
                'leaves' => $application->leaves,
                'other_benefits' => $application->other_benefits,

                // Desired & Target
                'desired_salary_range' => $application->desired_salary_range,
                'targeted_company' => $application->targeted_company,
                'industry_experience' => $application->industry_experience,

                // Exam Details
                'exam_plan_date' => $application->exam_plan_date,
                'exam_actual_date' => $application->exam_actual_date,
                'exam_venue' => $application->exam_venue,
                'exam_application_status' => $application->exam_application_status,
                'exam_atpp_part1_correct' => $application->exam_atpp_part1_correct,
                'exam_atpp_part1_wrong' => $application->exam_atpp_part1_wrong,
                'exam_atpp_part2_correct' => $application->exam_atpp_part2_correct,
                'exam_atpp_part2_wrong' => $application->exam_atpp_part2_wrong,
                'exam_atpp_part3_correct' => $application->exam_atpp_part3_correct,
                'exam_atpp_part3_wrong' => $application->exam_atpp_part3_wrong,
                'exam_atpp_result' => $application->exam_atpp_result,
                'exam_tech_result' => $application->exam_tech_result,
                'exam_result' => $application->exam_result,
                'exam_remarks' => $application->exam_remarks,

                // Initial Interview Details
                'initial_interview_plan_date' => $application->initial_interview_plan_date,
                'initial_interview_actual_date' => $application->initial_interview_actual_date,
                'initial_interview_venue' => $application->initial_interview_venue,
                'initial_interview_final' => $application->initial_interview_final,
                'initial_interview_result' => $application->initial_interview_result,
                'initial_interview_application_status' => $application->initial_interview_application_status,
                'initial_interview_remarks' => $application->initial_interview_remarks,

                // Final Interview Details
                'final_interview_date' => $application->final_interview_date,
                'final_interview_final' => $application->final_interview_final,
                'final_interview_result' => $application->final_interview_result,
                'final_interview_application_status' => $application->final_interview_application_status,
                'final_interview_remarks' => $application->final_interview_remarks,

                // Job Offer Details
                'job_offer_schedule' => $application->job_offer_schedule,
                'job_offer_status' => $application->job_offer_status,
                'job_offer_remarks' => $application->job_offer_remarks,

                // Additional Information fields
                'remarks' => $application->remarks,
                'current_employer' => $application->current_employer,
                'contacted_by_name' => $contactedByName,
                'contacted_date' => $application->contacted_date 
                    ? Carbon::parse($application->contacted_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                    : null,
                'replied' => $application->replied,
                'replied_date' => $application->replied_date 
                    ? Carbon::parse($application->replied_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                    : null,
                'aws_start_date' => $application->aws_start_date 
                    ? Carbon::parse($application->aws_start_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                    : null,
                'aws_rank' => $application->aws_rank,
                'parked_to' => $application->parked_to,

                'applicant' => [
                    'first_name' => $application->intermediateApplicant->first_name ?? '',
                    'last_name' => $application->intermediateApplicant->last_name ?? '',
                    'middle_name' => $application->intermediateApplicant->middle_name ?? '',
                    'email_address' => $application->intermediateApplicant->email_address ?? '',
                    'contact_no' => $application->intermediateApplicant->contact_no ?? '',
                ],
            ],

            'userPermissions' => auth()->user()->permissions ?? 0,
            'userId' => auth()->id(),

            //  These should only appear ONCE
            'interviews' => $interviews,
            'availableInterviewers' => $availableInterviewers,
            'initialInterviewAssignments' => [],
            'finalInterviewAssignments' => [],

            // Work experiences
            'workExperiences' => $workExperiences->map(function ($experience) {
                return [
                    'id' => $experience->id,
                    'employer' => $experience->employer,
                    'job_title' => $experience->job_title,
                    'name_supervisor' => $experience->name_supervisor,
                    'work_description' => $experience->work_description,
                ];
            })->toArray(),

            // Skills
            'skills' => $skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'skill' => $skill->skill,
                ];
            })->toArray(),

            'examVenues' => [
                1 => 'Online',
                2 => 'Face to Face',
            ],

            // Permissions and flash messages
            'userPermissions' => auth()->user()->permissions ?? 0,
            'user_id' => auth()->id(),
            'hasMixedInitialInterviewResults' => false,
            'hasMixedFinalInterviewResults' => false,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Update paper screening status
     */
    public function updatePaperScreening(Request $request, $id)
    {
        $request->validate([
            'paper_screening_status' => 'required|integer|in:1,2,3,4,5,6',
        ]);

        DB::beginTransaction();

        try {
            $application = IntermediateApplication::findOrFail($id);

            $oldStatus = $application->paper_screening_status;
            $newStatus = $request->paper_screening_status;

            $application->paper_screening_status = $newStatus;

            // If status is Passed (5) or 1st Priority (2), move to For Exam stage
            if (in_array($newStatus, [2, 5]) && $application->application_stage == 1) {
                $application->application_stage = 2; // For Exam
            }

            $application->save();

            // Log the action
            Log::createLog(
                'Intermediate',
                "Paper screening status updated from {$oldStatus} to {$newStatus} for application #{$application->id} - {$application->intermediateApplicant?->email_address}",
                $application->intermediate_applicant_id
            );

            DB::commit();

            return response()->json([
                'message' => 'Paper screening status updated successfully!',
                'paper_screening_status' => $newStatus,
                'application_stage' => $application->application_stage,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Failed to update paper screening status: '.$e->getMessage());

            return response()->json([
                'message' => 'Failed to update paper screening status. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function getRoleLabel($permissions): string
    {
        return match ((int) $permissions) {
            1 => 'HR Staff',
            2 => 'HR Manager',
            3 => 'Admin',
            5 => 'BU Head',
            6 => 'Interviewer',
            default => 'Staff',
        };
    }

    public function availableInterviewers()
    {
        $interviewers = User::actionInterviewers()
            ->where('active_status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($user) {
                return $user->toInterviewerOption();
            });

        return response()->json(['interviewers' => $interviewers]);
    }

    public function edit($id)
    {
        $application = IntermediateApplication::with([
            'intermediateApplicant',
            'resourceSchedule.project',
        ])->findOrFail($id);

        // Get project name
        $projectName = null;
        if ($application->resourceSchedule && $application->resourceSchedule->project) {
            $projectName = $application->resourceSchedule->project->project_name;
        }

        // ✅ GET INTERVIEWS FOR THIS APPLICATION
        $interviews = IntermediateInterviewer::with('interviewer')
            ->where('intermediate_application_id', $id)
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
                    'interview_status' => $interview->interview_status,
                    'decline_reason' => $interview->decline_reason,
                    'pending_approval_notified_at' => $interview->pending_approval_notified_at,
                    'evaluation_score' => $interview->evaluation_score,
                    'evaluation_results' => $interview->evaluation_results,
                    'evaluation_remarks' => $interview->evaluation_remarks,
                ];
            });

        // Get available requisitions
        $sourceProjects = IntermediateRequisitionModel::query()
            ->whereHas('project')
            ->with('project')
            ->orderBy('created_time', 'desc')
            ->get()
            ->map(fn ($requisition) => [
                'value' => $requisition->id,
                'label' => $requisition->resource.' - '.
                    ($requisition->project->project_name ?? 'No Project').' ('.
                    $requisition->getLocationAssignmentLabelAttribute().')',
            ])
            ->toArray();

        return Inertia::render('intermediate/applications/Edit', [
            'application' => [
                'id' => $application->id,
                'intermediate_applicant_id' => $application->intermediate_applicant_id,
                'resource_schedule_id' => $application->resource_schedule_id,
                'position' => $application->position,
                'project_name' => $projectName,
                'upload_resume' => $application->upload_resume,
                'upload_pic' => $application->upload_pic,
                'paper_screening_status' => $application->paper_screening_status,

                // Screening
                'answer_q1' => $application->answer_q1,
                'answer_q2' => $application->answer_q2,
                'answer_q3' => $application->answer_q3,
                'answer_q4' => $application->answer_q4,

                // Availability & Preferences
                'availability_date' => $application->availability_date,
                'desired_salary_range' => $application->desired_salary_range,
                'work_preference' => $application->work_preference,

                // Compensation
                'basic_pay' => $application->basic_pay,
                'bonuses' => $application->bonuses,
                'hmo' => $application->hmo,
                'leaves' => $application->leaves,
                'allowances' => $application->allowances,
                'other_benefits' => $application->other_benefits,
                'targeted_company' => $application->targeted_company,
                'industry_experience' => $application->industry_experience,

                // Exam
                'exam_plan_date' => $application->exam_plan_date ? Carbon::parse($application->exam_plan_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s') : null,
                'exam_actual_date' => $application->exam_actual_date ? Carbon::parse($application->exam_actual_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s') : null,
                'exam_venue' => $application->exam_venue,
                'exam_atpp_part1_correct' => $application->exam_atpp_part1_correct,
                'exam_atpp_part1_wrong' => $application->exam_atpp_part1_wrong,
                'exam_atpp_part2_correct' => $application->exam_atpp_part2_correct,
                'exam_atpp_part2_wrong' => $application->exam_atpp_part2_wrong,
                'exam_atpp_part3_correct' => $application->exam_atpp_part3_correct,
                'exam_atpp_part3_wrong' => $application->exam_atpp_part3_wrong,
                'exam_atpp_result' => $application->exam_atpp_result,
                'exam_tech_result' => $application->exam_tech_result,
                'exam_result' => $application->exam_result,
                'exam_application_status' => $application->exam_application_status,
                'exam_remarks' => $application->exam_remarks,

                // Initial Interview
                'initial_interview_plan_date' => $application->initial_interview_plan_date
                ? Carbon::parse($application->initial_interview_plan_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                : null,
                'initial_interview_actual_date' => $application->initial_interview_actual_date
                ? Carbon::parse($application->initial_interview_actual_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                : null,
                'initial_interview_venue' => $application->initial_interview_venue,
                'initial_interview_final' => $application->initial_interview_final,
                'initial_interview_result' => $application->initial_interview_result,
                'initial_interview_application_status' => $application->initial_interview_application_status,
                'initial_interview_remarks' => $application->initial_interview_remarks,

                // Final Interview
                'final_interview_date' => $application->final_interview_date
                ? Carbon::parse($application->final_interview_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                : null,
                'final_interview_final' => $application->final_interview_final,
                'final_interview_result' => $application->final_interview_result,
                'final_interview_application_status' => $application->final_interview_application_status,
                'final_interview_remarks' => $application->final_interview_remarks,

                // Job Offer
                'job_offer_schedule' => $application->job_offer_schedule
                ? Carbon::parse($application->job_offer_schedule)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                : null,
                'job_offer_status' => $application->job_offer_status,
                'job_offer_remarks' => $application->job_offer_remarks,

                // Other
                'remarks' => $application->remarks,
                'current_employer' => $application->current_employer,
                // ✅ Contact & Response Tracking
                'contacted_by' => $application->contacted_by,
                'contacted_date' => $application->contacted_date 
                    ? Carbon::parse($application->contacted_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                    : null,
                'replied' => $application->replied,
                'replied_date' => $application->replied_date 
                    ? Carbon::parse($application->replied_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                    : null,
                
                // ✅ Current Employer
                'current_employer' => $application->current_employer,
                
                // ✅ AWS Fields
                'aws_start_date' => $application->aws_start_date 
                    ? Carbon::parse($application->aws_start_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
                    : null,
                'aws_rank' => $application->aws_rank,
                'parked_to' => $application->parked_to,

                'applicant' => [
                    'first_name' => $application->intermediateApplicant->first_name ?? '',
                    'last_name' => $application->intermediateApplicant->last_name ?? '',
                    'middle_name' => $application->intermediateApplicant->middle_name ?? '',
                    'email_address' => $application->intermediateApplicant->email_address ?? '',
                    'contact_no' => $application->intermediateApplicant->contact_no ?? '',
                ],
            ],

            'currentUserInfo' => [
                'id' => auth()->id(),
                'first_name' => auth()->user()->first_name,
                'last_name' => auth()->user()->last_name,
                'name' => auth()->user()->full_name ?? auth()->user()->name,
            ],

            'sourceProjects' => $sourceProjects,
            'examVenues' => [
                1 => 'Online',
                2 => 'Face to Face',
            ],
            'examStatuses' => config('constants.exam_statuses') ?? [
                1 => 'Pending',
                2 => 'Done',
                3 => 'Passed',
                4 => '2nd Priority (P2)',
                5 => 'Failed',
            ],
            'interviewStatuses' => config('constants.interview_statuses') ?? [
                1 => 'Pending',
                2 => 'Done',
                3 => 'Passed',
                4 => 'P2',
                5 => 'Failed',
            ],
            'jobOfferStatuses' => config('constants.job_offer_statuses') ?? [
                1 => 'Pending',
                2 => 'Done',
                3 => 'Accept',
                4 => 'Decline',
                5 => 'Withdraw',
                6 => 'Retracted',
            ],
            'userPermissions' => auth()->user()->permissions ?? 0,
            'userId' => auth()->id(),  // ✅ ADD THIS
            'interviews' => $interviews,  // ✅ ADD THIS
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $application = IntermediateApplication::findOrFail($id);

        // Validation rules
        $rules = [
            'resource_schedule_id' => 'nullable|exists:resource_requisitions,id',
            'position' => 'nullable|string|max:80',

            // Screening
            'answer_q1' => ['nullable'],
            'answer_q2' => ['nullable'],
            'answer_q3' => ['nullable'],
            'answer_q4' => ['nullable'],

            // Profile
            'availability_date' => ['nullable'],
            'desired_salary_range' => [
                'nullable',
                
            ],
            'work_preference' => ['nullable'],
            'basic_pay' => [
                'nullable',
            ],
            'bonuses' => ['nullable'],
            'hmo' => ['nullable'],
            'leaves' => ['nullable'],
            'allowances' => ['nullable'],
            'other_benefits' => ['nullable'],
            'targeted_company' => ['nullable'],
            'industry_experience' => ['nullable'],
            'current_employer' => ['nullable'],

            // Exam
            'exam_plan_date' => 'nullable|date',
            'exam_actual_date' => 'nullable|date',
            'exam_venue' => 'nullable|integer|in:1,2',
            'exam_atpp_part1_correct' => 'nullable|integer|min:0',
            'exam_atpp_part1_wrong' => 'nullable|integer|min:0',
            'exam_atpp_part2_correct' => 'nullable|integer|min:0',
            'exam_atpp_part2_wrong' => 'nullable|integer|min:0',
            'exam_atpp_part3_correct' => 'nullable|integer|min:0',
            'exam_atpp_part3_wrong' => 'nullable|integer|min:0',
            'exam_atpp_result' => 'nullable|numeric',
            'exam_tech_result' => 'nullable|numeric|min:0',
            'exam_result' => 'nullable|integer',
            'exam_application_status' => 'nullable|integer',
            'exam_remarks' => 'nullable|string',

            // Initial Interview
            'initial_interview_plan_date' => 'nullable|date',
            'initial_interview_actual_date' => 'nullable|date',
            'initial_interview_venue' => 'nullable|integer|in:1,2',
            'initial_interview_final' => 'nullable|numeric|min:0|max:5',
            'initial_interview_result' => 'nullable|integer',
            'initial_interview_application_status' => 'nullable|integer',
            'initial_interview_remarks' => 'nullable|string',

            // Final Interview
            'final_interview_date' => 'nullable|date',
            'final_interview_final' => 'nullable|numeric|min:0|max:5',
            'final_interview_result' => 'nullable|integer',
            'final_interview_application_status' => 'nullable|integer',
            'final_interview_remarks' => 'nullable|string',

            // Job Offer
            'job_offer_schedule' => 'nullable|date',
            'job_offer_status' => 'nullable|integer',
            'job_offer_remarks' => 'nullable|string',

            // Files
            'upload_resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'upload_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Other
            'remarks' => 'nullable|string',

            'current_employer' => ['nullable', 'string', 'max:80'],
            'contacted_by' => ['nullable', 'integer'],
            'contacted_date' => ['nullable', 'date'],
            'replied' => ['nullable', 'integer', 'in:0,1'],
            'replied_date' => ['nullable', 'date'],
            'aws_start_date' => ['nullable', 'date'],
            'aws_rank' => ['nullable', 'string', 'max:80'],
            'parked_to' => ['nullable', 'string', 'max:80'],
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // Handle date fields with timezone
            $dateFields = [
                'exam_plan_date', 'exam_actual_date',
                'initial_interview_plan_date', 'initial_interview_actual_date',
                'final_interview_date', 'job_offer_schedule','aws_start_date',  
                'contacted_date',  
                'replied_date',
            ];

            foreach ($dateFields as $field) {
                if (! empty($validated[$field])) {
                    $validated[$field] = Carbon::parse($validated[$field])->timezone('Asia/Manila');
                }
            }

            // Handle file uploads
            if ($request->hasFile('upload_resume')) {
                $resumePath = $request->file('upload_resume')->store('resumes', 'public');
                $validated['upload_resume'] = basename($resumePath);
            }

            if ($request->hasFile('upload_pic')) {
                $picPath = $request->file('upload_pic')->store('pictures', 'public');
                $validated['upload_pic'] = basename($picPath);
            }

            // Update source_project_id from requisition
            if (! empty($validated['resource_schedule_id'])) {
                $requisition = IntermediateRequisitionModel::with('project')
                    ->find($validated['resource_schedule_id']);
                $validated['source_project_id'] = $requisition?->project_id;
            }

            // ============================================
            // INITIAL INTERVIEW HANDLING
            // ============================================

            // Handle individual interviewer evaluation
            if ($request->has('initial_interview_id') && $request->initial_interview_id) {
                $initialInterview = IntermediateInterviewer::where('id', $request->initial_interview_id)
                    ->where('interviewer_id', auth()->id())
                    ->first();

                if ($initialInterview) {
                    $initialInterview->update([
                        'evaluation_score' => $request->initial_evaluation_score,
                        'evaluation_results' => $request->initial_evaluation_result,
                        'evaluation_remarks' => $request->initial_evaluation_remarks,
                    ]);
                }
            }

            // Always recalculate the average
            $this->recalculateInitialInterviewAverage($application);
            $application->refresh();

            // Check if ALL approved interviewers have submitted scores
            $approvedInitialInterviewers = IntermediateInterviewer::where('intermediate_application_id', $application->id)
                ->where('interview_type', 2)
                ->where('interview_status', 2)
                ->get();

            $totalApprovedInitial = $approvedInitialInterviewers->count();
            $submittedInitialScores = $approvedInitialInterviewers->filter(fn ($i) => ! is_null($i->evaluation_score) && $i->evaluation_score !== '')->count();

            if ($totalApprovedInitial > 0 && $submittedInitialScores === $totalApprovedInitial) {
                // All interviewers submitted - use auto-calculated score and result
                $validated['initial_interview_final'] = $application->initial_interview_final;
                $validated['initial_interview_result'] = $application->initial_interview_result;

                // ✅ KEY FIX: Check if status was manually changed by comparing with auto-calculated
                $autoCalculatedStatus = $application->initial_interview_application_status;
                $requestStatus = $request->input('initial_interview_application_status');

                // If the request status is different from auto-calculated, HR manually changed it
                if ($requestStatus !== null && $requestStatus != $autoCalculatedStatus) {
                    \Log::info('HR manually changed initial interview status', [
                        'auto_calculated' => $autoCalculatedStatus,
                        'manual' => $requestStatus,
                    ]);
                    $validated['initial_interview_application_status'] = $requestStatus;
                } else {
                    \Log::info('Using auto-calculated initial interview status', [
                        'status' => $autoCalculatedStatus,
                    ]);
                    $validated['initial_interview_application_status'] = $autoCalculatedStatus;
                }

                \Log::info('Initial interview values set', [
                    'final' => $validated['initial_interview_final'] ?? 'not set',
                    'result' => $validated['initial_interview_result'] ?? 'not set',
                    'status' => $validated['initial_interview_application_status'] ?? 'not set',
                ]);
            } else {
                \Log::info('Keeping manual initial interview values (not all interviewers submitted)');
            }

            // ============================================
            // FINAL INTERVIEW HANDLING
            // ============================================

            // Handle individual interviewer evaluation
            if ($request->has('final_interview_id') && $request->final_interview_id) {
                $finalInterview = IntermediateInterviewer::where('id', $request->final_interview_id)
                    ->where('interviewer_id', auth()->id())
                    ->first();

                if ($finalInterview) {
                    $finalInterview->update([
                        'evaluation_score' => $request->final_evaluation_score,
                        'evaluation_results' => $request->final_evaluation_result,
                        'evaluation_remarks' => $request->final_evaluation_remarks,
                    ]);
                }
            }

            // Always recalculate the average
            $this->recalculateFinalInterviewAverage($application);
            $application->refresh();

            // Check if ALL approved interviewers have submitted scores
            $approvedFinalInterviewers = IntermediateInterviewer::where('intermediate_application_id', $application->id)
                ->where('interview_type', 3)
                ->where('interview_status', 2)
                ->get();

            $totalApprovedFinal = $approvedFinalInterviewers->count();
            $submittedFinalScores = $approvedFinalInterviewers->filter(fn ($i) => ! is_null($i->evaluation_score) && $i->evaluation_score !== '')->count();

            if ($totalApprovedFinal > 0 && $submittedFinalScores === $totalApprovedFinal) {
                // All interviewers submitted - use auto-calculated score and result
                $validated['final_interview_final'] = $application->final_interview_final;
                $validated['final_interview_result'] = $application->final_interview_result;

                // ✅ KEY FIX: Check if status was manually changed
                $autoCalculatedStatus = $application->final_interview_application_status;
                $requestStatus = $request->input('final_interview_application_status');

                // If the request status is different from auto-calculated, HR manually changed it
                if ($requestStatus !== null && $requestStatus != $autoCalculatedStatus) {
                    \Log::info('HR manually changed final interview status', [
                        'auto_calculated' => $autoCalculatedStatus,
                        'manual' => $requestStatus,
                    ]);
                    $validated['final_interview_application_status'] = $requestStatus;
                } else {
                    \Log::info('Using auto-calculated final interview status', [
                        'status' => $autoCalculatedStatus,
                    ]);
                    $validated['final_interview_application_status'] = $autoCalculatedStatus;
                }

                \Log::info('Final interview values set', [
                    'final' => $validated['final_interview_final'] ?? 'not set',
                    'result' => $validated['final_interview_result'] ?? 'not set',
                    'status' => $validated['final_interview_application_status'] ?? 'not set',
                ]);
            } else {
                \Log::info('Keeping manual final interview values (not all interviewers submitted)');
            }

$newStage = $application->application_stage;

// Priority 1: Job Offer
if ($this->hasJobOfferDataFromRequest($request)) {
    $newStage = 5;
}
// Priority 2: Final Interview
elseif ($this->hasFinalInterviewDataFromRequest($request)) {
    $newStage = 4;
}
// Priority 3: Initial Interview or Exam - set based on which was JUST edited
else {
    // Check which stage's fields were actually CHANGED in this request
    $examFieldsChanged = $this->hasExamFieldsChanged($request, $application);
    $initialFieldsChanged = $this->hasInitialInterviewFieldsChanged($request, $application);
    $finalFieldsChanged = $this->hasFinalInterviewFieldsChanged($request, $application);

    \Log::info('Stage detection - fields changed', [
        'exam_changed' => $examFieldsChanged,
        'initial_changed' => $initialFieldsChanged,
        'final_changed' => $finalFieldsChanged,
    ]);

    if ($initialFieldsChanged && !$examFieldsChanged) {
        // Only Initial Interview was edited in this request
        $newStage = 3;
    } elseif ($examFieldsChanged && !$initialFieldsChanged) {
        // Only Exam was edited in this request
        $newStage = 2;
    } elseif ($initialFieldsChanged && $examFieldsChanged) {
        // Both edited - use the one that was edited most recently
        // Determine which has more fields changed
        $examChangeCount = $this->countChangedExamFields($request, $application);
        $initialChangeCount = $this->countChangedInitialFields($request, $application);
        
        if ($initialChangeCount >= $examChangeCount) {
            $newStage = 3; // Initial Interview
        } else {
            $newStage = 2; // Exam
        }
    }
    // If neither changed, keep current stage
}

if ($newStage != $application->application_stage) {
    $validated['application_stage'] = $newStage;
}

            $application->update($validated);

            // Log the action
            Log::createLog(
                'Intermediate',
                "Application #{$application->id} updated successfully.",
                $application->intermediate_applicant_id
            );

            DB::commit();

            return redirect()
                ->route('intermediate.applications.show', $application->id)
                ->with('success', 'Record updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the record. Please try again.');
        }
    }

    /**
 * Check if any Exam fields were actually changed in this request
 */
private function hasExamFieldsChanged(Request $request, $application): bool
{
    $fields = [
        'exam_plan_date',
        'exam_actual_date',
        'exam_venue',
        'exam_atpp_part1_correct',
        'exam_atpp_part1_wrong',
        'exam_atpp_part2_correct',
        'exam_atpp_part2_wrong',
        'exam_atpp_part3_correct',
        'exam_atpp_part3_wrong',
        'exam_atpp_result',
        'exam_tech_result',
        'exam_remarks',
    ];

    foreach ($fields as $field) {
        if ($request->has($field) && $request->$field != $application->$field) {
            \Log::info("Exam field changed: {$field}", [
                'old' => $application->$field,
                'new' => $request->$field,
            ]);
            return true;
        }
    }

    return false;
}

/**
 * Check if any Initial Interview fields were actually changed in this request
 */
private function hasInitialInterviewFieldsChanged(Request $request, $application): bool
{
    $fields = [
        'initial_interview_plan_date',
        'initial_interview_actual_date',
        'initial_interview_venue',
        'initial_interview_final',
        'initial_interview_remarks',
    ];

    foreach ($fields as $field) {
        if ($request->has($field) && $request->$field != $application->$field) {
            \Log::info("Initial field changed: {$field}", [
                'old' => $application->$field,
                'new' => $request->$field,
            ]);
            return true;
        }
    }

    return false;
}

/**
 * Check if any Final Interview fields were actually changed in this request
 */
private function hasFinalInterviewFieldsChanged(Request $request, $application): bool
{
    $fields = [
        'final_interview_date',
        'final_interview_final',
        'final_interview_remarks',
    ];

    foreach ($fields as $field) {
        if ($request->has($field) && $request->$field != $application->$field) {
            return true;
        }
    }

    return false;
}

/**
 * Count how many Exam fields were changed
 */
private function countChangedExamFields(Request $request, $application): int
{
    $fields = [
        'exam_plan_date',
        'exam_actual_date',
        'exam_venue',
        'exam_atpp_part1_correct',
        'exam_atpp_part1_wrong',
        'exam_atpp_part2_correct',
        'exam_atpp_part2_wrong',
        'exam_atpp_part3_correct',
        'exam_atpp_part3_wrong',
        'exam_atpp_result',
        'exam_tech_result',
        'exam_remarks',
    ];

    $count = 0;
    foreach ($fields as $field) {
        if ($request->has($field) && $request->$field != $application->$field) {
            $count++;
        }
    }

    return $count;
}

/**
 * Count how many Initial Interview fields were changed
 */
private function countChangedInitialFields(Request $request, $application): int
{
    $fields = [
        'initial_interview_plan_date',
        'initial_interview_actual_date',
        'initial_interview_venue',
        'initial_interview_final',
        'initial_interview_remarks',
    ];

    $count = 0;
    foreach ($fields as $field) {
        if ($request->has($field) && $request->$field != $application->$field) {
            $count++;
        }
    }

    return $count;
}

    /**
     * Recalculate initial interview average from ALL approved interviewer evaluations
     * ONLY if ALL approved interviewers have submitted their scores
     */
    private function recalculateInitialInterviewAverage($application): void
    {
        \Log::info('=== RECALCULATING INITIAL INTERVIEW AVERAGE ===');

        // ✅ Get ALL approved interviewers for this stage (interview_type = 2, status = 2)
        $approvedInterviewers = IntermediateInterviewer::where('intermediate_application_id', $application->id)
            ->where('interview_type', 2) // Initial Interview
            ->where('interview_status', 2) // Approved/Accepted
            ->get();

        $totalApproved = $approvedInterviewers->count();

        \Log::info("Total approved initial interviewers: {$totalApproved}");

        if ($totalApproved === 0) {
            $application->initial_interview_final = null;
            $application->initial_interview_result = null;
            $application->initial_interview_application_status = null;
            $application->save();

            return;
        }

        // ✅ Collect scores from approved interviewers
        $scores = [];
        $interviewersWithScores = 0;

        foreach ($approvedInterviewers as $interviewer) {
            \Log::info("Interviewer ID: {$interviewer->interviewer_id} | Score: ".($interviewer->evaluation_score ?? 'NULL'));

            if (! is_null($interviewer->evaluation_score) && $interviewer->evaluation_score !== '') {
                $scores[] = floatval($interviewer->evaluation_score);
                $interviewersWithScores++;
            }
        }

        \Log::info("Interviewers who submitted scores: {$interviewersWithScores} / {$totalApproved}");
        \Log::info('Scores array: '.json_encode($scores));

        // ✅ CRITICAL: Only calculate average if ALL approved interviewers have submitted
        if ($interviewersWithScores < $totalApproved) {
            \Log::info("⏳ Not all interviewers have submitted scores ({$interviewersWithScores}/{$totalApproved}) - clearing initial interview data");

            // Clear the fields until everyone has submitted
            $application->initial_interview_final = null;
            $application->initial_interview_result = null;
            $application->initial_interview_application_status = null;
            $application->save();

            return;
        }

        // ✅ All interviewers have submitted - calculate average
        if (count($scores) > 0) {
            $sum = array_sum($scores);
            $average = $sum / count($scores);
            $roundedAverage = round($average, 2);

            \Log::info("✅ All {$totalApproved} interviewers submitted!");
            \Log::info("Sum: {$sum}");
            \Log::info("Average: {$average}");
            \Log::info("Rounded Average: {$roundedAverage}");

            // Update the application with the average
            $application->initial_interview_final = $roundedAverage;

            // Apply the score mapping logic
            $result = $this->mapInterviewScore($roundedAverage);

            \Log::info('Mapped Result: '.$result['result']);
            \Log::info('Mapped Status: '.$result['status']);

            $application->initial_interview_result = $result['result'];
            $application->initial_interview_application_status = $result['status'];

            $application->save();

            \Log::info("✅ Initial interview average saved: {$roundedAverage}");
        } else {
            \Log::info('⚠️ No valid scores found');
        }
    }

    /**
     * Recalculate final interview average from ALL approved interviewer evaluations
     * ONLY if ALL approved interviewers have submitted their scores
     */
    private function recalculateFinalInterviewAverage($application): void
    {
        \Log::info('=== RECALCULATING FINAL INTERVIEW AVERAGE ===');

        // ✅ Get ALL approved interviewers for this stage (interview_type = 3, status = 2)
        $approvedInterviewers = IntermediateInterviewer::where('intermediate_application_id', $application->id)
            ->where('interview_type', 3) // Final Interview
            ->where('interview_status', 2) // Approved/Accepted
            ->get();

        $totalApproved = $approvedInterviewers->count();

        \Log::info("Total approved final interviewers: {$totalApproved}");

        if ($totalApproved === 0) {
            \Log::info('⚠️ No approved interviewers found - clearing final interview data');
            $application->final_interview_final = null;
            $application->final_interview_result = null;
            $application->final_interview_application_status = null;
            $application->save();

            return;
        }

        // ✅ Collect scores from approved interviewers
        $scores = [];
        $interviewersWithScores = 0;

        foreach ($approvedInterviewers as $interviewer) {
            \Log::info("Interviewer ID: {$interviewer->interviewer_id} | Score: ".($interviewer->evaluation_score ?? 'NULL'));

            if (! is_null($interviewer->evaluation_score) && $interviewer->evaluation_score !== '') {
                $scores[] = floatval($interviewer->evaluation_score);
                $interviewersWithScores++;
            }
        }

        \Log::info("Interviewers who submitted scores: {$interviewersWithScores} / {$totalApproved}");
        \Log::info('Scores array: '.json_encode($scores));

        // ✅ CRITICAL: Only calculate average if ALL approved interviewers have submitted
        if ($interviewersWithScores < $totalApproved) {
            \Log::info("⏳ Not all interviewers have submitted scores ({$interviewersWithScores}/{$totalApproved}) - clearing final interview data");

            // Clear the fields until everyone has submitted
            $application->final_interview_final = null;
            $application->final_interview_result = null;
            $application->final_interview_application_status = null;
            $application->save();

            return;
        }

        // ✅ All interviewers have submitted - calculate average
        if (count($scores) > 0) {
            $sum = array_sum($scores);
            $average = $sum / count($scores);
            $roundedAverage = round($average, 2);

            \Log::info("✅ All {$totalApproved} interviewers submitted!");
            \Log::info("Sum: {$sum}");
            \Log::info("Average: {$average}");
            \Log::info("Rounded Average: {$roundedAverage}");

            // Update the application with the average
            $application->final_interview_final = $roundedAverage;

            // Apply the score mapping logic
            $result = $this->mapInterviewScore($roundedAverage);

            \Log::info('Mapped Result: '.$result['result']);
            \Log::info('Mapped Status: '.$result['status']);

            $application->final_interview_result = $result['result'];
            $application->final_interview_application_status = $result['status'];

            $application->save();

            \Log::info("✅ Final interview average saved: {$roundedAverage}");
        } else {
            \Log::info('⚠️ No valid scores found');
        }
    }

    // ✅ CORRECTED: Backend interview score mapping
    private function mapInterviewScore($score): array
    {
        $num = floatval($score);

        \Log::info('Mapping score: '.$num);

        // No score or invalid = Pending
        if ($num == 0 || $num < 1.00) {
            \Log::info('→ Score is 0 or invalid: Pending');

            return [
                'result' => 1,  // Pending
                'status' => 1,   // Pending
            ];
        }

        // 1.00 - 2.00 = Passed (Priority 1)
        if ($num >= 1.00 && $num <= 2.00) {
            \Log::info('→ Score 1.00-2.00: Result=Passed(2), Status=Passed(3)');

            return [
                'result' => 2,  // Passed
                'status' => 3,   // Passed (Priority 1)
            ];
        }

        // 2.01 - 3.00 = Passed (Priority 2)
        if ($num >= 2.01 && $num <= 3.00) {
            \Log::info('→ Score 2.01-3.00: Result=Passed(2), Status=P2(4)');

            return [
                'result' => 2,  // Passed
                'status' => 4,   // P2 (Priority 2)
            ];
        }

        // 3.01 - 5.00 = Failed
        if ($num >= 3.01 && $num <= 5.00) {
            \Log::info('→ Score 3.01-5.00: Result=Failed(3), Status=Failed(5)');

            return [
                'result' => 3,  // Failed
                'status' => 5,   // Failed
            ];
        }

        \Log::info('→ Default: Pending');

        return [
            'result' => 1,  // Pending
            'status' => 1,   // Pending
        ];
    }

    public function print($id)
    {
        $application = IntermediateApplication::with([
            'intermediateApplicant',
            'resourceSchedule.project',
        ])->findOrFail($id);

        // Get project name
        $projectName = null;
        if ($application->resourceSchedule && $application->resourceSchedule->project) {
            $projectName = $application->resourceSchedule->project->project_name;
        }

        // Get work experiences (excluding deleted)
        $workExperiences = $application->intermediateApplicant?->workExperiences()
            ->where('is_deleted', '!=', 1)
            ->orWhereNull('is_deleted')
            ->get() ?? collect([]);

        // Get skills (excluding deleted)
        $skills = $application->intermediateApplicant?->skills()
            ->where('is_deleted', '!=', 1)
            ->orWhereNull('is_deleted')
            ->get() ?? collect([]);

        // Get interviews
        $interviews = IntermediateInterviewer::with('interviewer')
            ->where('intermediate_application_id', $id)
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'name' => $interview->interviewer->full_name ?? 'Unknown',
                    'role_label' => $interview->interviewer->role_label ?? 'Interviewer',
                    'interview_type' => $interview->interview_type,
                    'scheduled_date' => $interview->scheduled_date,
                    'status' => $interview->interview_status,
                    'decline_reason' => $interview->decline_reason,
                    'evaluation_score' => $interview->evaluation_score,
                    'evaluation_results' => $interview->evaluation_results,
                    'evaluation_remarks' => $interview->evaluation_remarks,
                ];
            });

        $examVenues = [
            1 => 'Online',
            2 => 'Face to Face',
        ];

        return view('intermediate.intermprint', compact(
            'application',
            'projectName',
            'workExperiences',
            'skills',
            'interviews',
            'examVenues'
        ));
    }

    /**
     * Check if Exam fields have data from request
     */
    private function hasExamDataFromRequest(Request $request): bool
    {
        return ! empty($request->exam_plan_date) ||
               ! empty($request->exam_actual_date) ||
               ! empty($request->exam_venue) ||
               ! empty($request->exam_atpp_part1_correct) ||
               ! empty($request->exam_atpp_part1_wrong) ||
               ! empty($request->exam_atpp_part2_correct) ||
               ! empty($request->exam_atpp_part2_wrong) ||
               ! empty($request->exam_atpp_part3_correct) ||
               ! empty($request->exam_atpp_part3_wrong) ||
               ! empty($request->exam_atpp_result) ||
               ! empty($request->exam_tech_result) ||
               ! empty($request->exam_application_status) ||
               ! empty($request->exam_remarks);
    }

    /**
     * Check if Initial Interview fields have data from request
     */
    private function hasInitialInterviewDataFromRequest(Request $request): bool
    {
        return ! empty($request->initial_interview_plan_date) ||
               ! empty($request->initial_interview_actual_date) ||
               ! empty($request->initial_interview_venue) ||
               ! empty($request->initial_interview_final) ||
               ! empty($request->initial_interview_remarks);
    }

    /**
     * Check if Final Interview fields have data from request
     */
    private function hasFinalInterviewDataFromRequest(Request $request): bool
    {
        return ! empty($request->final_interview_date) ||
               ! empty($request->final_interview_final) ||
               ! empty($request->final_interview_remarks);
    }

    /**
     * Check if Job Offer fields have data from request
     */
    private function hasJobOfferDataFromRequest(Request $request): bool
    {
        return ! empty($request->job_offer_schedule) ||
               ! empty($request->job_offer_status) ||
               ! empty($request->job_offer_remarks);
    }

    /**
     * Send email notifications for application
     */
    public function sendNotification(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|in:interviewer_pending_approval,applicant_failed,hr_recruiters_job_offer,applicant_schedule',
        ]);

        DB::beginTransaction();

        try {
            $application = IntermediateApplication::with(['intermediateApplicant'])->findOrFail($id);

            switch ($request->type) {
                case 'interviewer_pending_approval':
                    $this->sendPendingApprovalToInterviewers($application);
                    break;

                case 'applicant_schedule':
                    $this->sendApplicantSchedule($application);
                    break;

                case 'applicant_failed':
                    $this->sendApplicantFailedNotification($application);
                    break;

                case 'hr_recruiters_job_offer':
                    $this->sendJobOfferNotification($application);
                    break;

                default:
                    throw new \Exception('Invalid notification type');
            }

            DB::commit();

            return response()->json([
                'message' => 'Notification sent successfully.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to send notification: '.$e->getMessage(), [
                'application_id' => $id,
                'type' => $request->type,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to send notification: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send pending approval emails to interviewers
     */
    private function sendPendingApprovalToInterviewers($application)
    {
        $pendingInterviews = IntermediateInterviewer::with('interviewer')
            ->where('intermediate_application_id', $application->id)
            ->where('interview_status', 1)
            ->whereNull('pending_approval_notified_at')
            ->get();

        if ($pendingInterviews->isEmpty()) {
            throw new \Exception('No pending approval interviewers found.');
        }

        $sentCount = 0;
        $approvalLink = route('intermediate.applications.show', $application->id);

        $application->load('intermediateApplicant');

        foreach ($pendingInterviews as $interview) {
            if (! $interview->interviewer || empty($interview->interviewer->email_address)) {
                \Log::warning("Interviewer {$interview->interviewer_id} has no email address");

                continue;
            }

            $applicantName = trim(
                ($application->intermediateApplicant->first_name ?? '').' '.
                ($application->intermediateApplicant->last_name ?? '')
            ) ?: 'Applicant';

            Mail::to($interview->interviewer->email_address)
                ->send(new ATS0004Mail(
                    $application,
                    $interview,
                    $approvalLink,
                    $applicantName
                ));

            //  Wait 500ms to stay under Mailtrap’s 2‑5 emails/10s limit
            sleep(10);

            $interview->pending_approval_notified_at = now();
            $interview->save();
            $sentCount++;

            \Log::info("Email sent to: {$interview->interviewer->email_address} for application {$application->id}");
        }

        if ($sentCount === 0) {
            throw new \Exception('No valid email addresses found for pending interviewers.');
        }
    }

    /**
     * Send applicant failed notification
     */
    private function sendApplicantFailedNotification($application)
    {
        $applicant = $application->intermediateApplicant;

        if (! $applicant || empty($applicant->email_address)) {
            throw new \Exception('Applicant has no email address.');
        }

        // Determine which stage failed based on application_status
        $failedStage = null;
        if ($application->final_interview_application_status == 5) {
            $failedStage = 'final interview';
        } elseif ($application->initial_interview_application_status == 5) {
            $failedStage = 'initial interview';
        } elseif ($application->exam_application_status == 5) {
            $failedStage = 'exam';
        } else {
            throw new \Exception('No failed stage found for this application.');
        }

        Mail::to($applicant->email_address)
            ->send(new ATS0009Mail(
                application: $application,
                failedStage: $failedStage
            ));

        \Log::info("Applicant failed notification sent to {$applicant->email_address} for {$failedStage} - application {$application->id}");
    }

    /**
     * Send job offer notification to HR recruiters
     */
    private function sendJobOfferNotification($application)
    {
        if (! $application->job_offer_schedule) {
            throw new \Exception('No job offer schedule set for this application.');
        }

        $hrRecruiters = User::whereIn('permissions', [3])
            ->where('active_status', 1)
            ->whereNotNull('email_address')
            ->get();

        if ($hrRecruiters->isEmpty()) {
            throw new \Exception('No HR recruiters found.');
        }

        $link = route('intermediate.applications.show', $application->id);
        $sentCount = 0;

        foreach ($hrRecruiters as $recruiter) {
            Mail::to($recruiter->email_address)
                ->send(new ATS0007Mail($application, $link));

            $sentCount++;

            sleep(10); // Rate limit for Mailtrap
        }

        \Log::info("Job offer notification sent to {$sentCount} HR recruiters for application {$application->id}");
    }

    private function sendApplicantSchedule($application)
    {
        $applicant = $application->intermediateApplicant;

        if (! $applicant || empty($applicant->email_address)) {
            throw new \Exception('Applicant has no email address.');
        }

        $applicantName = trim($applicant->first_name.' '.$applicant->last_name) ?: 'Applicant';

        $stage = (int) $application->application_stage;

        // Check if stage is already passed
        $alreadyPassed = match ($stage) {
            2 => $application->exam_result == 2,
            3 => $application->initial_interview_result == 2,
            4 => $application->final_interview_result == 2,
            default => false,
        };

        if ($alreadyPassed) {
            throw new \Exception('Applicant has already passed this stage. Cannot send schedule.');
        }

        // Determine stage details
        $stageData = match ($stage) {
            2 => [
                'label' => 'exam',
                'plan_date' => $application->exam_plan_date,
                'venue_field' => $application->exam_venue,
            ],
            3 => [
                'label' => 'initial interview',
                'plan_date' => $application->initial_interview_plan_date,
                'venue_field' => $application->initial_interview_venue,
            ],
            4 => [
                'label' => 'final interview',
                'plan_date' => $application->final_interview_date,
                'venue_field' => null,
            ],
            default => throw new \Exception('Application is not in a schedulable stage.'),
        };

        // Venue label
        $venueLabel = match ((int) $stageData['venue_field']) {
            1 => 'online',
            2 => 'face-to-face',
            default => '(venue to be announced)',
        };

        // Format date
        $scheduledDate = $stageData['plan_date']
            ? Carbon::parse($stageData['plan_date'])->format('F d, Y h:i A')
            : 'To be announced';

        Mail::to($applicant->email_address)
            ->send(new ATS0008Mail(
                applicantName: $applicantName,
                venue: $venueLabel,
                stage: $stageData['label'],
                scheduledDate: $scheduledDate
            ));

        \Log::info("Applicant schedule email sent to {$applicant->email_address} for {$stageData['label']} - application {$application->id}");
    }
}
