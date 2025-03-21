<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreProductRequest extends ProductRequest
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
                'manufacturer_part_number' => [
                    'required', 'string', 'max:255',
                    Rule::unique('products')->where(function (Builder $query) {
                        return $query->where('pack_size_id', request('pack_size_id'));
                    })
                ],
                'images' => ['required', 'array', 'min:1'],
                'images.*' => [File::image()->types(['jpeg', 'jpg', 'png', 'webp'])->max(10 * 1024)],
            ];
    }
}
