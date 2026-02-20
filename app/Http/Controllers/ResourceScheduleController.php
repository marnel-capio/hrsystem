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

    $schedules = ResourceSchedule::query()
        ->search($search)
        ->orderBy('created_time', 'desc')
        ->get();

    return inertia('action/schedules/ResourceScheduleList', [
        'schedules' => $schedules,
        'filters'   => ['search' => $search],
    ]);
}
}