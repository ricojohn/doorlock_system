<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRfidCardRequest extends FormRequest
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
        $rfidCard = $this->route('rfid_card');

        return [
            'member_id' => ['nullable', 'exists:members,id'],
            'card_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rfid_cards', 'card_number')->ignore($rfidCard->id),
            ],
            'type' => ['required', 'in:card,keyfob'],
            'status' => ['nullable', 'in:active,inactive,lost,stolen'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'issued_at' => ['required', 'date'],
            'expires_at' => ['nullable', 'date', 'after:issued_at'],
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
            'member_id.exists' => 'The selected member does not exist.',
            'card_number.required' => 'Card number is required.',
            'card_number.unique' => 'This card number is already registered.',
            'type.required' => 'Please select a card type.',
            'type.in' => 'Please select a valid card type.',
            'status.in' => 'Please select a valid status.',
            'issued_at.required' => 'Issue date is required.',
            'issued_at.date' => 'Please enter a valid issue date.',
            'expires_at.date' => 'Please enter a valid expiration date.',
            'expires_at.after' => 'Expiration date must be after issue date.',
        ];
    }
}
