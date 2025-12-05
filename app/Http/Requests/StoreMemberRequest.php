<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'subscription_plan_id' => ['nullable', 'exists:plans,id'],
            'subscription_start_date' => ['nullable', 'date', 'required_with:subscription_plan_id'],
            'subscription_end_date' => ['nullable', 'date', 'after:subscription_start_date', 'required_with:subscription_plan_id'],
            'subscription_price' => ['nullable', 'numeric', 'min:0', 'required_with:subscription_plan_id'],
            'subscription_status' => ['nullable', 'in:active,expired,cancelled'],
            'subscription_payment_status' => ['nullable', 'in:paid,pending,overdue'],
            'subscription_notes' => ['nullable', 'string'],
            'keyfob_id' => ['nullable', 'exists:rfid_cards,id'],
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
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'gender.in' => 'Please select a valid gender.',
        ];
    }
}
