<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\ResourceSchedule;
use Illuminate\Support\Facades\DB;

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
        // true = exclude scheduled batches (for action_batch_id dropdown)
        $newBatches = ResourceSchedule::getActionBatches(true);
        
        // false = only scheduled batches (for prev_batch_id dropdown)
        $prevBatches = ResourceSchedule::getActionBatches(false);

        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
            'newBatches' => $newBatches,
            'prevBatches' => $prevBatches,
        ]);
    }

    /**
     * Store a new schedule
     */
    public function store(Request $request)
    {
        try {
            $schedule = ResourceSchedule::createFromRequest($request);

            // Get action_batch name directly from DB
            $actionBatchName = DB::table('action_batches')
                ->where('id', $schedule->action_batch_id)
                ->value('action_batch');

            // Log successful creation with action_batch name
            Log::createLog(
                'ResourceSchedules',
                "Created resource schedule for {$actionBatchName}",
                $schedule->id
            );

            return redirect()->route('action.schedules.show', $schedule->id)
                ->with('success', "Record created successfully.");
        } catch (\Exception $e) {
            // Log unsuccessful creation
            Log::createLog(
                'ResourceSchedules',
                "Failed to create resource schedule for {$actionBatchName}",
                null
            );

            return back()->withErrors([
                'general' => 'Failed to create record. Please try again.',
            ])->withInput();
        }
    }

    /**
     * Show details page
     */
    public function show($id)
    {
        $schedule = ResourceSchedule::getWithActionBatch($id);

        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $schedule,
        ]);
    }
}