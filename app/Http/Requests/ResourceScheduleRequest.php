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

        foreach ($wbsFields as $field) {
            $rules["{$field}_startdate"] = ['required', new IsoWeekFormat()];
            $rules["{$field}_enddate"]   = ['required', new IsoWeekFormat()];
        }

        return $rules;
    }

    public function messages(): array
    {
        $errors = config('errors');

        return [
            '*.required' => $errors['field_required']['errorMessage'],
            'target_trainees.min' => $errors['target_trainees_min']['errorMessage'],
            'deployment_date.date_format' => $errors['deployment_date_format']['errorMessage'],
            'remarks.max' => 'This field must not exceed 1024 characters.',
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

            [$startYear, $startWeek] = explode('-W', $start);
            [$endYear, $endWeek]     = explode('-W', $end);

            $startDate = (new \DateTime())->setISODate((int)$startYear, (int)$startWeek);
            $endDate   = (new \DateTime())->setISODate((int)$endYear, (int)$endWeek);

            if ($endDate < $startDate) {
                $validator->errors()->add(
                    "{$act}_enddate",
                    config('errors.wbs_end_before_start.errorMessage')
                );            
            }
        }
    }
}