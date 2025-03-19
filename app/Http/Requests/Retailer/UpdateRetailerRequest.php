<?php

namespace App\Http\Requests\Retailer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\File;

class UpdateRetailerRequest extends RetailerRequest
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
                'logo' => ['nullable', File::image()->types(['jpeg', 'jpg', 'png', 'webp'])->max(5 * 1024)],
            ];
    }
}
