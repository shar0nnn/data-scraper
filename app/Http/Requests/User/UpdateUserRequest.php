<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends UserRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        $rules['email'] = [
            'required', 'email', 'max:255',
            Rule::unique('users', 'email')->ignore($this->route('user'))
        ];

        return $rules;
    }
}
