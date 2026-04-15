<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsoWeekFormat;
use Carbon\Carbon;

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
            $rules["{$field}_startdate"] = ['nullable', new IsoWeekFormat()];

            if ($field !== 'training') {
                $rules["{$field}_enddate"] = ['nullable', new IsoWeekFormat()];
            }
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

    private function validateWbsRanges($validator): void
    {
        $errors = config('errors');
        $deploymentDate = $this->input('deployment_date');

        if (empty($deploymentDate) || !preg_match('/^\d{4}-\d{2}$/', $deploymentDate)) {
            return;
        }

        $deploymentMonth = Carbon::createFromFormat('Y-m', $deploymentDate);
        $minimumAllowedDate = $deploymentMonth->copy()->startOfMonth()->subMonths(6);
        $deploymentMonthStart = $deploymentMonth->copy()->startOfMonth();
        $deploymentMonthEnd = $deploymentMonth->copy()->endOfMonth();

        $activities = [
            'contact_schools',
            'source_testing',
            'initial_interviews',
            'final_interviews',
            'contract_offers',
            'requirements',
            'training',
        ];

        $previousStartDate = null;
        $previousEndDate = null;
        $previousActivityLabel = null;


        foreach ($activities as $act) {
            $start = $this->input("{$act}_startdate");
            $end   = $this->input("{$act}_enddate");

            $startEmpty = $start === null || trim($start) === '';
            $endEmpty   = $end === null || trim($end) === '';

            if ($act === 'training') {
                if ($startEmpty) {
                    $validator->errors()->add("{$act}_startdate", $errors['field_required']['errorMessage']);
                    continue;
                }

                if (!preg_match('/^\d{4}-W\d{2}$/', $start)) {
                    $validator->errors()->add("{$act}_startdate", $errors['wbs_invalid_format']['errorMessage']);
                    continue;
                }

                [$startYear, $startWeek] = explode('-W', $start);

                $weekStart = Carbon::now()
                    ->setISODate((int)$startYear, (int)$startWeek)
                    ->startOfWeek();

                $weekEnd = $weekStart->copy()->endOfWeek();

                if ($previousStartDate && $weekStart->lt($previousStartDate)) {
                    $validator->errors()->add(
                        "{$act}_startdate",
                        ucfirst(str_replace('_', ' ', $act)) . " cannot start before {$previousActivityLabel}."
                    );
                }
                if ($previousEndDate && $weekStart->lt($previousEndDate)) {
                    $validator->errors()->add(
                        "{$act}_startdate",
                        ucfirst(str_replace('_', ' ', $act)) . " cannot start before {$previousActivityLabel} ends."
                    );
                }

                $overlapsDeploymentMonth = $weekStart->lte($deploymentMonthEnd) && $weekEnd->gte($deploymentMonthStart);

                if (!$overlapsDeploymentMonth) {
                    $validator->errors()->add(
                        "{$act}_startdate",
                        'Training start must fall within or overlap the deployment month.'
                    );
                }

                continue;
            }

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
            [$endYear, $endWeek] = explode('-W', $end);

            $startDate = Carbon::now()->setISODate((int)$startYear, (int)$startWeek)->startOfWeek();
            $endDate = Carbon::now()->setISODate((int)$endYear, (int)$endWeek)->endOfWeek();

            if ($endDate->lt($startDate)) {
                $validator->errors()->add("{$act}_enddate", $errors['wbs_end_before_start']['errorMessage']);
                continue;
            }

            if ($endDate->lt($minimumAllowedDate)) {
                $validator->errors()->add(
                    "{$act}_startdate",
                    'Activity cannot start earlier than 6 months before deployment date.'
                );
            }

            if ($endDate->lt($minimumAllowedDate)) {
                $validator->errors()->add(
                    "{$act}_enddate",
                    'Activity cannot end earlier than 6 months before deployment date.'
                );
            }

            if ($startDate->gt($deploymentMonthEnd)) {
                $validator->errors()->add(
                    "{$act}_startdate",
                    'Activity cannot start after the deployment month end.'
                );
            }

            if ($endDate->gt($deploymentMonthEnd)) {
                $validator->errors()->add(
                    "{$act}_enddate",
                    'Activity cannot end after the deployment month end.'
                );
            }

            if ($previousStartDate && $startDate->lt($previousStartDate)) {
                $validator->errors()->add(
                    "{$act}_startdate",
                    ucfirst(str_replace('_', ' ', $act)) . " cannot start before {$previousActivityLabel}."
                );
            }
            if ($previousEndDate && $endDate->lt($previousEndDate)) {
    $validator->errors()->add(
        "{$act}_enddate",
        ucfirst(str_replace('_', ' ', $act)) . " cannot end before {$previousActivityLabel} ends."
    );
}

            $previousStartDate = $startDate;
            $previousEndDate = $endDate;
            $previousActivityLabel = str_replace('_', ' ', $act);
        }
    }
}
