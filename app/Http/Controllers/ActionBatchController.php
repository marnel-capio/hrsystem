<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActionBatch;
use Illuminate\Http\Request;
use Inertia\Inertia;
 
class ActionBatchController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $search = $request->search;
        $query = ActionBatch::query();
 
        if ($search) {
            $query->where('action_batch', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
        }
 
        $batches = $query
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();
 
        return Inertia::render('action/batches/ActionBatchList', [
            'batches' => $batches,
            'filters' => ['search' => $search],
        ]);
    }
 
    // DETAIL
    public function show($id)
    {
        $batch = ActionBatch::select(
                'action_batches.*',
                'creator.first_name as created_by_first',
                'creator.last_name as created_by_last',
                'updater.first_name as updated_by_first',
                'updater.last_name as updated_by_last'
            )
            ->leftJoin('users as creator', 'action_batches.created_by', '=', 'creator.id')
            ->leftJoin('users as updater', 'action_batches.updated_by', '=', 'updater.id')
            ->findOrFail($id);
 
        return Inertia::render('action/batches/ActionBatchDetail', [
            'batch' => $batch
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'action_batch' => 'required|string|max:20',
            'status'       => 'required|boolean',
            'remarks'      => 'nullable|string|max:1024',
        ]);
 
        ActionBatch::create([
            'action_batch'  => $request->action_batch,
            'status'        => $request->status,
            'remarks'       => $request->remarks,
            'created_by'    => auth()->id(),        
            'updated_by'    => auth()->id(),
            'created_time'  => now(),
            'updated_time'  => now(),
        ]);
 
        return redirect()->route('action.list')
                         ->with('success', 'Records created successfully');
    }
}