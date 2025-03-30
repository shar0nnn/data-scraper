<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pack_size_id' => ['required', 'exists:pack_sizes,id'],
            'retailers' => ['nullable', 'array'],
            'retailers.*.id' => ['required_with:retailers.*.url', 'exists:retailers,id'],
            'retailers.*.url' => ['required_with:retailers.*.id', 'string', 'max:1000'],
        ];
    }
}
