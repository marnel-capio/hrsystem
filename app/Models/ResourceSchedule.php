<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResourceSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action_batch_id',
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
     * Relationship to ActionBatch
     */
    public function actionBatch()
    {
        return $this->belongsTo(ActionBatch::class, 'action_batch_id');
    }

    /**
     * Create schedule from request
     */
    public static function createFromRequest($request)
    {
        $validated = $request->validate([
            'action_batch_id' => 'required|exists:action_batches,id',
            'location'        => 'required|string|max:255',
            'targetTrainees'  => 'required|integer|min:1',
            'deploymentDate'  => 'required|date_format:Y-m',
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

        return DB::transaction(function () use ($validated) {
            return self::create([
                'action_batch_id' => $validated['action_batch_id'],
                'target_location' => $validated['location'] === 'Manila' ? 1 : 2,
                'target_trainees' => $validated['targetTrainees'],
                'deployment_date' => $validated['deploymentDate'],

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

                'created_by'      => auth()->id(),
                'created_time'    => now(),
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
                    config('errors.wbs_end_before_start.errorMessage')
                ));
            }
        }
    }
}