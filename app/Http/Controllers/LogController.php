<?php

namespace App\Http\Controllers;

use App\Models\LogModel;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class LogController extends Controller
{
    public function index(Request $request)
{
    $logs = LogModel::listPageData();

    $users = DB::table('logs')
        ->select(
            'logs.created_by', 
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name")
        )
        ->leftJoin('users', 'users.id', '=', 'logs.created_by')
        ->whereNotNull('users.first_name')
        ->whereNotNull('users.last_name')
        ->where('users.first_name', '!=', '')
        ->where('users.last_name', '!=', '')
        ->groupBy('logs.created_by', 'full_name')
        ->orderBy('full_name')
        ->get();

    $modules = DB::table('logs')->distinct()->pluck('module');

    Log::info($modules);

    return inertia('logs/logs', [
        'logs' => $logs,
        'userPermissions' => auth()->user()->permissions,
        'users' => $users,
        'modules' => $modules,
    ]
    );
}
}