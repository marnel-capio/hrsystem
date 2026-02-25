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
        // if (!Auth::user()->can('view_action_batches')) {
        //     return redirect()
        //         ->route('dashboard')
        //         ->with('error', 'Access denied: You are not authorized to view this page.');
        // }
 
        $search = $request->input('search');
 
        $batches = ActionBatchModel::getPaginated($search, 20);
        $batchesTotal = ActionBatchModel::count();
 
        return Inertia::render('action/batches/ActionBatchList', [
            'batches' => $batches,
            'filters' => [
                'search' => $search,
            ],
            'batches_total' => $batchesTotal,
        ]);
    }
}