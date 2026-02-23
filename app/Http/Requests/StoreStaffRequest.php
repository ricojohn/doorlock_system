<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'role' => ['required', 'in:admin,coach,frontdesk'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($this->input('role') === 'coach') {
            $rules['phone'] = ['nullable', 'string', 'max:255'];
            $rules['date_of_birth'] = ['nullable', 'date'];
            $rules['gender'] = ['nullable', 'in:male,female,other'];
            $rules['specialty'] = ['nullable', 'string', 'max:255'];
            $rules['house_number'] = ['nullable', 'string', 'max:255'];
            $rules['street'] = ['nullable', 'string', 'max:255'];
            $rules['barangay'] = ['nullable', 'string', 'max:255'];
            $rules['city'] = ['nullable', 'string', 'max:255'];
            $rules['state'] = ['nullable', 'string', 'max:255'];
            $rules['postal_code'] = ['nullable', 'string', 'max:255'];
            $rules['country'] = ['nullable', 'string', 'max:255'];
            $rules['status'] = ['nullable', 'in:active,inactive'];
            $rules['work_histories'] = ['nullable', 'array'];
            $rules['work_histories.*.company_name'] = ['nullable', 'string', 'max:255'];
            $rules['work_histories.*.position'] = ['nullable', 'string', 'max:255'];
            $rules['work_histories.*.start_date'] = ['nullable', 'date'];
            $rules['work_histories.*.end_date'] = ['nullable', 'date', 'after_or_equal:work_histories.*.start_date'];
            $rules['work_histories.*.description'] = ['nullable', 'string'];
            $rules['certificates'] = ['nullable', 'array'];
            $rules['certificates.*.certificate_name'] = ['nullable', 'string', 'max:255'];
            $rules['certificates.*.issuing_organization'] = ['nullable', 'string', 'max:255'];
            $rules['certificates.*.issue_date'] = ['nullable', 'date'];
            $rules['certificates.*.expiry_date'] = ['nullable', 'date', 'after_or_equal:certificates.*.issue_date'];
            $rules['certificates.*.certificate_number'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
