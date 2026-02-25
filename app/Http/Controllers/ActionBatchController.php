<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActionBatchModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
 
class ActionBatchController extends Controller
{
    // Show Register Page
    public function index(Request $request)
    {
        $user = Auth::user();
 
        if (
            !$user ||
            !in_array($user->position, ['Admin', 'HR Manager']) ||
            $user->active_status != 1
        ) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Access denied: You are not authorized to view this page');
        }
 
        return Inertia::render('action/batches/ActionBatchRegister');
    }
 
    // Store Action Batch
    public function store(Request $request)
    {
        $request->validate([
            'action_batch' => [
                'required',
                'string',
                'max:20',
                'unique:action_batches,action_batch'
            ],
            'remarks' => 'nullable|string|max:1024',
            
        ]);
 
        ActionBatchModel::create([
            'action_batch' => strtoupper($request->action_batch),
            'remarks' => $request->remarks,
            'status' => 1,
            'created_by' => Auth::id(),
            'created_time' => Carbon::now(),
            'updated_by' => Auth::id(),
            'updated_time' => Carbon::now(),
        ]);
 
        return redirect()
            ->route('dashboard')
            ->with('success', 'Record created successfully.');
    }
}
 