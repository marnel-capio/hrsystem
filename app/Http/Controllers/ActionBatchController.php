<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActionBatchModel;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
 
class ActionBatchController extends Controller
{
    public function show($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
        return Inertia::render('action/batches/ActionBatchEditDetail', ['batch'=> $batch]);
    }
}
