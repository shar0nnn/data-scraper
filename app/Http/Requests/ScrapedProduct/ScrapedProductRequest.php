<?php

namespace App\Http\Requests\ScrapedProduct;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScrapedProductRequest extends FormRequest
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
        return [
            'mpn' => ['required', 'exists:products,manufacturer_part_number'],
            'data' => ['required', 'array'],
            'data.scraped_product' => ['required', 'array'],
            'data.scraped_product.retailer_id' => ['required', 'exists:retailers,id'],
            'data.scraped_product.price' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
            'data.scraped_product.stock_count' => ['nullable', 'integer', 'min:0'],
            'data.scraped_product.rating' => ['nullable', 'array'],
            'data.scraped_images' => ['nullable', 'array'],
            'data.scraped_images.*.url' => ['required', 'string', 'max:1000'],
            'data.scraped_images.*.file_name' => ['required', 'string', 'max:255'],
        ];
    }
}
