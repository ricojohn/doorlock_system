<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreezeMemberSubscriptionRequest extends FormRequest
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
            'frozen_until' => ['required', 'date', 'after_or_equal:today'],
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
            'frozen_until.required' => 'Please select the date when the freeze should end.',
            'frozen_until.after_or_equal' => 'The freeze end date must be today or a future date.',
        ];
    }
}
