<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
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
            'member_id' => ['required', 'exists:members,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'plan_name' => ['nullable', 'string', 'max:255', 'required_without:plan_id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:active,expired,cancelled'],
            'payment_status' => ['nullable', 'in:paid,pending,overdue'],
            'notes' => ['nullable', 'string'],
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
            'member_id.required' => 'Please select a member.',
            'member_id.exists' => 'The selected member does not exist.',
            'plan_name.required' => 'Plan name is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please enter a valid start date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after' => 'End date must be after start date.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'status.in' => 'Please select a valid status.',
            'payment_status.in' => 'Please select a valid payment status.',
        ];
    }
}
