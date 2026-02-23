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
        if (! Auth::user()->can('view_action_batches')) {
            abort(403, 'Unauthorized');
        }
 
        $search = $request->input('search');
        $batches = ActionBatchModel::getPaginated($search, 20);
 
        return Inertia::render('action/batches/ActionBatchList', [
            'batches' => $batches,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }
}
 