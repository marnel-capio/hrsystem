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
        $user = auth()->user();
        
        if (!in_array((int)$user->permissions, [1, 2, 3])) {
            // Use Inertia redirect with error in query string
            return redirect()
                ->route('dashboard')
                ->with('error', 'Access denied: You are not authorized to view this page.');
        }
    
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

    $user = auth()->user();
        
        if (!in_array((int)$user->permissions, [1, 2])) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Access denied: You are not authorized to view this page.');        }

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
                            ->with('success', 'Record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // rollback any DB changes

            return back()->with([
        'error' => 'Failed to create record. Please try again.',
        'flash_time' => microtime(true)
    ])->withInput();
        }
    }

    public function show($id)
    {
        $schedule = ResourceSchedule::getWithActionBatch($id);

        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $schedule,
        ]);
    }
}