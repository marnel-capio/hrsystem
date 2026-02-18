<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ResourceSchedule;

/**
 * ResourceScheduleController
 *
 * Handles CRUD operations for resource schedules, including validation, WBS processing,
 * database transactions, and centralized error/success messaging.
 */
class ResourceScheduleController extends Controller
{
    /**
     * Display a list of resource schedules.
     *
     * Retrieves all schedules ordered by creation time (descending) and passes them
     * to the Inertia.js list view for rendering.
     *
     */
    public function index()
    {
        $schedules = ResourceSchedule::orderBy('created_time', 'desc')->get();

        return inertia('action/schedules/ResourceScheduleList', [
            'schedules' => $schedules,
        ]);
    }

    /**
     * Show the form for creating a new resource schedule.
     *
     * Renders the registration form and passes centralized error messages
     * from the config for client-side use.
     *
     */
    public function create()
    {
        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
        ]);
    }

    /**
     * Store a newly created resource schedule.
     *
     * Validates input data, performs custom WBS validation, and saves the schedule
     * within a database transaction. Redirects to the show page on success or back
     * with errors on failure.
          */
    public function store(Request $request)
    {
        // Define validation rules with centralized error messages
        $errorMessages = [
            'batchName.required' => config('errors.field_required')['errorMessage'],
            'batchName.unique' => config('errors.batch_name_taken')['errorMessage'],
            'location.required' => config('errors.field_required')['errorMessage'],
            'targetTrainees.required' => config('errors.field_required')['errorMessage'],
            'targetTrainees.integer' => config('errors.target_trainees_invalid')['errorMessage'],
            'targetTrainees.min' => config('errors.target_trainees_min')['errorMessage'],
            'deploymentDate.required' => config('errors.field_required')['errorMessage'],
            'deploymentDate.date_format' => config('errors.deployment_date_format')['errorMessage'],
            'wbs.required' => config('errors.wbs_required')['errorMessage'],
            'wbs.*.start.required' => config('errors.wbs_start_required')['errorMessage'],
            'wbs.*.start.regex' => config('errors.wbs_invalid_format')['errorMessage'],
            'wbs.*.end.required' => config('errors.wbs_end_required')['errorMessage'],
            'wbs.*.end.regex' => config('errors.wbs_invalid_format')['errorMessage'],
        ];

        // Validate input data (performed outside transaction as it's not a DB operation)
        $validated = $request->validate([
            'batchName'      => 'required|string|max:255|unique:resource_schedules,batch_name',
            'location'       => 'required|string|max:255',
            'targetTrainees' => 'required|integer|min:1',
            'deploymentDate' => 'required|date_format:Y-m',
            'wbs'            => 'required|array',
            'wbs.contact_schools.start'      => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.contact_schools.end'        => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.sourcing_testing.start'     => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.sourcing_testing.end'       => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.initial_interviews.start'   => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.initial_interviews.end'     => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.final_interviews.start'     => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.final_interviews.end'       => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.contract_offers.start'      => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.contract_offers.end'        => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.requirements.start'         => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.requirements.end'           => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.training.start'             => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.training.end'               => 'required|regex:/^\d{4}-W\d{2}$/',
        ], $errorMessages);

        // Perform custom WBS validation to ensure end dates are not before start dates
        foreach ($validated['wbs'] as $activity => $range) {
            [$startYear, $startWeek] = explode('-W', $range['start']);
            [$endYear, $endWeek]     = explode('-W', $range['end']);

            $startDate = new \DateTime();
            $startDate->setISODate((int)$startYear, (int)$startWeek);

            $endDate = new \DateTime();
            $endDate->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                $wbsError = config('errors.wbs_end_before_start');
                return back()->withErrors([
                    "wbs.$activity.end" => str_replace(':activity', ucfirst(str_replace('_', ' ', $activity)), $wbsError['errorMessage'])
                ])->withInput();
            }
        }

        // Wrap database operations in a transaction for atomicity
        DB::beginTransaction();
        try {
            $schedule = ResourceSchedule::create([
                'batch_name'       => $validated['batchName'],
                'target_location'  => $validated['location'],
                'target_trainees'  => $validated['targetTrainees'],
                'deployment_date'  => $validated['deploymentDate'],
                'wbs'              => $validated['wbs'],
            ]);

            DB::commit();

            return redirect()->route('action.schedules.show', $schedule->id)
                             ->with('success', config('errors.record_created_successfully')['errorMessage']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error = config('errors.transaction_failed');
            return back()->withErrors(['general' => $error['errorMessage']])->withInput();
        }
    }

    /**
     * Display the details of a specific resource schedule.
     *
     */
    public function show($id)
    {
        $schedule = ResourceSchedule::findOrFail($id);

        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * Update an existing resource schedule.
     *
     * Validates input data, performs custom WBS validation, and updates the schedule
     * within a database transaction. Redirects to the show page on success or back
     * with errors on failure.
     *
     */
    public function update(Request $request, $id)
    {
        $schedule = ResourceSchedule::findOrFail($id);

        // Define validation rules with centralized error messages (similar to store)
        $errorMessages = [
            'batchName.required' => config('errors.field_required')['errorMessage'],
            'batchName.unique' => config('errors.batch_name_taken')['errorMessage'],
            'location.required' => config('errors.field_required')['errorMessage'],
            'targetTrainees.required' => config('errors.field_required')['errorMessage'],
            'targetTrainees.integer' => config('errors.target_trainees_invalid')['errorMessage'],
            'targetTrainees.min' => config('errors.target_trainees_min')['errorMessage'],
            'deploymentDate.required' => config('errors.field_required')['errorMessage'],
            'deploymentDate.date_format' => config('errors.deployment_date_format')['errorMessage'],
            'wbs.required' => config('errors.wbs_required')['errorMessage'],
            'wbs.*.start.required' => config('errors.wbs_start_required')['errorMessage'],
            'wbs.*.start.regex' => config('errors.wbs_invalid_format')['errorMessage'],
            'wbs.*.end.required' => config('errors.wbs_end_required')['errorMessage'],
            'wbs.*.end.regex' => config('errors.wbs_invalid_format')['errorMessage'],
        ];

        // Validate input data (performed outside transaction)
        $validated = $request->validate([
            'batchName'      => 'required|string|max:255|unique:resource_schedules,batch_name,' . $id,
            'location'       => 'required|string|max:255',
            'targetTrainees' => 'required|integer|min:1',
            'deploymentDate' => 'required|date_format:Y-m',
            'wbs'            => 'required|array',
            'wbs.contact_schools.start'      => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.contact_schools.end'        => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.sourcing_testing.start'     => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.sourcing_testing.end'       => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.initial_interviews.start'   => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.initial_interviews.end'     => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.final_interviews.start'     => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.final_interviews.end'       => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.contract_offers.start'      => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.contract_offers.end'        => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.requirements.start'         => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.requirements.end'           => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.training.start'             => 'required|regex:/^\d{4}-W\xd{2}$/',
            'wbs.training.end'               => 'required|regex:/^\d{4}-W\xd{2}$/',
        ], $errorMessages);

        // Perform custom WBS validation
        foreach ($validated['wbs'] as $activity => $range) {
            [$startYear, $startWeek] = explode('-W', $range['start']);
            [$endYear, $endWeek]     = explode('-W', $range['end']);

            $startDate = new \DateTime();
            $startDate->setISODate((int)$startYear, (int)$startWeek);

            $endDate = new \DateTime();
            $endDate->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                $wbsError = config('errors.wbs_end_before_start');
                return back()->withErrors([
                    "wbs.$activity.end" => str_replace(':activity', ucfirst(str_replace('_', ' ', $activity)), $wbsError['errorMessage'])
                ])->withInput();
            }
        }

        // Wrap database operations in a transaction
        DB::beginTransaction();
        try {
            $schedule->update([
                'batch_name'       => $validated['batchName'],
                'target_location'  => $validated['location'],
                'target_trainees'  => $validated['targetTrainees'],
                'deployment_date'  => $validated['deploymentDate'],
                'wbs'              => $validated['wbs'],
            ]);

            DB::commit();

            return redirect()->route('action.schedules.show', $id)
                             ->with('success', config('errors.record_created_successfully')['errorMessage']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error = config('errors.transaction_failed');
            return back()->withErrors(['general' => $error['errorMessage']])->withInput();
        }
    }
}