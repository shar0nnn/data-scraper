<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreProductRequest extends ProductRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Product::class);
    }

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
