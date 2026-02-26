<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResourceSchedule;

class ResourceScheduleController extends Controller
{
    /**
     * Display the list page.
     */
    public function index()
    {
        $search = request('search', '');

        $schedules = ResourceSchedule::select(
                'resource_schedules.*', 
                'action_batches.action_batch',
                'action_batches.target_trainees' // Get target_trainees from action_batches
            )
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->when($search, function ($query, $search) {
                $query->where('action_batches.action_batch', 'like', "%{$search}%");
            })
            ->orderBy('resource_schedules.created_time', 'desc')
            ->get();

        return inertia('action/schedules/ResourceScheduleList', [
            'schedules'       => $schedules,
            'filters'        => ['search' => $search],
            'userPermissions' => auth()->user()->permissions, 
        ]);
    }
}