<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoachRequest extends FormRequest
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
        $coachId = $this->route('coach')->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('coaches', 'email')->ignore($coachId)],
            'phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'house_number' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
            'work_histories' => ['nullable', 'array'],
            'work_histories.*.company_name' => ['nullable', 'string', 'max:255'],
            'work_histories.*.position' => ['nullable', 'string', 'max:255'],
            'work_histories.*.start_date' => ['nullable', 'date'],
            'work_histories.*.end_date' => ['nullable', 'date', 'after_or_equal:work_histories.*.start_date'],
            'work_histories.*.description' => ['nullable', 'string'],
            'certificates' => ['nullable', 'array'],
            'certificates.*.certificate_name' => ['nullable', 'string', 'max:255'],
            'certificates.*.issuing_organization' => ['nullable', 'string', 'max:255'],
            'certificates.*.issue_date' => ['nullable', 'date'],
            'certificates.*.expiry_date' => ['nullable', 'date', 'after_or_equal:certificates.*.issue_date'],
            'certificates.*.certificate_number' => ['nullable', 'string', 'max:255'],
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
        ];
    }
}
