<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceSchedule;

class ResourceScheduleController extends Controller
{
    /**
     * Show the register page
     */
    public function create()
    {
        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
        ]);
    }

    /**
     * Store a new schedule
     */
    public function store(Request $request)
    {
        $schedule = ResourceSchedule::createFromRequest($request);

        // Redirect to details page after creation
        return redirect()->route('action.schedules.show', $schedule->id)
            ->with('success', config('errors.record_created_successfully.errorMessage'));
    }

    /**
     * Show details page
     */
    public function show($id)
    {
        $schedule = ResourceSchedule::findOrFail($id);

        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $schedule,
        ]);
    }
}