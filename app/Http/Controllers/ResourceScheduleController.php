<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResourceScheduleRequest;
use App\Models\ResourceSchedule;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResourceScheduleController extends Controller
{

    /**
     * Display the list page. Redirect to this page if user clicks Cancel in Register page.
     */    
    public function index()
    {
        $search = request('search', '');

        $schedules = ResourceSchedule::listPageData($search);

        return inertia('action/schedules/ResourceScheduleList', [
            'schedules'       => $schedules,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

    
    /**
     * Show the register page
     */
    public function create()
    {

        $newBatches = ResourceSchedule::getActionBatches(true);   // exclude scheduled batches
        $prevBatches = ResourceSchedule::getActionBatches(false); // only scheduled batches

        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
            'newBatches' => $newBatches,
            'prevBatches' => $prevBatches
        ]);
    }

    /**
     * Store a new schedule using ResourceScheduleRequest for validation
     */
    public function store(ResourceScheduleRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Convert location string to ID
        $validated['target_location'] = $validated['target_location'] === 'Manila' ? 1 : 2;
        $validated['created_by'] = $user->id;
        $validated['created_time'] = now();
        $validated['updated_by'] = $user->id;
        $validated['updated_time'] = now();

        try {
            DB::beginTransaction();

            // Create resource schedule
            $schedule = ResourceSchedule::create($validated);

            // Fetch action batch name
            $actionBatchName = DB::table('action_batches')
                ->where('id', $schedule->action_batch_id)
                ->value('action_batch');

            // TEMPORARY: force an exception to test the catch block
                //  throw new \Exception('');

            // Log creation
            Log::createLog(
                'ResourceSchedules',
                "Created resource schedule for {$actionBatchName}",
                $schedule->id
            );

            DB::commit(); // commit everything

            return redirect()->route('action.schedules.show', $schedule->id)
                            ->with('success', config('errors.record_created_successfully.errorMessage'));
        } catch (\Exception $e) {
            DB::rollBack(); // rollback any DB changes

            return back()->with([
        'error' => config('errors.transaction_failed.errorMessage'),
        'flash_time' => microtime(true)
    ])->withInput();
        }
    }

    public function show($id)
{
    $schedule = ResourceSchedule::with('actionBatch') // eager load the related batch
                    ->findOrFail($id);

    // Format WBS fields like you did in Register page
    $wbs = [
        'contact_schools' => [
            'start' => $schedule->contact_schools_startdate,
            'end'   => $schedule->contact_schools_enddate
        ],
        'sourcing_testing' => [
            'start' => $schedule->source_testing_startdate,
            'end'   => $schedule->source_testing_enddate
        ],
        'initial_interviews' => [
            'start' => $schedule->initial_interviews_startdate,
            'end'   => $schedule->initial_interviews_enddate
        ],
        'final_interviews' => [
            'start' => $schedule->final_interviews_startdate,
            'end'   => $schedule->final_interviews_enddate
        ],
        'contract_offers' => [
            'start' => $schedule->contract_offers_startdate,
            'end'   => $schedule->contract_offers_enddate
        ],
        'requirements' => [
            'start' => $schedule->requirements_startdate,
            'end'   => $schedule->requirements_enddate
        ],
        'training' => [
            'start' => $schedule->training_startdate,
            'end'   => $schedule->training_enddate
        ]
    ];

    // Map action batch name
    $batchName = optional($schedule->actionBatch)->action_batch ?? 'Unknown';

    return inertia('action/schedules/ResourceScheduleDetails', [
        'schedule' => [
            'id' => $schedule->id,
            'batch_name' => $batchName,
            'target_location' => $schedule->target_location == 1 ? 'Manila' : 'Cebu',
            'target_trainees' => $schedule->target_trainees,
            'deployment_date' => $schedule->deployment_date,
            'wbs' => $wbs,
            'job_acceptance' => $schedule->job_acceptance,
            'job_offer' => $schedule->job_offer,
            'final_interview' => $schedule->final_interview,
            'initial_interview' => $schedule->initial_interview,
            'screening' => $schedule->screening,
            'contact_schools' => $schedule->contact_schools,
            'remarks' => $schedule->remarks,
            'created_by' => $schedule->created_by,
            'created_time' => $schedule->created_time,
            'updated_by' => $schedule->updated_by,
            'updated_time' => $schedule->updated_time,
        ]
    ]);
}

public function edit($id)
{
    $schedule = ResourceSchedule::getWithActionBatch($id); // your existing method

    $newBatches = ResourceSchedule::getActionBatches(true);   // exclude scheduled batches
    $prevBatches = ResourceSchedule::getActionBatches(false); // only scheduled batches

    return inertia('action/schedules/ResourceScheduleEdit', [
        'schedule' => $schedule,
        'newBatches' => $newBatches,
        'prevBatches' => $prevBatches,
        'errorMessages' => config('errors', []),
    ]);
}

public function update(ResourceScheduleRequest $request, $id)
{
    $schedule = ResourceSchedule::findOrFail($id);
    $validated = $request->validated();

    $validated['updated_by'] = auth()->id();
    $validated['updated_time'] = now();

    try {
        DB::beginTransaction();

        $schedule->update($validated);

        // Log the update
        $actionBatchName = $schedule->actionBatch->action_batch ?? '';
        Log::createLog('ResourceSchedules', "Updated resource schedule for {$actionBatchName}", $schedule->id);

        DB::commit();

        return redirect()->route('action.schedules.show', $schedule->id)
                         ->with('success', config('errors.record_updated_successfully.errorMessage'));
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with([
            'error' => config('errors.transaction_failed.errorMessage'),
            'flash_time' => microtime(true),
        ])->withInput();
    }
}
}