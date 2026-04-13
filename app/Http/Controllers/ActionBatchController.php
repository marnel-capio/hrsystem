<?php

namespace App\Http\Controllers;

use App\Models\ActionBatchModel;
use App\Http\Requests\ActionBatchRequest;
use Inertia\Inertia;
use App\Services\ActionBatchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionBatchController extends Controller
{
    protected $actionBatchService;
 
    public function __construct(ActionBatchService $actionBatchService)
    {
        $this->actionBatchService = $actionBatchService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');

        $batches = ActionBatchModel::getPaginated($search, perPage: 20);
        $batchesTotal = ActionBatchModel::count();

        return Inertia::render('action/batches/ActionBatchList', [
            'batches' => $batches,
            'filters' => [
                'search' => $search,
            ],
            'batches_total' => $batchesTotal,
            'user_permissions' => $user->permissions,
        ]);
    }
 
    public function create()
    {
        $options = $this->actionBatchService->getNextBatchOptions();
        $prevBatch = ActionBatchModel::orderBy('created_time', 'desc')->first();
        $lastBatchTargetDate = $prevBatch ? $prevBatch->target_date : null;

        return Inertia::render('action/batches/ActionBatchRegister', [
            'batchOptions' => $options,
            'lastBatchTargetDate' => $lastBatchTargetDate,
        ]);
    }

  
    public function store(ActionBatchRequest $request)
    {
        try {
    
            DB::beginTransaction();
    
            // SIMULATE ERROR
            //throw new \Exception("Test error");
    
            $batch = $this->actionBatchService->create($request->validated(), $request);
    
            DB::commit();
    
            return redirect()
                ->route('action.batches.show', ['id' => $batch->id])
                ->with('success', config('errors.record_created_successfully.errorMessage'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->withErrors([
                'error' => config('errors.transaction_failed.errorMessage')
            ]);
        }
    }
 
 
 
    public function show($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
 
        $createdByUser = \App\Models\User::find($batch->created_by);
        $updatedByUser = \App\Models\User::find($batch->updated_by);
 
        $batch->created_by_name = $createdByUser ? $createdByUser->first_name . ' ' . $createdByUser->last_name : 'Unknown';
        $batch->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';
        return Inertia::render('action/batches/ActionBatchDetail', [
            'batch' => $batch,
            'user_permissions' => auth()->user()->permissions,
            
        ]);
    }


    
    //EDIT/DETAIL
    public function edit($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
        $prevBatch = ActionBatchModel::where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->first();
        $lastBatchTargetDate = $prevBatch ? $prevBatch->target_date : null;

        $nextBatch = ActionBatchModel::where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->first();

        $nextBatchTargetDate = $nextBatch ? $nextBatch->target_date : null;

        return Inertia::render('action/batches/ActionBatchEdit', [
            'batch' => $batch,
            'user_permissions' => auth()->user()->permissions,
            'lastBatchTargetDate' => $lastBatchTargetDate,
            'nextBatchTargetDate' => $nextBatchTargetDate,
        ]);
    }

    public function update(ActionBatchRequest $request, $id)
    {
        try {
            DB::beginTransaction();
    
            // TEST ERROR
            //throw new \Exception("Test error");
    
            $data = $request->validated();
            $data['id'] = $id;
    
            $batch = $this->actionBatchService->update($data, $request);
    
            DB::commit();
    
            return redirect()
                ->route('action.batches.show', $batch->id)
                ->with('success', config('errors.record_updated_successfully.errorMessage'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->withErrors([
                'error' => config('errors.record_updated_failed.errorMessage')
            ]);
        }
    }
}