<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActionBatchModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
 
class ActionBatchController extends Controller
{
    public function index(Request $request)
    {
        // Check if the user is authorized
        // if (!Auth::user()->can('view_action_batches_create')) {
        //     return redirect()
        //         ->route('dashboard')
        //         ->with('error', 'Access denied: You are not authorized to view this page.');
        // }
 
        // Authorized: render the register page
        return Inertia::render('action/batches/ActionBatchRegister');
    }
 
    // Store new action batch
    public function store(Request $request)
{
    // Trim the input to avoid leading/trailing spaces
        $request->merge([
            'action_batch' => trim($request->action_batch)
        ]);
    
        // Check uniqueness
        if (ActionBatchModel::where('action_batch', $request->action_batch)->exists()) {
            return back()->withErrors([
                'action_batch' => 'An error occurred while creating record, please try again.'
            ]);
        }
    
        try {
            // Insert into DB (ignoring created_by etc.)
            $batch = ActionBatchModel::create([
                'action_batch' => $request->action_batch,
                'remarks' => $request->remarks,
            ]);
    
            // Redirect to details page with success flash
            return redirect()
                ->route('action.detail', $batch->id)
                ->with('success', 'Record created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'action_batch' => 'An error occurred while creating record, please try again.'
            ]);
        }
    }
 
 
    // Show details of a batch
    public function detail($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
        return Inertia::render('action/batches/ActionBatchDetail', [
            'batch' => $batch
        ]);
    }
}