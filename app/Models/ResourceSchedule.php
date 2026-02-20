<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ResourceSchedule extends Model
{
    protected $table = 'resource_schedules';

    protected $fillable = [
        'batch_name',
        'target_location',
        'target_trainees',
        'deployment_date',
        'wbs',
    ];

    protected $casts = [
        'wbs' => 'array',
    ];

    public $timestamps = false;

    // --------------------------
    // Static methods for controller
    // --------------------------

    public static function getAll()
    {
        return self::orderBy('created_time', 'desc')->get();
    }

    public static function findById($id)
    {
        return self::findOrFail($id);
    }

    public static function successMessage()
    {
        return config('errors.record_updated_successfully')['errorMessage'];
    }

    // --------------------------
    // Instance method for updating from request
    // --------------------------
    public function updateFromRequest(Request $request)
    {
        // Validation rules
        $errorMessages = [
            'batchName.required'      => config('errors.field_required')['errorMessage'],
            'batchName.unique'        => config('errors.batch_name_taken')['errorMessage'],
            'location.required'       => config('errors.field_required')['errorMessage'],
            'targetTrainees.required' => config('errors.field_required')['errorMessage'],
            'targetTrainees.min'      => config('errors.target_trainees_min')['errorMessage'],
            'deploymentDate.required' => config('errors.field_required')['errorMessage'],
            'wbs.*.start.required'    => config('errors.wbs_start_required')['errorMessage'],
            'wbs.*.start.regex'       => config('errors.wbs_invalid_format')['errorMessage'],
            'wbs.*.end.required'      => config('errors.wbs_end_required')['errorMessage'],
            'wbs.*.end.regex'         => config('errors.wbs_invalid_format')['errorMessage'],
        ];

        $validated = $request->validate([
            'batchName'      => 'required|string|max:255|unique:resource_schedules,batch_name,' . $this->id,
            'location'       => 'required|string|max:255',
            'targetTrainees' => 'required|integer|min:1',
            'deploymentDate' => 'required|date_format:Y-m',
            'wbs'            => 'required|array',
            'wbs.*.start'    => 'required|regex:/^\d{4}-W\d{2}$/',
            'wbs.*.end'      => 'required|regex:/^\d{4}-W\d{2}$/',
        ], $errorMessages);

        // WBS validation: end >= start
        foreach ($validated['wbs'] as $activity => $range) {
            [$startYear, $startWeek] = explode('-W', $range['start']);
            [$endYear, $endWeek]     = explode('-W', $range['end']);

            $startDate = new \DateTime();
            $startDate->setISODate((int)$startYear, (int)$startWeek);

            $endDate = new \DateTime();
            $endDate->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                $wbsError = config('errors.wbs_end_before_start');
                abort(422, str_replace(':activity', ucfirst(str_replace('_', ' ', $activity)), $wbsError['errorMessage']));
            }
        }

        // Transactional update
        DB::transaction(function () use ($validated) {
            $this->update([
                'batch_name'      => $validated['batchName'],
                'target_location' => $validated['location'],
                'target_trainees' => $validated['targetTrainees'],
                'deployment_date' => $validated['deploymentDate'],
                'wbs'             => $validated['wbs'],
            ]);
        });
    }
}