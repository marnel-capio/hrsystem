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
        $schedule = ResourceSchedule::with(['actionBatch', 'updater'])->findOrFail($id);
        $projection = $schedule->getProjection($schedule->prev_batch_id ? ResourceSchedule::find($schedule->prev_batch_id) : null);
        return inertia('action/schedules/ResourceScheduleDetails', [
            'schedule' => $schedule->formattedForShow(),
            'projection' => $projection
            ])->with('success', session('success'))
            ->with('error', session('error'));
    }
    
    public function edit($id)
    {
        $schedule = ResourceSchedule::getWithActionBatch($id);
        return inertia('action/schedules/ResourceScheduleEdit', [
        'schedule' => $schedule->formattedForEdit(),
        'newBatches' => ActionBatchModel::getActionBatches(true),
        'prevBatches' => ResourceSchedule::getAllBatchFromExistingResourceSchedule($id),
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
                'error' => config('errors.transaction_failed.errorMessage'),
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
            return back()->with('error', 'errors.email_sent_failed.errorMessage');
        }

        $link = url("/action/schedules/{$schedule->id}");

        try {
            Mail::to($emails)->send(new ResourceScheduleNotificationMail(
                $batchName,
                $link
            ));

            return back()->with('success', 'errors.email_sent_success.errorMessage');
        } catch (\Exception $e) {
            return back()->with('error', 'errors.email_sent_failed.errorMessage');
        }
    }

    public function destroy($id)
    {
        $schedule = ResourceSchedule::findOrFail($id);

        try {
            DB::beginTransaction();

            $actionBatchName = $schedule->actionBatch->action_batch ?? '';
            Log::createLog('ResourceSchedules', "Deleted resource schedule for {$actionBatchName}", $schedule->id);

            $schedule->delete();

            DB::commit();

            return redirect()->route('action.schedules.index')
                             ->with('success', config('errors.record_deleted_successfully.errorMessage'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', config('errors.record_deleted_failed.errorMessage'));
        }
    }
}