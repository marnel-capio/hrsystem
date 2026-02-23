<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResourceScheduleController extends Controller
{
    /**
     * Only Admin and HR Manager can access
     */
    private function authorizeUser()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['Admin', 'HR Manager'])) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the register page
     */
    public function create()
    {
        $this->authorizeUser(); // Permission check

        // Fetch all action batches for dropdown
        $actionBatches = DB::table('action_batches')
            ->select('id', 'action_batch')
            ->get();

        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
            'actionBatches' => $actionBatches,
        ]);
    }

    /**
     * Store a new schedule
     */
    public function store(Request $request)
    {
        $this->authorizeUser(); // Permission check

        $schedule = ResourceSchedule::createFromRequest($request);

        // Redirect to details page after creation
        return redirect()->route('action.schedules.show', $schedule->id)
            ->with('success', config('errors.record_created_successfully.errorMessage'));
    }

    /**
     * Show details page
     */
    public function show($id)
    {
        $this->authorizeUser(); // Permission check

        // Fetch schedule with action batch name using join
        $schedule = ResourceSchedule::select('resource_schedules.*', 'action_batches.action_batch')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->where('resource_schedules.id', $id)
            ->firstOrFail();

        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $schedule,
        ]);
    }
}