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

        $schedules = ResourceSchedule::listPageData($search);

        return inertia('action/schedules/ResourceScheduleList', [
            'schedules'       => $schedules,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }
}