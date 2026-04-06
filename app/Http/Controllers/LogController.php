<?php

namespace App\Http\Controllers;

use App\Models\LogModel;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
{
    // Fetch logs
    $logs = LogModel::listPageData();

    // Fetch users' full names, excluding those with empty first_name or last_name
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

    // Fetch distinct modules from the logs table
    $modules = DB::table('logs')->distinct()->pluck('module');

    return inertia('log/logs', [
        'logs' => $logs,
        'userPermissions' => auth()->user()->permissions,
        'users' => $users,
        'modules' => $modules,  // Send the distinct modules to the frontend
    ]);
}
}