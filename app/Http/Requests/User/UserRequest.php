<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'exists:roles,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'retailers' => ['nullable', 'array'],
            'retailers.*' => ['exists:retailers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'retailers.*.exists' => __('Selected retailer(s) does not exist.'),
        ];
    }
}
