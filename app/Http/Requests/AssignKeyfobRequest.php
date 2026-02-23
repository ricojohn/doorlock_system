<?php

namespace App\Http\Requests;

use App\Models\RfidCard;
use Illuminate\Foundation\Http\FormRequest;

class AssignKeyfobRequest extends FormRequest
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
            'keyfob_id' => [
                'required',
                'exists:rfid_cards,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $keyfob = RfidCard::find($value);
                    if ($keyfob && $keyfob->member_id !== null) {
                        $fail('This keyfob is already assigned to another member.');
                    }
                },
            ],
            'price' => ['nullable', 'numeric', 'min:0'],
            'issued_at' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
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
            'keyfob_id.required' => 'Please select a keyfob.',
            'keyfob_id.exists' => 'Selected keyfob not found.',
            'issued_at.required' => 'Issued date is required.',
        ];
    }
}
