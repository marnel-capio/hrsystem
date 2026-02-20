<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceSchedule;
use Illuminate\Support\Facades\DB;

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
        // Validate form inputs
        $validated = $request->validate([
            'batchName'      => 'required|string|max:255|unique:resource_schedules,batch_name',
            'location'       => 'required|string|max:255',
            'targetTrainees' => 'required|integer|min:1',
            'deploymentDate' => 'required|date_format:Y-m',
            'wbs'            => 'required|array',
            'wbs.*.start'    => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.*.end'      => 'required|regex:/^\d{4}-W\d{2}$/',
        ]);

        // Validate WBS ranges
        foreach ($validated['wbs'] as $activity => $range) {
            [$startYear, $startWeek] = explode('-W', $range['start']);
            [$endYear, $endWeek]     = explode('-W', $range['end']);

            $startDate = new \DateTime();
            $startDate->setISODate((int)$startYear, (int)$startWeek);

            $endDate = new \DateTime();
            $endDate->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                return back()->withErrors([
                    "wbs.$activity.end" => "End week cannot be before start week for $activity."
                ])->withInput();
            }
        }

        // Save record
        DB::transaction(function () use ($validated) {
            ResourceSchedule::create([
                'batch_name'       => $validated['batchName'],
                'target_location'  => $validated['location'],
                'target_trainees'  => $validated['targetTrainees'],
                'deployment_date'  => $validated['deploymentDate'],
                'wbs'              => $validated['wbs'],
                'created_by'       => auth()->id(),
                'created_time'     => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Resource schedule created successfully!');
    }
}