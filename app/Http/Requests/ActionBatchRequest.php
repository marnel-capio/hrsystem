<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $batchId = $this->route('id') ?? null;

        return [
            'action_batch' => [
                'required',
                'string',
                'max:20',
                $batchId
                    ? Rule::unique('action_batches', 'action_batch')->ignore($batchId)
                    : Rule::unique('action_batches', 'action_batch'),
            ],
            'target_trainees' => 'required|integer|max:99',
            'target_date' => 'required|date|after_or_equal:today',
            'remarks' => 'nullable|string|max:1024',
        ];
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
