<?php

namespace App\Http\Controllers;

use App\Models\IntermediateProjectModel;
use Inertia\Inertia;
use App\Services\IntermediateProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntermediateProjectController extends Controller
{
    protected $intermediateProjectService;
 
    public function __construct(IntermediateProjectService $intermediateProjectService)
    {
        $this->intermediateProjectService = $intermediateProjectService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');

        $projects = IntermediateProjectModel::getPaginated($search, perPage: 20);
        $projectsTotal = IntermediateProjectModel::count();

        return Inertia::render('intermediate/projects/ProjectList', [
            'projects' => $projects,
            'filters' => [
                'search' => $search,
            ],
            'projects_total' => $projectsTotal,
            'user_permissions' => $user->permissions,
        ]);
    }
}