<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResourceSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'batch_name',
        'target_location',
        'target_trainees',
        'deployment_date',
        'wbs',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'wbs'          => 'array',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    /**
     * Validate request and create a new schedule
     */
    public static function createFromRequest($request)
    {
        // Validation rules
        $validated = $request->validate([
            'batchName'      => 'required|string|max:255|unique:resource_schedules,batch_name',
            'location'       => 'required|string|max:255',
            'targetTrainees' => 'required|integer|min:1',
            'deploymentDate' => 'required|date_format:Y-m',
            'wbs'            => 'required|array',
            'wbs.*.start'    => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.*.end'      => 'required|regex:/^\d{4}-W\d{2}$/',
        ], [
            'batchName.required' => config('errors.field_required.errorMessage'),
            'batchName.unique'   => config('errors.batch_name_taken.errorMessage'),
            'location.required'  => config('errors.field_required.errorMessage'),
            'targetTrainees.required' => config('errors.field_required.errorMessage'),
            'targetTrainees.integer'  => config('errors.target_trainees_invalid.errorMessage'),
            'targetTrainees.min'      => config('errors.target_trainees_min.errorMessage'),
            'deploymentDate.required' => config('errors.field_required.errorMessage'),
            'deploymentDate.date_format' => config('errors.deployment_date_format.errorMessage'),
            'wbs.required' => config('errors.wbs_required.errorMessage'),
            'wbs.*.start.required' => config('errors.wbs_start_required.errorMessage'),
            'wbs.*.end.required'   => config('errors.wbs_end_before_start.errorMessage'),
        ]);

        // Validate WBS ranges
        self::validateWbsRanges($validated['wbs']);

        // Create the schedule in a transaction
        return DB::transaction(function () use ($validated) {
            return self::create([
                'batch_name'       => $validated['batchName'],
                'target_location' => $validated['location'] === 'Cebu' ? 2 : ($validated['location'] === 'Manila' ? 1 : $validated['location']),                
                'target_trainees'  => $validated['targetTrainees'],
                'deployment_date'  => $validated['deploymentDate'],
                'wbs'              => $validated['wbs'],
                'created_by'       => auth()->id(),
                'created_time'     => now(),
            ]);
        });
    }

    /**
     * Validate WBS ranges (throws exception on error)
     */
    public static function validateWbsRanges(array $wbs)
    {
        foreach ($wbs as $activity => $range) {
            [$startYear, $startWeek] = explode('-W', $range['start']);
            [$endYear, $endWeek]     = explode('-W', $range['end']);

            $startDate = (new \DateTime())->setISODate((int)$startYear, (int)$startWeek);
            $endDate   = (new \DateTime())->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                $label = ucfirst(str_replace('_', ' ', $activity));
                abort(422, str_replace(':activity', $label, config('errors.wbs_end_before_start.errorMessage')));
            }
        }
    }
}