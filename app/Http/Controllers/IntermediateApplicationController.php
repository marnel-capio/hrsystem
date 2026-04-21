<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntermediateApplicationRequest;
use App\Models\IntermediateApplicant;
use App\Models\IntermediateApplication;
use App\Models\IntermediateRequisitionModel;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        ]);
    }

    public function store(StoreIntermediateApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // ✅ DEFAULT VALUES (non-null)
            $data['paper_screening_status'] = 1;  // Screening Pending
            $data['application_stage'] = 1;       // New/Screening

            // ✅ UPGRADE if advanced data exists
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

            return redirect()->route('intermediate.applications.index')
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($resumePath)) {
                \Storage::disk('public')->delete($resumePath);
            }
            if (isset($picPath)) {
                \Storage::disk('public')->delete($picPath);
            }

            return back()->with('error', config('errors.transaction_failed.errorMessage'));
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
}
