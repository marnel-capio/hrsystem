<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        return Inertia::render('HRDashboard')->with([
            'menuPermissions' => Config::get('constants.menuPermissions'),
            'hiddenLinks' => Config::get('constants.hiddenLinks'),
        ]);
    }
}