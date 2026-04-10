<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResourceScheduleRequest;
use App\Models\ResourceSchedule;
use App\Models\ActionBatchModel;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceScheduleNotificationMail;
use App\Models\User;
use App\Mail\ResourceScheduleDeletedMail;

class ResourceScheduleController extends Controller
{
    public function index()
    {
        $search = request('search', '');

        $schedules = ResourceSchedule::listPageData($search);

        return inertia('action/schedules/ResourceScheduleList', [
            'schedules'       => $schedules,
            'filters'         => ['search' => $search],
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

    public function create()
    {
        $newBatches = ActionBatchModel::getActionBatches(true);
        $prevBatches = ActionBatchModel::getActionBatches(false);

        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
            'newBatches' => $newBatches,
            'prevBatches' => $prevBatches
        ]);
    }

    public function store(ResourceScheduleRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $locations = config('constants.trainingLocation');
        $validated['target_location'] = $validated['target_location'] === 'Manila'
        ? $locations['LOCATION_MANILA_VALUE']
        : $locations['LOCATION_CEBU_VALUE'];
        $validated['created_by'] = $user->id;
        $validated['created_time'] = now();
        $validated['updated_by'] = $user->id;
        $validated['updated_time'] = now();

        try {
            DB::beginTransaction();

            // error test
            // throw new \Exception('');

            $schedule = ResourceSchedule::create($validated);

            $actionBatchName = DB::table('action_batches')
                ->where('id', $schedule->action_batch_id)
                ->value('action_batch');

            Log::createLog(
                'ResourceSchedules',
                "Created resource schedule for {$actionBatchName}",
                $schedule->id
            );

            DB::commit();

            return redirect()->route('action.schedules.show', $schedule->id)
                             ->with('success', config('errors.record_created_successfully.errorMessage'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'error' => config('errors.transaction_failed.errorMessage'),
                'flash_time' => microtime(true)
            ])->withInput();
        }
    }

    public function show($id)
    {
        // Load schedule with relationships
        $schedule = ResourceSchedule::with(['actionBatch', 'updater'])->findOrFail($id);

        // Get previous schedule by finding resource schedule with the action_batch_id equal to prev_batch_id
        $prevSchedule = null;
        if ($schedule->prev_batch_id) {
            $prevSchedule = ResourceSchedule::where('action_batch_id', $schedule->prev_batch_id)->first();
        }

        // Get projection data
        $projection = $schedule->getProjection($prevSchedule);

        // Format schedule for show
        $formattedSchedule = $schedule->formattedForShow();

        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $formattedSchedule,
            'projection' => $projection,
            'userPermissions' => auth()->user()->permissions
        ])->with('success', session('success'))
          ->with('error', session('error'));
    }

    public function edit($id)
    {
        $schedule = ResourceSchedule::getWithActionBatch($id);

        // Get previous batches with their names
        $prevBatches = ResourceSchedule::getAllBatchFromExistingResourceSchedule($id);

        return inertia('action/schedules/ResourceScheduleEdit', [
            'schedule' => $schedule->formattedForEdit(),
            'newBatches' => ActionBatchModel::getActionBatches(true),
            'prevBatches' => $prevBatches, // This now has action_batch names
            'currentBatch' => $schedule->currentBatch(),
            'errorMessages' => config('errors', []),
        ])->with('success', session('success'))
          ->with('error', session('error'));
    }

    public function update(ResourceScheduleRequest $request, $id)
    {
        $schedule = ResourceSchedule::findOrFail($id);
        $validated = $request->validated();

        // Convert target_location string to integer
        $validated['target_location'] = $validated['target_location'] === '1' ? 1 : 2;
        $validated['updated_by'] = auth()->id();
        $validated['updated_time'] = now();

        try {
            DB::beginTransaction();

            // error test
            // throw new \Exception('');

            // Keep old values for comparison in logs
            $original = $schedule->getOriginalValuesForUpdate();

            $schedule->update($validated);

            $fieldLabels = ResourceSchedule::fieldLabels();

            // Build log message with changed fields
            $logDetails = "Updated resource schedule for {$schedule->actionBatch->action_batch}.";

            foreach ($validated as $field => $newValue) {
                $oldValue = $original[$field] ?? null;
                if ($oldValue != $newValue) {
                    $label = $fieldLabels[$field] ?? $field;
                    $logDetails .= "{$label}: {$oldValue} -> {$newValue}\n";
                }
            }

            Log::createLog('ResourceSchedules', $logDetails, $schedule->id);

            DB::commit();

            return redirect()->route('action.schedules.show', $schedule->id)
                            ->with('success', config('errors.record_updated_successfully.errorMessage'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'error' => config('errors.record_updated_failed.errorMessage'),
                'flash_time' => microtime(true),
            ])->withInput();
        }
    }

    public function sendResourceScheduleNotification($id)
    {
        $schedule = ResourceSchedule::findOrFail($id);

        $batchName = optional($schedule->actionBatch)->action_batch ?? 'Unknown';

        $hrRecruiters = User::hrRecruiters();

        $emails = $hrRecruiters->pluck('email_address')->toArray();

        //if no hr recruiters found
        if (empty($emails)) {
            return back()->with('error', config('errors.email_sent_failed.errorMessage'));
        }

        $link = url("/action/schedules/{$schedule->id}");

        try {
            Mail::to($emails)->send(new ResourceScheduleNotificationMail(
                $batchName,
                $link
            ));

            return back()->with('success', config('errors.email_sent_success.errorMessage'));
        } catch (\Exception $e) {
            return back()->with('error', config('errors.email_sent_failed.errorMessage'));
        }
    }

public function destroy($id)
{
    $schedule = ResourceSchedule::with('actionBatch')->findOrFail($id);

    $actionBatchName = $schedule->actionBatch->action_batch ?? '';
    $targetLocation = $schedule->target_location == 1 ? 'Manila' : 'Cebu';
    $deploymentDate = $schedule->deployment_date;
    $emails = $this->getDeleteNotificationEmails();

    try {
        DB::beginTransaction();

        Log::createLog('ResourceSchedules', "Deleted resource schedule for {$actionBatchName}", $schedule->id);

        $schedule->delete();

        DB::commit();

        // Mail should not block redirect
    if (!empty($emails)) {
        try {
            Mail::to($emails)->send(new ResourceScheduleDeletedMail(
                $actionBatchName,
                $targetLocation,
                $deploymentDate
            ));
        } catch (\Exception $e) {
            \Log::info('Resource schedule delete mail failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    return redirect()->route('action.schedules.index')
        ->with('success', config('errors.record_deleted_successfully.errorMessage'));

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::info('Resource schedule delete failed', [
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
}
