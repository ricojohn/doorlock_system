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
        // If subscription_id is present, it's for adding to member
        if ($this->has('subscription_id')) {
            return [
                'subscription_id' => ['required', 'exists:subscriptions,id'],
                'subscription_type' => ['required', 'in:New,Renewal'],
                'start_date' => ['required', 'date'],
                'price' => ['nullable', 'numeric', 'min:0'],
                'payment_type' => ['nullable', 'string', 'max:255'],
                'payment_status' => ['nullable', 'in:paid,pending,overdue'],
                'notes' => ['nullable', 'string'],
            ];
        }

        // Otherwise, it's creating a subscription template
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
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
            'name.required' => 'Subscription name is required.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'duration_months.required' => 'Duration in months is required.',
            'duration_months.integer' => 'Duration must be a whole number.',
            'duration_months.min' => 'Duration must be at least 1 month.',
            'subscription_id.required' => 'Please select a subscription.',
            'subscription_type.required' => 'Subscription type is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please enter a valid start date.',
        ];
    }
}
