<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceSchedule;

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
        $schedule = ResourceSchedule::createFromRequest($request);

        return redirect()->route('action.schedules.show', $schedule->id)
            ->with('success', "Record created successfully.");
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