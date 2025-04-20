<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

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
