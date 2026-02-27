<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResourceSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action_batch_id',
        'prev_batch_id',
        'target_location',
        'target_trainees',
        'deployment_date',
        'contact_schools_startdate',
        'contact_schools_enddate',
        'source_testing_startdate',
        'source_testing_enddate',
        'initial_interviews_startdate',
        'initial_interviews_enddate',
        'final_interviews_startdate',
        'final_interviews_enddate',
        'contract_offers_startdate',
        'contract_offers_enddate',
        'requirements_startdate',
        'requirements_enddate',
        'training_startdate',
        'training_enddate',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

        /**
     * Get list page data
     */
    public static function listPageData(?string $search = null)
    {
        return static::query()
            ->select('resource_schedules.*', 'action_batches.action_batch', 'action_batches.target_trainees')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action_batches.action_batch', 'like', "%{$search}%")
                      ->orWhere('resource_schedules.target_location', 'like', "%{$search}%")
                      ->orWhereRaw("DATE_FORMAT(resource_schedules.deployment_date, '%M %Y') LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('resource_schedules.created_time', 'desc')
            ->get();
    }

    /**
     * Relationship to ActionBatch
     */
    public function actionBatch()
    {
        return $this->belongsTo(ActionBatch::class, 'action_batch_id');
    }

    /**
     * Get action batches for dropdown
     * 
     * @param bool $excludeScheduled - If true, excludes batches that already have a resource schedule
     *                                 If false, returns only batches that have a resource schedule
     */
    public static function getActionBatches($excludeScheduled = true)
    {
        // Get IDs of action batches that already have a resource schedule
        $scheduledBatchIds = self::pluck('action_batch_id')->toArray();

        $query = DB::table('action_batches')
            ->select('id', 'action_batch', 'target_trainees');

        if ($excludeScheduled) {
            // For action_batch_id dropdown - exclude scheduled batches
            $query->whereNotIn('id', $scheduledBatchIds);
        } else {
            // For prev_batch_id dropdown - only scheduled batches
            $query->whereIn('id', $scheduledBatchIds);
        }

        return $query->get();
    }

    /**
     * Get schedule with action batch
     */
    public static function getWithActionBatch($id)
    {
        return self::select('resource_schedules.*', 'action_batches.action_batch')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->where('resource_schedules.id', $id)
            ->firstOrFail();
    }

    /**
     * Create schedule from request
     */
    public static function createFromRequest($request)
    {
        $validated = $request->validate([
            'action_batch_id' => 'required|exists:action_batches,id',
            'prev_batch_id' => 'nullable|exists:action_batches,id',
            'target_location' => 'required|string|max:255',
            'target_trainees' => 'required|integer|min:1',
            'deployment_date' => 'required|date_format:Y-m',
            // WBS validation
            'contact_schools_startdate' => 'required|regex:/^\d{4}-W\d{2}$/',
            'contact_schools_enddate'   => 'required|regex:/^\d{4}-W\d{2}$/',
            'source_testing_startdate'  => 'required|regex:/^\d{4}-W\d{2}$/',
            'source_testing_enddate'    => 'required|regex:/^\d{4}-W\d{2}$/',
            'initial_interviews_startdate' => 'required|regex:/^\d{4}-W\d{2}$/',
            'initial_interviews_enddate'   => 'required|regex:/^\d{4}-W\d{2}$/',
            'final_interviews_startdate'   => 'required|regex:/^\d{4}-W\d{2}$/',
            'final_interviews_enddate'     => 'required|regex:/^\d{4}-W\d{2}$/',
            'contract_offers_startdate'    => 'required|regex:/^\d{4}-W\d{2}$/',
            'contract_offers_enddate'      => 'required|regex:/^\d{4}-W\d{2}$/',
            'requirements_startdate'       => 'required|regex:/^\d{4}-W\d{2}$/',
            'requirements_enddate'         => 'required|regex:/^\d{4}-W\d{2}$/',
            'training_startdate'           => 'required|regex:/^\d{4}-W\d{2}$/',
            'training_enddate'             => 'required|regex:/^\d{4}-W\d{2}$/',
        ]);

        self::validateWbsRanges($validated);

        $user = auth()->user();

        // Get the batch name from action_batches table
        $actionBatch = DB::table('action_batches')
            ->where('id', $validated['action_batch_id'])
            ->first();

        return DB::transaction(function () use ($validated, $actionBatch, $user) {
            return self::create([
                'action_batch_id' => $validated['action_batch_id'],
                'prev_batch_id' => $validated['prev_batch_id'] ?? null,
                'target_location' => $validated['target_location'] === 'Manila' ? 1 : 2,
                'target_trainees' => $validated['target_trainees'],
                'deployment_date' => $validated['deployment_date'],

                // WBS fields
                'contact_schools_startdate' => $validated['contact_schools_startdate'],
                'contact_schools_enddate'   => $validated['contact_schools_enddate'],
                'source_testing_startdate'  => $validated['source_testing_startdate'],
                'source_testing_enddate'    => $validated['source_testing_enddate'],
                'initial_interviews_startdate' => $validated['initial_interviews_startdate'],
                'initial_interviews_enddate'   => $validated['initial_interviews_enddate'],
                'final_interviews_startdate'   => $validated['final_interviews_startdate'],
                'final_interviews_enddate'     => $validated['final_interviews_enddate'],
                'contract_offers_startdate'    => $validated['contract_offers_startdate'],
                'contract_offers_enddate'      => $validated['contract_offers_enddate'],
                'requirements_startdate'       => $validated['requirements_startdate'],
                'requirements_enddate'         => $validated['requirements_enddate'],
                'training_startdate'           => $validated['training_startdate'],
                'training_enddate'             => $validated['training_enddate'],

                'created_by'   => $user->id,
                'created_time' => now(),
                'updated_by'   => $user->id,
                'updated_time' => now(),
            ]);
        });
    }

    /**
     * WBS range validation
     */
    public static function validateWbsRanges(array $data)
    {
        $activities = [
            'contact_schools',
            'source_testing',
            'initial_interviews',
            'final_interviews',
            'contract_offers',
            'requirements',
            'training',
        ];

        foreach ($activities as $act) {
            $start = $data[$act . '_startdate'];
            $end   = $data[$act . '_enddate'];

            [$startYear, $startWeek] = explode('-W', $start);
            [$endYear, $endWeek]     = explode('-W', $end);

            $startDate = (new \DateTime())->setISODate((int)$startYear, (int)$startWeek);
            $endDate   = (new \DateTime())->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                abort(422, str_replace(':activity', ucfirst(str_replace('_', ' ', $act)),
                    config('Start week cannot be after end week.')
                ));
            }
        }
    }

    
}