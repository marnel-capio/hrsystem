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
        return inertia('action/schedules/ResourceScheduleList', [
            'schedules' => ResourceSchedule::listPageData(),
        ]);
    }
}