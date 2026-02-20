<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceSchedule;

class ResourceScheduleController extends Controller
{

    /**
     * Show schedule details
     */
    public function show($id)
    {
        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => ResourceSchedule::findById($id),
        ]);
    }

    /**
     * Show edit page
     */
    public function edit($id)
    {
        return inertia('action/schedules/ResourceScheduleEdit', [
            'schedule' => ResourceSchedule::findById($id),
        ]);
    }

    /**
     * Update schedule
     */
    public function update(Request $request, $id)
    {
        $schedule = ResourceSchedule::findById($id);
        $schedule->updateFromRequest($request);

        return redirect()->route('action.schedules.show', $id)
                         ->with('success', ResourceSchedule::successMessage());
    }
}