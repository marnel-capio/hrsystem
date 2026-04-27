<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class IntermediateApplication extends Model
{
    use HasFactory;

    protected $table = 'intermediate_applicants_applications';

    public $timestamps = true;

    const CREATED_AT = 'created_time';

    const UPDATED_AT = 'updated_time';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        // Core
        'application_stage',
        'intermediate_applicant_id',
        'resource_schedule_id',
        'fy_week',
        'position',
        'source_project_id',

        // Files
        'upload_resume',
        'upload_pic',

        // Screening answers
        'answer_q1', 'answer_q2', 'answer_q3', 'answer_q4',

        // Profile / preference
        'availability_date',
        'desired_salary_range',
        'work_preference',
        'basic_pay',
        'bonuses',
        'hmo',
        'leaves',
        'allowances',
        'other_benefits',
        'targeted_company',
        'industry_experience',
        'current_employer',
        'asking_rate',
        'site_assignment',

        // Screening
        'paper_screening_status',

        // Exam (new structure)
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
        'exam_result',
        'exam_application_status',
        'exam_remarks',

        // Initial Interview
        'initial_interview_plan_date',
        'initial_interview_actual_date',
        'initial_interview_venue',
        'initial_interview_final',
        'initial_interview_result',
        'initial_interview_application_status',
        'initial_interview_remarks',

        // Final Interview
        'final_interview_date',
        'final_interview_final',
        'final_interview_result',
        'final_interview_application_status',
        'final_interview_remarks',

        // Job offer
        'job_offer_schedule',
        'job_offer_status',
        'job_offer_remarks',

        // Post offer
        'aws_start_date',
        'aws_rank',
        'parked_to',
        'reason_by_category',
        'reason_for_decline',

        'remarks',

        // Tracking
        'contacted_by',
        'contacted_date',
        'replied',
        'replied_date',

        'created_by',
        'updated_by',
    ];

    /**
     * Casting
     */
    protected $casts = [
        'application_stage' => 'integer',

        'answer_q1' => 'boolean',
        'answer_q2' => 'boolean',
        'answer_q3' => 'boolean',
        'answer_q4' => 'boolean',

        'contacted_date' => 'datetime',
        'replied_date' => 'datetime',

        'exam_plan_date' => 'datetime',
        'exam_actual_date' => 'datetime',
        'initial_interview_plan_date' => 'datetime',
        'initial_interview_actual_date' => 'datetime',
        'final_interview_date' => 'datetime',
        'job_offer_schedule' => 'datetime',
        'aws_start_date' => 'datetime',

        'paper_screening_status' => 'integer',
        'exam_result' => 'integer',
        'exam_application_status' => 'integer',
        'initial_interview_result' => 'integer',
        'initial_interview_application_status' => 'integer',
        'final_interview_result' => 'integer',
        'final_interview_application_status' => 'integer',
        'job_offer_status' => 'integer',
        'replied' => 'integer',

        'exam_atpp_result' => 'decimal:2',
        'exam_tech_result' => 'decimal:2',
        'initial_interview_final' => 'decimal:2',
        'final_interview_final' => 'decimal:2',
    ];

    protected $appends = [
        'fullApplicantName',
        'projectName',
        'stageLabel',
    ];

    /**
     * Relationships
     */
    public function intermediateApplicant(): BelongsTo
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(IntermediateProjectModel::class, 'source_project_id');
    }

    public function contactedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function resourceSchedule(): BelongsTo
    {
        return $this->belongsTo(IntermediateRequisitionModel::class, 'resource_schedule_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(IntermediateInterviewer::class, 'intermediate_application_id');
    }

    /**
     * Accessors
     */
    protected function fullApplicantName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->intermediateApplicant
                ? "{$this->intermediateApplicant->first_name} {$this->intermediateApplicant->last_name}"
                : 'Unknown'
        );
    }

    protected function projectName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->resourceSchedule?->project?->project_name
        );
    }

    protected function stageLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->application_stage) {
                1 => 'New',
                2 => 'For Exam',
                3 => 'For Initial Interview',
                4 => 'For Final Interview',
                5 => 'For Job Offer',
                default => 'Unknown'
            }
        );
    }

    /**
     * Get the project name through resource schedule
     */
    public function getProjectNameAttribute(): string
    {
        return $this->resourceSchedule?->project?->project_name ?? 'N/A';
    }

    public function getFullApplicantNameAttribute()
    {
        return $this->intermediateApplicant?->full_name ?? 'Unknown';
    }

    public function getStageLabelAttribute()
    {
        return match ($this->application_stage) {
            1 => 'New',
            2 => 'For Exam',
            3 => 'For Initial Interview',
            4 => 'For Final Interview',
            5 => 'For Job Offer',
            default => 'Unknown'
        };
    }

    /**
     * Get the contacted by user's full name
     */
    public function getContactedByNameAttribute(): ?string
    {
        if (! $this->contacted_by) {
            return null;
        }

        $user = User::find($this->contacted_by);

        return $user ? trim($user->first_name.' '.$user->last_name) : null;
    }

    /**
     * Get work experiences for the applicant
     */
    public function getWorkExperiencesAttribute(): Collection
    {
        $applicant = $this->intermediateApplicant;

        if (! $applicant) {
            return collect([]);
        }

        return $applicant->workExperiences()
            ->where(function ($query) {
                $query->where('is_deleted', '!=', 1)
                    ->orWhereNull('is_deleted');
            })
            ->get()
            ->map(function ($experience) {
                return [
                    'id' => $experience->id,
                    'employer' => $experience->employer,
                    'job_title' => $experience->job_title,
                    'name_supervisor' => $experience->name_supervisor,
                    'work_description' => $experience->work_description,
                ];
            });
    }

    /**
     * Get skills for the applicant
     */
    public function getSkillsAttribute(): Collection
    {
        $applicant = $this->intermediateApplicant;

        if (! $applicant) {
            return collect([]);
        }

        return $applicant->skills()
            ->where(function ($query) {
                $query->where('is_deleted', '!=', 1)
                    ->orWhereNull('is_deleted');
            })
            ->get()
            ->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'skill' => $skill->skill,
                ];
            });
    }

    /**
     * Get formatted interviews
     */
    public function getFormattedInterviewsAttribute(): Collection
    {
        return $this->interviews()
            ->with('interviewer')
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
    }

    /**
     * Format contacted date to Manila timezone
     */
    public function getFormattedContactedDateAttribute(): ?string
    {
        return $this->contacted_date
            ? Carbon::parse($this->contacted_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
            : null;
    }

    /**
     * Format replied date to Manila timezone
     */
    public function getFormattedRepliedDateAttribute(): ?string
    {
        return $this->replied_date
            ? Carbon::parse($this->replied_date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
            : null;
    }

    /**
     * Get the full application detail data for the show view
     */
    public function getDetailDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'upload_pic' => $this->upload_pic,
            'upload_resume' => $this->upload_resume,
            'application_stage' => $this->application_stage,
            'project_name' => $this->project_name,
            'paper_screening_status' => $this->paper_screening_status,
            'location_assignment' => $this->resourceSchedule->location_assignment ?? null,

            // Screening Questions
            'answer_q1' => $this->answer_q1,
            'answer_q2' => $this->answer_q2,
            'answer_q3' => $this->answer_q3,
            'answer_q4' => $this->answer_q4,

            // Availability & Preferences
            'availability_date' => $this->availability_date,
            'work_preference' => $this->work_preference,

            // Compensation
            'basic_pay' => $this->basic_pay,
            'bonuses' => $this->bonuses,
            'allowances' => $this->allowances,

            // Benefits
            'hmo' => $this->hmo,
            'leaves' => $this->leaves,
            'other_benefits' => $this->other_benefits,

            // Desired & Target
            'desired_salary_range' => $this->desired_salary_range,
            'targeted_company' => $this->targeted_company,
            'industry_experience' => $this->industry_experience,

            // Exam Details
            'exam_plan_date' => $this->exam_plan_date,
            'exam_actual_date' => $this->exam_actual_date,
            'exam_venue' => $this->exam_venue,
            'exam_application_status' => $this->exam_application_status,
            'exam_atpp_part1_correct' => $this->exam_atpp_part1_correct,
            'exam_atpp_part1_wrong' => $this->exam_atpp_part1_wrong,
            'exam_atpp_part2_correct' => $this->exam_atpp_part2_correct,
            'exam_atpp_part2_wrong' => $this->exam_atpp_part2_wrong,
            'exam_atpp_part3_correct' => $this->exam_atpp_part3_correct,
            'exam_atpp_part3_wrong' => $this->exam_atpp_part3_wrong,
            'exam_atpp_result' => $this->exam_atpp_result,
            'exam_tech_result' => $this->exam_tech_result,
            'exam_result' => $this->exam_result,
            'exam_remarks' => $this->exam_remarks,

            // Initial Interview Details
            'initial_interview_plan_date' => $this->initial_interview_plan_date,
            'initial_interview_actual_date' => $this->initial_interview_actual_date,
            'initial_interview_venue' => $this->initial_interview_venue,
            'initial_interview_final' => $this->initial_interview_final,
            'initial_interview_result' => $this->initial_interview_result,
            'initial_interview_application_status' => $this->initial_interview_application_status,
            'initial_interview_remarks' => $this->initial_interview_remarks,

            // Final Interview Details
            'final_interview_date' => $this->final_interview_date,
            'final_interview_final' => $this->final_interview_final,
            'final_interview_result' => $this->final_interview_result,
            'final_interview_application_status' => $this->final_interview_application_status,
            'final_interview_remarks' => $this->final_interview_remarks,

            // Job Offer Details
            'job_offer_schedule' => $this->job_offer_schedule,
            'job_offer_status' => $this->job_offer_status,
            'job_offer_remarks' => $this->job_offer_remarks,

            // Additional Information fields
            'remarks' => $this->remarks,
            'reason_for_decline' => $this->reason_for_decline,
            'reason_by_category' => $this->reason_by_category,
            'parked_to' => $this->parked_to,
            'aws_rank' => $this->aws_rank,
            'aws_start_date' => $this->aws_start_date,

            // Tracking fields
            'contacted_by' => $this->contacted_by,
            'contacted_date' => $this->formatted_contacted_date,
            'replied' => $this->replied,
            'replied_date' => $this->formatted_replied_date,
            'current_employer' => $this->current_employer,
            'contacted_by_name' => $this->contacted_by_name,

            // Applicant details
            'applicant' => [
                'first_name' => $this->intermediateApplicant->first_name ?? '',
                'last_name' => $this->intermediateApplicant->last_name ?? '',
                'middle_name' => $this->intermediateApplicant->middle_name ?? '',
                'email_address' => $this->intermediateApplicant->email_address ?? '',
                'contact_no' => $this->intermediateApplicant->contact_no ?? '',
            ],
        ];
    }

    /**
     * Get the full show data including relationships
     */
    public static function getShowData(int $id): array
    {
        $application = self::with([
            'intermediateApplicant',
            'resourceSchedule.project',
        ])->findOrFail($id);

        return [
            'application' => $application->detail_data,
            'userPermissions' => auth()->user()->permissions ?? 0,
            'userId' => auth()->id(),
            'interviews' => $application->formatted_interviews,
            'availableInterviewers' => self::getAvailableInterviewers(),
            'initialInterviewAssignments' => [],
            'finalInterviewAssignments' => [],
            'workExperiences' => $application->work_experiences->toArray(),
            'skills' => $application->skills->toArray(),
            'examVenues' => [
                1 => 'Online',
                2 => 'Face to Face',
            ],
            'userPermissions' => auth()->user()->permissions ?? 0,
            'user_id' => auth()->id(),
            'hasMixedInitialInterviewResults' => false,
            'hasMixedFinalInterviewResults' => false,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ];
    }

    /**
     * Get available interviewers
     */
    public static function getAvailableInterviewers(): array
    {
        return User::actionInterviewers()
            ->where('active_status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($user) {
                return $user->toInterviewerOption();
            })
            ->values()
            ->toArray();
    }

    /**
     * Scopes
     */
    public function scopeByStage(Builder $query, int $stage): Builder
    {
        return $query->where('application_stage', $stage);
    }

    public function scopeByProject(Builder $query, int $projectId): Builder
    {
        return $query->where('source_project_id', $projectId);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('intermediateApplicant', function ($sub) use ($search) {
                $sub->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email_address', 'like', "%{$search}%");
            })
                ->orWhere('position', 'like', "%{$search}%")
                ->orWhereHas('resourceSchedule.project', fn ($p) => $p->where('project_name', 'like', "%{$search}%")
                );
        });
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_time', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Dashboard stats
     */
    public static function dashboardStats(): array
    {
        return [
            'total' => self::count(),
            'by_stage' => self::selectRaw('application_stage, COUNT(*) as count')
                ->groupBy('application_stage')
                ->pluck('count', 'application_stage')
                ->toArray(),
            'passed_exams' => self::where('exam_result', 2)->count(),
            'job_offers' => self::where('job_offer_status', 3)->count(),
        ];
    }

    /**
     * Boot
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? 1;
            $model->updated_by = auth()->id() ?? 1;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? 1;
        });
    }

    /**
     * Date serialization
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::instance($date)
            ->timezone('Asia/Manila')
            ->format('Y-m-d H:i:s');
    }

    /**
     * Status label helpers
     */
    private function getStatusLabel(int $status): string
    {
        return match ($status) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Passed',
            4 => 'P2',
            5 => 'Failed',
            default => 'N/A'
        };
    }

    /**
     * Get the edit form data for the application
     */
    public function getEditDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'intermediate_applicant_id' => $this->intermediate_applicant_id,
            'resource_schedule_id' => $this->resource_schedule_id,
            'position' => $this->position,
            'project_name' => $this->project_name,
            'upload_resume' => $this->upload_resume,
            'upload_pic' => $this->upload_pic,
            'paper_screening_status' => $this->paper_screening_status,

            // Screening
            'answer_q1' => $this->answer_q1,
            'answer_q2' => $this->answer_q2,
            'answer_q3' => $this->answer_q3,
            'answer_q4' => $this->answer_q4,

            // Availability & Preferences
            'availability_date' => $this->availability_date,
            'desired_salary_range' => $this->desired_salary_range,
            'work_preference' => $this->work_preference,

            // Compensation
            'basic_pay' => $this->basic_pay,
            'bonuses' => $this->bonuses,
            'hmo' => $this->hmo,
            'leaves' => $this->leaves,
            'allowances' => $this->allowances,
            'other_benefits' => $this->other_benefits,
            'targeted_company' => $this->targeted_company,
            'industry_experience' => $this->industry_experience,

            // Exam
            'exam_plan_date' => $this->formatDateForEdit($this->exam_plan_date),
            'exam_actual_date' => $this->formatDateForEdit($this->exam_actual_date),
            'exam_venue' => $this->exam_venue,
            'exam_atpp_part1_correct' => $this->exam_atpp_part1_correct,
            'exam_atpp_part1_wrong' => $this->exam_atpp_part1_wrong,
            'exam_atpp_part2_correct' => $this->exam_atpp_part2_correct,
            'exam_atpp_part2_wrong' => $this->exam_atpp_part2_wrong,
            'exam_atpp_part3_correct' => $this->exam_atpp_part3_correct,
            'exam_atpp_part3_wrong' => $this->exam_atpp_part3_wrong,
            'exam_atpp_result' => $this->exam_atpp_result,
            'exam_tech_result' => $this->exam_tech_result,
            'exam_result' => $this->exam_result,
            'exam_application_status' => $this->exam_application_status,
            'exam_remarks' => $this->exam_remarks,

            // Initial Interview
            'initial_interview_plan_date' => $this->formatDateForEdit($this->initial_interview_plan_date),
            'initial_interview_actual_date' => $this->formatDateForEdit($this->initial_interview_actual_date),
            'initial_interview_venue' => $this->initial_interview_venue,
            'initial_interview_final' => $this->initial_interview_final,
            'initial_interview_result' => $this->initial_interview_result,
            'initial_interview_application_status' => $this->initial_interview_application_status,
            'initial_interview_remarks' => $this->initial_interview_remarks,

            // Final Interview
            'final_interview_date' => $this->formatDateForEdit($this->final_interview_date),
            'final_interview_final' => $this->final_interview_final,
            'final_interview_result' => $this->final_interview_result,
            'final_interview_application_status' => $this->final_interview_application_status,
            'final_interview_remarks' => $this->final_interview_remarks,

            // Job Offer
            'job_offer_schedule' => $this->formatDateForEdit($this->job_offer_schedule),
            'job_offer_status' => $this->job_offer_status,
            'job_offer_remarks' => $this->job_offer_remarks,
            'aws_start_date' => $this->formatDateForEdit($this->aws_start_date),
            'aws_rank' => $this->aws_rank,
            'parked_to' => $this->parked_to,

            // Other
            'remarks' => $this->remarks,
            'contacted_by' => $this->contacted_by,
            'contacted_date' => $this->formatDateForEdit($this->contacted_date),
            'replied' => $this->replied,
            'replied_date' => $this->formatDateForEdit($this->replied_date),
            'current_employer' => $this->current_employer,

            'applicant' => [
                'first_name' => $this->intermediateApplicant->first_name ?? '',
                'last_name' => $this->intermediateApplicant->last_name ?? '',
                'middle_name' => $this->intermediateApplicant->middle_name ?? '',
                'email_address' => $this->intermediateApplicant->email_address ?? '',
                'contact_no' => $this->intermediateApplicant->contact_no ?? '',
            ],
        ];
    }

    /**
     * Format date for edit form (Manila timezone)
     */
    private function formatDateForEdit($date): ?string
    {
        return $date
            ? Carbon::parse($date)->timezone('Asia/Manila')->format('Y-m-d H:i:s')
            : null;
    }

    /**
     * Get the full edit form data including interviews and dropdowns
     */
    public static function getEditData(int $id): array
    {
        $application = self::with([
            'intermediateApplicant',
            'resourceSchedule.project',
        ])->findOrFail($id);

        return [
            'application' => $application->edit_data,
            'sourceProjects' => self::getRequisitionsForDropdown(),
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
            'userId' => auth()->id(),
            'interviews' => $application->formatted_interviews,
            'auth' => [
                'user' => [
                    'id' => auth()->id(),
                    'first_name' => auth()->user()->first_name,
                    'last_name' => auth()->user()->last_name,
                    'name' => auth()->user()->full_name ?? auth()->user()->name,
                ],
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ];
    }

    /**
     * Get requisitions formatted for dropdown selection
     */
    public static function getRequisitionsForDropdown(): array
    {
        return IntermediateRequisitionModel::query()
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
    }

    /**
     * Update the application with validated data
     */
    public function updateApplication(array $validated, $request = null): void
    {
        // Handle date fields with timezone
        $this->processDateFields($validated);

        // Handle file uploads
        if ($request) {
            $this->processFileUploads($validated, $request);
        }

        // Update source_project_id from requisition
        $this->updateProjectFromRequisition($validated);

        // Handle individual interviewer evaluations
        if ($request) {
            $this->handleInterviewerEvaluations($request);
        }

        // Process initial interview
        $this->processInitialInterview($validated, $request);

        // Process final interview
        $this->processFinalInterview($validated, $request);

        // Determine and set application stage
        $validated['application_stage'] = $this->determineApplicationStage($request ?? request());

        // Update the application
        $this->update($validated);

        // Log the action
        $this->logUpdate();
    }

    /**
     * Process date fields to Manila timezone
     */
    private function processDateFields(array &$validated): void
    {
        $dateFields = [
            'exam_plan_date', 'exam_actual_date',
            'initial_interview_plan_date', 'initial_interview_actual_date',
            'final_interview_date', 'job_offer_schedule', 'aws_start_date',
            'contacted_date', 'replied_date',
        ];

        foreach ($dateFields as $field) {
            if (! empty($validated[$field])) {
                $validated[$field] = Carbon::parse($validated[$field])
                    ->timezone('Asia/Manila');
            }
        }
    }

    /**
     * Handle file uploads
     */
    private function processFileUploads(array &$validated, $request): void
    {
        if ($request->hasFile('upload_resume')) {
            $resumePath = $request->file('upload_resume')->store('resumes', 'public');
            $validated['upload_resume'] = basename($resumePath);
        }

        if ($request->hasFile('upload_pic')) {
            $picPath = $request->file('upload_pic')->store('pictures', 'public');
            $validated['upload_pic'] = basename($picPath);
        }
    }

    /**
     * Update source_project_id from requisition
     */
    private function updateProjectFromRequisition(array &$validated): void
    {
        if (! empty($validated['resource_schedule_id'])) {
            $requisition = IntermediateRequisitionModel::with('project')
                ->find($validated['resource_schedule_id']);
            $validated['source_project_id'] = $requisition?->project_id;
        }
    }

    /**
     * Handle individual interviewer evaluations
     */
    private function handleInterviewerEvaluations($request): void
    {
        // Initial interview evaluation
        if ($request->has('initial_interview_id') && $request->initial_interview_id) {
            $this->interviews()
                ->where('id', $request->initial_interview_id)
                ->where('interviewer_id', auth()->id())
                ->first()
                ?->update([
                    'evaluation_score' => $request->initial_evaluation_score,
                    'evaluation_results' => $request->initial_evaluation_result,
                    'evaluation_remarks' => $request->initial_evaluation_remarks,
                ]);
        }

        // Final interview evaluation
        if ($request->has('final_interview_id') && $request->final_interview_id) {
            $this->interviews()
                ->where('id', $request->final_interview_id)
                ->where('interviewer_id', auth()->id())
                ->first()
                ?->update([
                    'evaluation_score' => $request->final_evaluation_score,
                    'evaluation_results' => $request->final_evaluation_result,
                    'evaluation_remarks' => $request->final_evaluation_remarks,
                ]);
        }
    }

    /**
     * Process initial interview - recalculate averages and determine status
     */
    private function processInitialInterview(array &$validated, $request): void
    {
        $this->recalculateInitialInterviewAverage();
        $this->refresh();

        $approvedInterviewers = $this->interviews()
            ->where('interview_type', 2)
            ->where('interview_status', 2)
            ->get();

        $totalApproved = $approvedInterviewers->count();
        $submittedScores = $approvedInterviewers->filter(
            fn ($i) => ! is_null($i->evaluation_score) && $i->evaluation_score !== ''
        )->count();

        if ($totalApproved > 0 && $submittedScores === $totalApproved) {
            $validated['initial_interview_final'] = $this->initial_interview_final;
            $validated['initial_interview_result'] = $this->initial_interview_result;

            $autoCalculatedStatus = $this->initial_interview_application_status;
            $requestStatus = $request ? $request->input('initial_interview_application_status') : null;

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
        }
    }

    /**
     * Process final interview - recalculate averages and determine status
     */
    private function processFinalInterview(array &$validated, $request): void
    {
        $this->recalculateFinalInterviewAverage();
        $this->refresh();

        $approvedInterviewers = $this->interviews()
            ->where('interview_type', 3)
            ->where('interview_status', 2)
            ->get();

        $totalApproved = $approvedInterviewers->count();
        $submittedScores = $approvedInterviewers->filter(
            fn ($i) => ! is_null($i->evaluation_score) && $i->evaluation_score !== ''
        )->count();

        if ($totalApproved > 0 && $submittedScores === $totalApproved) {
            $validated['final_interview_final'] = $this->final_interview_final;
            $validated['final_interview_result'] = $this->final_interview_result;

            $autoCalculatedStatus = $this->final_interview_application_status;
            $requestStatus = $request ? $request->input('final_interview_application_status') : null;

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
        }
    }

    /**
     * Recalculate initial interview average from ALL approved interviewer evaluations
     * ONLY if ALL approved interviewers have submitted their scores
     */
    private function recalculateInitialInterviewAverage($application): void
    {
        // ✅ Get ALL approved interviewers for this stage (interview_type = 2, status = 2)
        $approvedInterviewers = IntermediateInterviewer::where('intermediate_application_id', $application->id)
            ->where('interview_type', 2) // Initial Interview
            ->where('interview_status', 2) // Approved/Accepted
            ->get();

        $totalApproved = $approvedInterviewers->count();


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

            if (! is_null($interviewer->evaluation_score) && $interviewer->evaluation_score !== '') {
                $scores[] = floatval($interviewer->evaluation_score);
                $interviewersWithScores++;
            }
        }

        // ✅ CRITICAL: Only calculate average if ALL approved interviewers have submitted
        if ($interviewersWithScores < $totalApproved) {

            // Clear the fields until everyone has submitted
            $application->initial_interview_final = null;
            $application->initial_interview_result = null;
            $application->initial_interview_application_status = null;
            $application->save();

            return;
        }

        if (count($scores) > 0) {
            $sum = array_sum($scores);
            $average = $sum / count($scores);
            $roundedAverage = round($average, 2);

            // Update the application with the average
            $application->initial_interview_final = $roundedAverage;

            // Apply the score mapping logic
            $result = $this->mapInterviewScore($roundedAverage);

            $application->initial_interview_result = $result['result'];
            $application->initial_interview_application_status = $result['status'];

            $application->save();

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

            if (! is_null($interviewer->evaluation_score) && $interviewer->evaluation_score !== '') {
                $scores[] = floatval($interviewer->evaluation_score);
                $interviewersWithScores++;
            }
        }


        // ✅ CRITICAL: Only calculate average if ALL approved interviewers have submitted
        if ($interviewersWithScores < $totalApproved) {

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

            // Update the application with the average
            $application->final_interview_final = $roundedAverage;

            // Apply the score mapping logic
            $result = $this->mapInterviewScore($roundedAverage);

            $application->final_interview_result = $result['result'];
            $application->final_interview_application_status = $result['status'];

            $application->save();

            \Log::info("✅ Final interview average saved: {$roundedAverage}");
        } else {
            \Log::info('⚠️ No valid scores found');
        }
    }

    /**
     * Determine the application stage based on the data provided
     */
    private function determineApplicationStage($request): int
    {
        // Priority 1: Job Offer
        if ($this->hasJobOfferDataFromRequest($request)) {
            return 5;
        }

        // Priority 2: Final Interview
        if ($this->hasFinalInterviewDataFromRequest($request)) {
            return 4;
        }

        // Priority 3: Initial Interview or Exam
        $hasExam = $this->hasExamDataFromRequest($request);
        $hasInitial = $this->hasInitialInterviewDataFromRequest($request);

        if ($hasInitial && ! $hasExam) {
            return 3; // For Initial Interview
        } elseif ($hasExam && ! $hasInitial) {
            return 2; // For Exam
        } elseif ($hasExam && $hasInitial) {
            // Check which was most recently edited
            return $this->determineMostRecentStage($request);
        }

        return $this->application_stage ?? 1; // Default to current or New
    }

    /**
     * Determine the most recent stage when both exam and initial interview have data
     */
    private function determineMostRecentStage($request): int
    {
        $requestExamFields = [
            'exam_plan_date', 'exam_actual_date', 'exam_venue',
            'exam_atpp_part1_correct', 'exam_atpp_part1_wrong',
            'exam_atpp_part2_correct', 'exam_atpp_part2_wrong',
            'exam_atpp_part3_correct', 'exam_atpp_part3_wrong',
            'exam_atpp_result', 'exam_tech_result', 'exam_remarks',
        ];

        $requestInitialFields = [
            'initial_interview_plan_date', 'initial_interview_actual_date',
            'initial_interview_venue', 'initial_interview_final',
            'initial_interview_remarks',
        ];

        $examHasNewData = collect($requestExamFields)->some(
            fn ($field) => $request->has($field) && $request->$field !== null
        );
        $initialHasNewData = collect($requestInitialFields)->some(
            fn ($field) => $request->has($field) && $request->$field !== null
        );

        if ($examHasNewData && ! $initialHasNewData) {
            return 2; // Exam fields were edited
        } elseif ($initialHasNewData && ! $examHasNewData) {
            return 3; // Initial interview fields were edited
        } elseif ($examHasNewData && $initialHasNewData) {
            return 2; // Both edited, default to Exam
        }

        return $this->application_stage; // Keep current stage
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
     * Check if Final Interview fields have data from request
     */
    private function hasFinalInterviewDataFromRequest(Request $request): bool
    {
        return ! empty($request->final_interview_date) ||
               ! empty($request->final_interview_final) ||
               ! empty($request->final_interview_remarks);
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
     * Log the application update
     */
    private function logUpdate(): void
    {
        Log::createLog(
            'Intermediate',
            "Application #{$this->id} updated successfully.",
            $this->intermediate_applicant_id
        );
    }

    /**
     * Update the application using the form request
     */
    public static function updateFromRequest(int $id, $request): self
    {
        $application = self::findOrFail($id);

        DB::beginTransaction();

        try {
            // Auto-set contacted_by if applicable
            $validated = $request->validated();

            if (! empty($validated['contacted_date']) && empty($application->contacted_by)) {
                $validated['contacted_by'] = auth()->id();
            }

            $application->updateApplication($validated, $request);

            DB::commit();

            return $application;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
