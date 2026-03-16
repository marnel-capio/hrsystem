<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsoWeekFormat;

class ResourceScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $wbsFields = [
            'contact_schools',
            'source_testing',
            'initial_interviews',
            'final_interviews',
            'contract_offers',
            'requirements',
            'training',
        ];

        $rules = [
            'action_batch_id' => 'required|exists:action_batches,id',
            'prev_batch_id'   => 'nullable|exists:action_batches,id',
            'target_location' => 'required|string|max:255',
            'target_trainees' => 'required|integer|min:1',
            'deployment_date' => 'required|date_format:Y-m',
            'remarks'         => 'nullable|string|max:1024',
        ];

        // ✅ Remove 'required' from WBS fields - handled in validateWbsRanges()
        foreach ($wbsFields as $field) {
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

        foreach ($activities as $act) {
            $start = $this->input("{$act}_startdate");
            $end   = $this->input("{$act}_enddate");

            // Treat null or empty string as empty
            $startEmpty = $start === null || trim($start) === '';
            $endEmpty   = $end === null || trim($end) === '';

            // Scenario 1: Both Start & End are empty
            if ($startEmpty && $endEmpty) {
                $validator->errors()->add("{$act}_startdate", $errors['field_required']['errorMessage']);
                $validator->errors()->add("{$act}_enddate", $errors['field_required']['errorMessage']);
                continue;
            }

            // Scenario 2: End is empty only (Start is filled)
            if (!$startEmpty && $endEmpty) {
                $validator->errors()->add("{$act}_enddate", $errors['field_required']['errorMessage']);
                continue;
            }

            // Scenario 3: Start is empty only (End is filled)
            if ($startEmpty && !$endEmpty) {
                $validator->errors()->add("{$act}_startdate", $errors['wbs_end_before_start']['errorMessage']);
                continue;
            }

            // Scenario 4: Both are filled - Check Format
            if (!preg_match('/^\d{4}-W\d{2}$/', $start)) {
                $validator->errors()->add("{$act}_startdate", $errors['wbs_invalid_format']['errorMessage']);
                continue;
            }
            if (!preg_match('/^\d{4}-W\d{2}$/', $end)) {
                $validator->errors()->add("{$act}_enddate", $errors['wbs_invalid_format']['errorMessage']);
                continue;
            }

            // Scenario 5: Both are filled - Check Date Range
            [$startYear, $startWeek] = explode('-W', $start);
            [$endYear, $endWeek]     = explode('-W', $end);

            $startDate = (new \DateTime())->setISODate((int)$startYear, (int)$startWeek);
            $endDate   = (new \DateTime())->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                $validator->errors()->add("{$act}_enddate", $errors['wbs_end_before_start']['errorMessage']);
            }
        }
    }
}