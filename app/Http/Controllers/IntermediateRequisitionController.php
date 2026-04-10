<?php

namespace App\Http\Controllers;

use App\Models\IntermediateRequisitionModel;
use App\Models\IntermediateProjectModel; 
use App\Http\Requests\IntermediateRequisitionRequest;
use Inertia\Inertia;
use App\Services\IntermediateRequisitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class IntermediateRequisitionController extends Controller
{
    protected $intermediateRequisitionService;

    public function __construct(IntermediateRequisitionService $intermediateRequisitionService)
    {
        $this->intermediateRequisitionService = $intermediateRequisitionService;
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

    public function store(IntermediateRequisitionRequest $request)
{
    try {
        // Start a transaction
        DB::beginTransaction();
        
        // Log the validated data for debugging
        Log::debug('Creating requisition with data: ', $request->validated());
        
        // Create the requisition
        $requisition = $this->intermediateRequisitionService->create($request->validated(), $request);
        
        // Commit the transaction
        DB::commit();
        
        // Redirect on success
        return redirect()
            ->route('intermediate.requisitions.show', ['id' => $requisition->id])
            ->with('success', config('errors.record_created_successfully.errorMessage'));

    } catch (\Exception $e) {
        // Rollback the transaction
        DB::rollBack();

        // Log the exception
        Log::error('Error creating requisition: ', ['error' => $e->getMessage()]);
        
        // Return error message
        return back()->withErrors([
            'error' => config('errors.transaction_failed.errorMessage')
        ]);
    }
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