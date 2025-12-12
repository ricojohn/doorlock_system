<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePtSessionPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'coach_id' => ['required', 'exists:coaches,id'],
            'member_id' => ['required', 'exists:members,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:active,completed,cancelled'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.exercise_name' => ['nullable', 'string', 'max:255'],
            'items.*.sets' => ['nullable', 'integer', 'min:1'],
            'items.*.reps' => ['nullable', 'integer', 'min:1'],
            'items.*.weight' => ['nullable', 'numeric', 'min:0'],
            'items.*.duration_minutes' => ['nullable', 'integer', 'min:1'],
            'items.*.rest_period_seconds' => ['nullable', 'integer', 'min:0'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'coach_id.required' => 'Please select a coach.',
            'coach_id.exists' => 'Selected coach does not exist.',
            'member_id.required' => 'Please select a member.',
            'member_id.exists' => 'Selected member does not exist.',
            'name.required' => 'Plan name is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please enter a valid start date.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }
}
