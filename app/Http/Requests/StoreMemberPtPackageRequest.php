<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberPtPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pt_package_id' => ['required', 'exists:pt_packages,id'],
            'coach_id' => ['nullable', 'exists:coaches,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_type' => ['nullable', 'string', 'max:50'],
            'receipt_number' => ['nullable', 'string', 'max:100'],
            'receipt_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $package = $this->route('member') ? \App\Models\PtPackage::find($this->input('pt_package_id')) : null;
            if ($package && $package->status !== 'active') {
                $validator->errors()->add('pt_package_id', 'Selected PT package is not active.');
            }
        });
    }
}
