<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsoWeekFormat;
use Carbon\Carbon;

class ResourceScheduleRequest extends FormRequest
{
    private const ACTIVITIES = [
        'contact_schools',
        'source_testing',
        'initial_interviews',
        'final_interviews',
        'contract_offers',
        'requirements',
        'training',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'action_batch_id' => 'required|exists:action_batches,id',
            'prev_batch_id'   => 'nullable|exists:action_batches,id',
            'target_location' => 'required',
            'target_trainees' => 'required|integer|min:1',
            'deployment_date' => 'required|date_format:Y-m',
            'remarks'         => 'nullable|string|max:1024',
        ];

        foreach (self::ACTIVITIES as $field) {
            $rules["{$field}_startdate"] = ['nullable', new IsoWeekFormat()];
            $rules["{$field}_enddate"]   = ['nullable', new IsoWeekFormat()];
        }

        return $rules;
    }

    public function messages(): array
    {
        $errors = config('errors');

        return [
            '*.required' => $errors['field_required']['errorMessage'],
            'remarks.max' => $errors['max_length_exceeded']['errorMessage'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateWbsRanges($validator);
        });
    }

private function validateWbsRanges($validator)
{
    $errors = config('errors');
    $activities = [
        'contact_schools',
        'source_testing',
        'initial_interviews',
        'final_interviews',
        'contract_offers',
        'requirements',
        'training',
    ];

    $deploymentDate = $this->input('deployment_date');

    // Stop here if deployment_date is empty or invalid.
    // Let Laravel's normal required/date_format rules handle the error.
    if (empty($deploymentDate) || !preg_match('/^\d{4}-\d{2}$/', $deploymentDate)) {
        return;
    }

    $deploymentMonth = \Carbon\Carbon::createFromFormat('Y-m', $deploymentDate);
    $minimumAllowedDate = $deploymentMonth->copy()->startOfMonth()->subMonths(6);
    $deploymentMonthEnd = $deploymentMonth->copy()->endOfMonth();

    foreach ($activities as $act) {
        $start = $this->input("{$act}_startdate");
        $end   = $this->input("{$act}_enddate");

        $startEmpty = $start === null || trim($start) === '';
        $endEmpty   = $end === null || trim($end) === '';

        if ($startEmpty && $endEmpty) {
            $validator->errors()->add("{$act}_startdate", $errors['field_required']['errorMessage']);
            $validator->errors()->add("{$act}_enddate", $errors['field_required']['errorMessage']);
            continue;
        }

        if (!$startEmpty && $endEmpty) {
            $validator->errors()->add("{$act}_enddate", $errors['field_required']['errorMessage']);
            continue;
        }

        if ($startEmpty && !$endEmpty) {
            $validator->errors()->add("{$act}_startdate", $errors['field_required']['errorMessage']);
            continue;
        }

        if (!preg_match('/^\d{4}-W\d{2}$/', $start)) {
            $validator->errors()->add("{$act}_startdate", $errors['wbs_invalid_format']['errorMessage']);
            continue;
        }

        if (!preg_match('/^\d{4}-W\d{2}$/', $end)) {
            $validator->errors()->add("{$act}_enddate", $errors['wbs_invalid_format']['errorMessage']);
            continue;
        }

        [$startYear, $startWeek] = explode('-W', $start);
        [$endYear, $endWeek]     = explode('-W', $end);

        $startDate = (new \DateTime())->setISODate((int)$startYear, (int)$startWeek);
        $endDate   = (new \DateTime())->setISODate((int)$endYear, (int)$endWeek);

        if ($endDate < $startDate) {
            $validator->errors()->add("{$act}_enddate", $errors['wbs_end_before_start']['errorMessage']);
        }

        if ($startDate < $minimumAllowedDate) {
            $validator->errors()->add("{$act}_startdate", 'Activity cannot start earlier than 6 months before deployment date.');
        }

        if ($endDate < $minimumAllowedDate) {
            $validator->errors()->add("{$act}_enddate", 'Activity cannot end earlier than 6 months before deployment date.');
        }

        if ($startDate > $deploymentMonthEnd) {
            $validator->errors()->add("{$act}_startdate", 'Activity cannot start after the deployment month end.');
        }

        if ($endDate > $deploymentMonthEnd) {
            $validator->errors()->add("{$act}_enddate", 'Activity cannot end after the deployment month end.');
        }
    }
}

    private function isoWeekStartDate(string $week): Carbon
    {
        [$year, $isoWeek] = explode('-W', $week);

        return Carbon::now()->setISODate((int) $year, (int) $isoWeek)->startOfWeek();
    }
}