<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ActionBatchModel;


class ActionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $batchId = $this->route('id') ?? null;

        $previousBatch = null;
        $nextBatch = null;

        if ($batchId) {
            $previousBatch = ActionBatchModel::where('id', '<', $batchId)
                ->orderBy('id', 'desc')
                ->first();

            $nextBatch = ActionBatchModel::where('id', '>', $batchId)
                ->orderBy('id', 'asc')
                ->first();
        } else {
            $previousBatch = ActionBatchModel::orderBy('id', 'desc')->first();
        }

        $minDate = $previousBatch?->target_date;
        $maxDate = $nextBatch?->target_date;

        $rules = [];

        if (!$batchId) {
            $rules['action_batch'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('action_batches', 'action_batch'),
            ];
        }

        $rules['target_trainees'] = 'required|integer|min:1|max:99';

        $rules['target_date'] = [
            'required',
            'date',
            'after_or_equal:today',

            function ($attribute, $value, $fail) use ($batchId, $minDate, $maxDate) {

                if (!$batchId) {
                    if ($minDate && $value < $minDate) {
                        return $fail(config('errors.TARGET_DATE_MIN_ONLY.errorMessage'));
                    }
                }

                if ($batchId) {
                    if ($minDate && $value < $minDate) {
                        return $fail(config('errors.TARGET_DATE_BETWEEN.errorMessage'));
                    }

                    if ($maxDate && $value > $maxDate) {
                        return $fail(config('errors.TARGET_DATE_BETWEEN.errorMessage'));
                    }
                }
            }
        ];

        $rules['remarks'] = 'nullable|string|max:1024';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'action_batch.required' => config('errors.field_required.errorMessage'),
            'action_batch.max' => config('errors.max_length_exceeded.errorMessage'),
            'action_batch.unique' => config('errors.action_batch_unique.errorMessage'),

            'target_trainees.required' => config('errors.field_required.errorMessage'),
            'target_trainees.max' => config('errors.max_length_exceeded.errorMessage'),

            'target_date.required' => config('errors.field_required.errorMessage'),
            'target_date.after_or_equal' => config('errors.target_date_after_or_equal.errorMessage'),

            'remarks.max' => config('errors.max_length_exceeded.errorMessage'),
        ];
    }
}
