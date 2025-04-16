<?php

namespace App\Http\Requests\User;

class StoreUserRequest extends UserRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        $rules['password'] = ['required', 'string', 'max:255'];
        $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email'];

        return $rules;
    }
}
