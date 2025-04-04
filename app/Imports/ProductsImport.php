<?php

namespace App\Imports;

use App\Models\PackSize;
use App\Models\Product;
use App\Traits\HasImportStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, WithEvents
{
    use RegistersEventListeners, HasImportStats;

    public function model(array $row): Model|Product|null
    {
        $this->fileRows++;
        $packSize = PackSize::query()->where('name', $row['pack_size'])->pluck('id')->first();
        if (!$packSize) {
            $packSize = PackSize::query()->create(
                ['name' => $row['pack_size']]
            )->id;
        }

        $validator = Validator::make([
            'manufacturer_part_number' => $row['manufacturer_part_number'],
        ], [
            'manufacturer_part_number' => Rule::unique('products')->where(function ($query) use ($packSize) {
                return $query->where('pack_size_id', $packSize);
            })
        ]);

        if ($validator->fails()) {
            return null;
        }

        $this->dbRows++;

        return new Product([
            'title' => $row['title'],
            'description' => $row['description'],
            'manufacturer_part_number' => $row['manufacturer_part_number'],
            'pack_size_id' => $packSize,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'manufacturer_part_number' => ['required', 'max:255'],
            'pack_size' => ['required', 'string', 'max:255'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'title.required' => __('messages.The field is required.', ['field' => 'title', 'row' => $this->fileRows + 2]),
            'title.string' => __('messages.The field must be a string.', ['field' => 'title', 'row' => $this->fileRows + 2]),
            'title.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'title', 'row' => $this->fileRows + 2]),
            'description.string' => __('messages.The field must be a string.', ['field' => 'description', 'row' => $this->fileRows + 2]),
            'manufacturer_part_number.required' => __('messages.The field is required.', ['field' => 'manufacturer part number', 'row' => $this->fileRows + 2]),
            'manufacturer_part_number.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'manufacturer part number', 'row' => $this->fileRows + 2]),
            'pack_size.required' => __('messages.The field is required.', ['field' => 'pack size', 'row' => $this->fileRows + 2]),
            'pack_size.string' => __('messages.The field must be a string.', ['field' => 'pack size', 'row' => $this->fileRows + 2]),
            'pack_size.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'pack size', 'row' => $this->fileRows + 2]),
        ];
    }
}
