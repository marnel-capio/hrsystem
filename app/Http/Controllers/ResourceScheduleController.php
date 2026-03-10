<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResourceScheduleRequest;
use App\Models\ResourceSchedule;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Mail\ResourceScheduleNotificationMail;
use App\Models\User;
use App\Models\EmailHistory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ResourceScheduleController extends Controller
{

    /**
     * Display the list page. Redirect to this page if user clicks Cancel in Register page.
     */    
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

    
    /**
     * Show the register page
     */
    public function create()
    {

        $newBatches = ResourceSchedule::getActionBatches(true);   // exclude scheduled batches
        $prevBatches = ResourceSchedule::getActionBatches(false); // only scheduled batches

        return inertia('action/schedules/ResourceScheduleRegister', [
            'errorMessages' => config('errors', []),
            'newBatches' => $newBatches,
            'prevBatches' => $prevBatches
        ]);
    }

    /**
     * Store a new schedule using ResourceScheduleRequest for validation
     */
    public function store(ResourceScheduleRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Convert location string to ID
        $validated['target_location'] = $validated['target_location'] === 'Manila' ? 1 : 2;
        $validated['created_by'] = $user->id;
        $validated['created_time'] = now();
        $validated['updated_by'] = $user->id;
        $validated['updated_time'] = now();

        try {
            DB::beginTransaction();

            // Create resource schedule
            $schedule = ResourceSchedule::create($validated);

            // Fetch action batch name
            $actionBatchName = DB::table('action_batches')
                ->where('id', $schedule->action_batch_id)
                ->value('action_batch');

            // TEMPORARY: force an exception to test the catch block
                //  throw new \Exception('');

            // Log creation
            Log::createLog(
                'ResourceSchedules',
                "Created resource schedule for {$actionBatchName}",
                $schedule->id
            );

            DB::commit(); // commit everything

            return redirect()->route('action.schedules.show', $schedule->id)
                            ->with('success', config('errors.record_created_successfully.errorMessage'));
        } catch (\Exception $e) {
            DB::rollBack(); // rollback any DB changes

            return back()->with([
        'error' => config('errors.transaction_failed.errorMessage'),
        'flash_time' => microtime(true)
    ])->withInput();
        }
    }

    public function show($id)
{
    $schedule = ResourceSchedule::with('actionBatch') // eager load the related batch
                    ->findOrFail($id);

    // Format WBS fields like you did in Register page
    $wbs = [
        'contact_schools' => [
            'start' => $schedule->contact_schools_startdate,
            'end'   => $schedule->contact_schools_enddate
        ],
        'sourcing_testing' => [
            'start' => $schedule->source_testing_startdate,
            'end'   => $schedule->source_testing_enddate
        ],
        'initial_interviews' => [
            'start' => $schedule->initial_interviews_startdate,
            'end'   => $schedule->initial_interviews_enddate
        ],
        'final_interviews' => [
            'start' => $schedule->final_interviews_startdate,
            'end'   => $schedule->final_interviews_enddate
        ],
        'contract_offers' => [
            'start' => $schedule->contract_offers_startdate,
            'end'   => $schedule->contract_offers_enddate
        ],
        'requirements' => [
            'start' => $schedule->requirements_startdate,
            'end'   => $schedule->requirements_enddate
        ],
        'training' => [
            'start' => $schedule->training_startdate,
            'end'   => $schedule->training_enddate
        ]
    ];

    // Map action batch name
    $batchName = optional($schedule->actionBatch)->action_batch ?? 'Unknown';

    $prevBatchName = null;

    if ($schedule->prev_batch_id) {
        $prevBatchName = DB::table('action_batches')
            ->where('id', $schedule->prev_batch_id)
            ->value('action_batch');
    }

    // -------------------------------
    // RECRUITMENT PROJECTION LOGIC
    // -------------------------------

    $actualApps = ResourceSchedule::getRecruitmentProjection($schedule->action_batch_id);
    $planApps   = $schedule->prev_batch_id
                    ? ResourceSchedule::getRecruitmentProjection($schedule->prev_batch_id)
                    : collect([]);

    // Helper for counting
    $counter = function($apps, $stage) {
        return match ($stage) {
            'examinees' => $apps->whereNotNull('exam_actual_date')->count(),

            'initial_interview' => $apps->where('initial_interview_result', '!=', null)
                                        ->where('initial_interview_result', '!=', 1)->count(),

            'final_interview' => $apps->where('final_interview_result', '!=', null)
                                      ->where('final_interview_result', '!=', 1)->count(),

            'accepted' => $apps->where('job_offer_status', 3)->count(),

            'declined' => $apps->where('job_offer_status', 4)->count(),

            'trainees_manila' => $apps->where('trainees_from', 1)->count(),

            'trainees_cebu' => $apps->where('trainees_from', 2)->count(),

            default => 0,
        };
    };

    $stages = [
        'examinees',
        'initial_interview',
        'final_interview',
        'accepted',
        'declined',
        'trainees_manila',
        'trainees_cebu',
    ];

    $projection = [];

    foreach ($stages as $stage) {
        $actualNo = $counter($actualApps, $stage);
        $planNo   = $counter($planApps, $stage);

        $projection[$stage] = [
            'actual_no' => $actualNo,
            'actual_pct' => $schedule->target_trainees > 0
                ? round(($actualNo / $schedule->target_trainees) * 100, 2)
                : 0,

            'plan_no' => $planNo,
            'plan_pct' => $schedule->target_trainees > 0
                ? round(($planNo / $schedule->target_trainees) * 100, 2)
                : 0,
        ];
    }

    return inertia('action/schedules/ResourceScheduleDetails', [
        'schedule' => [
            'id' => $schedule->id,
            'batch_name' => $batchName,
            'prev_batch_name' => $prevBatchName,
            'target_location' => $schedule->target_location == 1 ? 'Manila' : 'Cebu',
            'target_trainees' => $schedule->target_trainees,
            'deployment_date' => $schedule->deployment_date,
            'wbs' => $wbs,
            'job_acceptance' => $schedule->job_acceptance,
            'job_offer' => $schedule->job_offer,
            'final_interview' => $schedule->final_interview,
            'initial_interview' => $schedule->initial_interview,
            'screening' => $schedule->screening,
            'contact_schools' => $schedule->contact_schools,
            'remarks' => $schedule->remarks,
            'created_by' => $schedule->created_by,
            'created_time' => $schedule->created_time,
            'updated_by' => $schedule->updated_by,
            'updated_time' => $schedule->updated_time,
        ],
        'projection' => $projection
    ]);
}

public function edit($id)
{
    $schedule = ResourceSchedule::getWithActionBatch($id);

    $newBatches = ResourceSchedule::getActionBatches(true);
    $prevBatches = ResourceSchedule::getActionBatches(false);

    // Get the current batch info
    $currentBatch = DB::table('action_batches')
        ->where('id', $schedule->action_batch_id)
        ->first();

    // Format data - convert target_location to string
    $formattedSchedule = [
        'id' => $schedule->id,
        'action_batch_id' => (int) $schedule->action_batch_id,
        'prev_batch_id' => (int) $schedule->prev_batch_id,
        'target_location' => (string) $schedule->target_location, // Convert to string!
        'target_trainees' => (int) $schedule->target_trainees,
        'deployment_date' => $schedule->deployment_date,
        'remarks' => $schedule->remarks,
        // WBS dates
        'contact_schools_startdate' => $schedule->contact_schools_startdate,
        'contact_schools_enddate' => $schedule->contact_schools_enddate,
        'source_testing_startdate' => $schedule->source_testing_startdate,
        'source_testing_enddate' => $schedule->source_testing_enddate,
        'initial_interviews_startdate' => $schedule->initial_interviews_startdate,
        'initial_interviews_enddate' => $schedule->initial_interviews_enddate,
        'final_interviews_startdate' => $schedule->final_interviews_startdate,
        'final_interviews_enddate' => $schedule->final_interviews_enddate,
        'contract_offers_startdate' => $schedule->contract_offers_startdate,
        'contract_offers_enddate' => $schedule->contract_offers_enddate,
        'requirements_startdate' => $schedule->requirements_startdate,
        'requirements_enddate' => $schedule->requirements_enddate,
        'training_startdate' => $schedule->training_startdate,
        'training_enddate' => $schedule->training_enddate,
    ];

    return inertia('action/schedules/ResourceScheduleEdit', [
        'schedule' => $formattedSchedule,
        'newBatches' => $newBatches,
        'prevBatches' => $prevBatches,
        'currentBatch' => $currentBatch,
        'errorMessages' => config('errors', []),
    ]);
}

public function update(ResourceScheduleRequest $request, $id)
{
    $schedule = ResourceSchedule::findOrFail($id);
    $validated = $request->validated();

    // Convert location string back to integer for database
    $validated['target_location'] = $validated['target_location'] === '1' ? 1 : 2;

    $validated['updated_by'] = auth()->id();
    $validated['updated_time'] = now();

    try {
        DB::beginTransaction();

        $schedule->update($validated);

        $actionBatchName = $schedule->actionBatch->action_batch ?? '';
        Log::createLog('ResourceSchedules', "Updated resource schedule for {$actionBatchName}", $schedule->id);

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

    $batchName = DB::table('action_batches')
        ->where('id', $schedule->action_batch_id)
        ->value('action_batch');

    $hrRecruiters = User::where('permissions', 3)
                        ->where('active_status', 1)
                        ->get(['email_address', 'first_name', 'id']);

    // Collect all email addresses into an array
    $emailAddresses = $hrRecruiters->pluck('email_address')->toArray();

    // Only proceed if we have recipients
    if (empty($emailAddresses)) {
        return back()->with('error', 'No HR managers found to send notification.');
    }

    $link = url("/action/schedules/{$id}");

    try {
        // Send ONE email to ALL recipients
        Mail::to($emailAddresses)
            ->send(new ResourceScheduleNotificationMail(
                0, // userId (not used for multiple recipients)
                "HR Team", // Generic recipient name
                $batchName,
                $link
            ));

        // Optional: Log the email history
        // foreach ($emailAddresses as $email) {
        //     EmailHistory::create([
        //         'status' => 1,
        //         'subject' => "【HR System】New Resource Schedule Created",
        //         'from' => config('mail.from.name'),
        //         'email_from' => config('mail.from.address'),
        //         'email_to' => $email,
        //         'email_body' => "Hi HR Team,\n\nA new resource schedule has been created.\nBatch: {$batchName} \nLink: {$link}\n\nThank you,\nAWS HR Manager",
        //         'created_by' => Auth::id(),
        //         'updated_by' => Auth::id(),
        //         'create_time' => now(),
        //         'update_time' => now(),
        //     ]);
        // }

        return back()->with('success', 'Notification emails sent to all HR recruiters.');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to send notification emails.');
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
        return back()->with('error', 'Failed to delete resource schedule.');
    }
}

}