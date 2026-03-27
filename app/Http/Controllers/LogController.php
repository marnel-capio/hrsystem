<?php

namespace App\Http\Controllers;

use App\Models\LogModel;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = LogModel::listPageData();

        return inertia('intermediate/resource-requisitions/List', [
            'logs' => $logs,
            'userPermissions' => auth()->user()->permissions,
        ]);
    }
}