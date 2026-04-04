<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterActionApplicationRequest;
use App\Models\ActionApplicant;
use App\Models\ActionApplication;
use App\Models\ActionBatchModel;
use App\Models\Log;
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