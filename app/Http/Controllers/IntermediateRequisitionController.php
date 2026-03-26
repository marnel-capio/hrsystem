<?php

namespace App\Http\Controllers;

use App\Models\IntermediateRequisitionModel;
use App\Http\Requests\IntermediateRequest;
use Inertia\Inertia;
use App\Services\IntermediateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
    $search = $request->input('search');  // Make sure this is correctly passed

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
}