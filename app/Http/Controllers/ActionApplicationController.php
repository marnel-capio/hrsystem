<?php

namespace App\Http\Controllers;

use App\Models\ActionApplication;

class ActionApplicationController extends Controller
{
    /**
     * Display a listing of the applications.
     */
    public function index()
    {
        $search = request('search', '');

        $applications = ActionApplication::listPageData($search);

        return inertia('action/applications/ActionApplicationList', [
            'applications'       => $applications,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

}
