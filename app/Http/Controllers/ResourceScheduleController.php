<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResourceSchedule;
use Inertia\Inertia; // Make sure this is imported

class ResourceScheduleController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $schedules = ResourceSchedule::listPageData($search);

        // 1. Get the flash data from the session
        $flash = session()->get('flash', []);

        return Inertia::render('action/schedules/ResourceScheduleList', [
            'schedules'       => $schedules,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
            // 2. Explicitly pass the flash data to Inertia props
            'flash'           => $flash, 
        ]);
    }
}