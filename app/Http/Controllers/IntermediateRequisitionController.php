<?php

namespace App\Http\Controllers;

use App\Models\IntermediateRequisitionModel;
use App\Models\IntermediateProjectModel; // Import your IntermediateProjectModel
use App\Http\Requests\IntermediateRequest;
use Inertia\Inertia;
use App\Services\IntermediateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntermediateRequisitionController extends Controller
{
    protected $intermediateService;

    public function __construct(IntermediateService $intermediateService)
    {
        $this->intermediateService = $intermediateService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $requisitions = IntermediateRequisitionModel::getPaginated($search, perPage: 20);
        $requisitionsTotal = IntermediateRequisitionModel::search($search)->count();

        return Inertia::render('intermediate/resource-requisitions/List', [
            'requisitions' => $requisitions,
            'filters' => [
                'search' => $search,
            ],
            'requisitions_total' => $requisitionsTotal,
            'user_permissions' => $user->permissions,
        ]);
    }

    public function create()
    {
        $projects = IntermediateProjectModel::getProjects(); 

        return Inertia::render('intermediate/resource-requisitions/Register', [
            'newProjects' => $projects,
        ]);
    }

    public function show($id)
    {
        $requisition = IntermediateRequisitionModel::findOrFail($id);
        return Inertia::render('intermediate/resource-requisitions/Detail', [
            'requisition' => $requisition,
            'user_permissions' => auth()->user()->permissions,
        ]);
    }
}