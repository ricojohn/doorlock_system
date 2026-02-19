<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePtPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'package_rate' => ['required', 'numeric', 'min:0'],
            'session_count' => ['required', 'integer', 'min:1'],
            'rate_per_session' => ['nullable', 'numeric', 'min:0'],
            'commission_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'commission_per_session' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'package_type' => ['required', 'in:New,Renewal'],
            'coach_id' => ['nullable', 'exists:coaches,id'],
            'status' => ['nullable', 'in:active,inactive'],
            'payment_type' => ['nullable', 'string', 'max:255'],
            'exercises' => ['nullable', 'array'],
            'exercises.*.exercise_name' => ['nullable', 'string', 'max:255'],
            'exercises.*.sets' => ['nullable', 'integer', 'min:0'],
            'exercises.*.reps' => ['nullable', 'integer', 'min:0'],
            'exercises.*.weight' => ['nullable', 'numeric', 'min:0'],
            'exercises.*.duration_minutes' => ['nullable', 'integer', 'min:0'],
            'exercises.*.rest_period_seconds' => ['nullable', 'integer', 'min:0'],
            'exercises.*.notes' => ['nullable', 'string'],
        ];
    }
}
