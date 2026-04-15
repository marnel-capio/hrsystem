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
use App\Models\User;
use App\Mail\ResourceRequisitionNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceRequisitionDeletedMail;




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
        $requisitionsTotal = IntermediateRequisitionModel::whereHas('project')
        ->search($search)
        ->count();

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
        DB::beginTransaction();
        
        Log::debug('Creating requisition with data: ', $request->validated());
        
        $requisition = $this->intermediateRequisitionService->create($request->validated(), $request);
        
        DB::commit();
        
        return redirect()
            ->route('intermediate.requisitions.show', ['id' => $requisition->id])
            ->with('success', config('errors.record_created_successfully.errorMessage'));

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error creating requisition: ', ['error' => $e->getMessage()]);
        
        return back()->withErrors([
            'error' => config('errors.transaction_failed.errorMessage')
        ]);
    }
}


    public function show($id)
    {
        $requisition = IntermediateRequisitionModel::findOrFail($id);
        $updatedByUser = \App\Models\User::find($requisition->updated_by);

        $requisition->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';

        return Inertia::render('intermediate/resource-requisitions/Detail', [
            'requisition' => $requisition,
            'user_permissions' => auth()->user()->permissions,
        ]);
    }

    public function sendResourceRequisitionNotification($id)
{
    $requisition = IntermediateRequisitionModel::with('project')->findOrFail($id);

    $projectName = optional($requisition->project)->project_name ?? 'Unknown';

    $hrUsers = User::query()
        ->whereNotNull('email_address')
        ->whereIn('permissions', [
            config('constants.HR_ADMIN_PERMISSION.value'),
            config('constants.HR_MANAGER_PERMISSION.value'),
            config('constants.HR_RECRUITER_PERMISSION.value'),
        ])
        ->pluck('email_address')
        ->toArray();

    if (empty($hrUsers)) {
        return back()->with('error', config('errors.email_sent_failed.errorMessage'));
    }

    $link = url("/intermediate/resource-requisitions/{$requisition->id}");

    try {
        Mail::to($hrUsers)->send(
            new ResourceRequisitionNotificationMail($projectName, $link)
        );

        return back()->with('success', config('errors.email_sent_success.errorMessage'));
    } catch (\Exception $e) {
        \Log::error('Requisition email failed', [
            'message' => $e->getMessage(),
        ]);

        return back()->with('error', config('errors.email_sent_failed.errorMessage'));
    }
}


    public function destroy($id)
    {
        $requisition = IntermediateRequisitionModel::with('project')->findOrFail($id);

        $projectName = $requisition->project->project_name ?? '';
        $Location_assignment =
        $requisition->Location_assignment == 1 ? 'Alabang' :
        ($requisition->Location_assignment == 2 ? 'Makati' :
        ($requisition->Location_assignment == 3 ? 'Cebu' :
        ($requisition->Location_assignment == 4 ? 'Japan' : 'China')));
        $start_date = $requisition->start_date;
        $emails = $this->getDeleteNotificationEmails();

        try {
            DB::beginTransaction();

            Log::info("Deleted resource requisition", [
        'module' => 'ResourceRequisitions',
        'project_name' => $projectName,
        'requisition_id' => $requisition->id,
    ]);
            $requisition->delete();

            DB::commit();

            // Mail should not block redirect
        if (!empty($emails)) {
            try {
                Mail::to($emails)->send(new ResourceRequisitionDeletedMail(
                    $projectName,
                    $Location_assignment,
                    $start_date
                ));
            } catch (\Exception $e) {
                Log::info('Resource requisition delete mail failed', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        return redirect()->route('intermediate.requisitions.index')
            ->with('success', config('errors.record_deleted_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            Log::info('Resource requisition delete failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', config('errors.record_deleted_failed.errorMessage'));
        }


    }

        private function getDeleteNotificationEmails(): array
    {
        $permissionIds = [
            config('constants.HR_ADMIN_PERMISSION.value'),
            config('constants.HR_MANAGER_PERMISSION.value'),
            config('constants.HR_RECRUITER_PERMISSION.value'),
        ];

        return User::query()
            ->whereIn('permissions', $permissionIds)
            ->whereNotNull('email_address')
            ->pluck('email_address')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }


    
    public function edit($id)
    {
        $requisition = IntermediateRequisitionModel::findOrFail($id);
    
        return Inertia::render('intermediate/resource-requisitions/Edit', [
            'requisition' => $requisition,
            'user_permissions' => auth()->user()->permissions,
        ]);
    }
    

    public function update(IntermediateRequisitionRequest $request, $id)
    {
        try {
            DB::beginTransaction();
    
            // TEST ERROR
            //throw new \Exception("Test error");
    
            $data = $request->validated();
            $data['id'] = $id;
    
            $requisition = $this->intermediateRequisitionService->update($data, $request);
    
            DB::commit();
    
            return redirect()
                ->route('intermediate.requisitions.show', $requisition->id)
                ->with('success', config('errors.record_updated_successfully.errorMessage'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();

            return back()->withErrors([
                'error' => config('errors.record_updated_failed.errorMessage')
            ]);
        }
    }
}