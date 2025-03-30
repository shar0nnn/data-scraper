<?php

namespace App\Http\Requests\Retailer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\File;

class StoreRetailerRequest extends RetailerRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return parent::rules() + [
                'logo' => ['required', 'mimes:jpeg,jpg,png,webp,svg', 'max:5120'],
            ];
    }
}
