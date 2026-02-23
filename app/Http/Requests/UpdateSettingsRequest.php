<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
        return [
            'app_name' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,gif', 'max:512'],
            'primary_color' => ['required', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'theme_mode' => ['required', 'string', 'in:light,dark,system'],
        ];
    }

    public function messages(): array
    {
        return [
            'app_name.required' => 'App name is required.',
            'primary_color.regex' => 'Primary color must be a valid hex (e.g. #4154f1).',
            'theme_mode.in' => 'Theme must be Light, Dark, or System.',
        ];
    }
}
