<?php

namespace App\Imports;

use App\Models\PackSize;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class ProductsImport extends SpreadsheetImport implements WithHeadingRow, SkipsEmptyRows, OnEachRow
{
    public function __construct()
    {
        parent::__construct();
        $this->skipRows = 1;
        $this->rowNumber = $this->skipRows;
    }

    /**
     * @throws ValidationException
     */
    public function onRow(Row $row): void
    {
        $this->rowNumber++;
        $row = $row->toArray();

        $this->validateRow($row);

        $packSize = PackSize::query()->where('name', $row['pack_size'])->pluck('id')->first();
        if (!$packSize) {
            $packSize = PackSize::query()->create(
                ['name' => $row['pack_size']]
            )->id;
        }

        Product::query()->updateOrCreate(
            [
                'manufacturer_part_number' => $row['manufacturer_part_number'],
                'pack_size_id' => $packSize
            ],
            [
                'title' => $row['title'],
                'description' => $row['description'],
                'manufacturer_part_number' => $row['manufacturer_part_number'],
                'pack_size_id' => $packSize,
            ]
        );
    }

    /**
     * @throws ValidationException
     */
    private function validateRow($row): void
    {
        Validator::make($row, $this->rules(), $this->messages())->stopOnFirstFailure()->validate();
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'manufacturer_part_number' => ['required', 'max:255'],
            'pack_size' => ['required', 'string', 'max:255'],
        ];
    }

    private function messages(): array
    {
        return [
            'title.required' => __('messages.The field is required.', ['field' => 'title', 'row' => $this->getRowNumber()]),
            'title.string' => __('messages.The field must be a string.', ['field' => 'title', 'row' => $this->getRowNumber()]),
            'title.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'title', 'row' => $this->getRowNumber()]),
            'description.string' => __('messages.The field must be a string.', ['field' => 'description', 'row' => $this->getRowNumber()]),
            'manufacturer_part_number.required' => __('messages.The field is required.', ['field' => 'manufacturer part number', 'row' => $this->getRowNumber()]),
            'manufacturer_part_number.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'manufacturer part number', 'row' => $this->getRowNumber()]),
            'pack_size.required' => __('messages.The field is required.', ['field' => 'pack size', 'row' => $this->getRowNumber()]),
            'pack_size.string' => __('messages.The field must be a string.', ['field' => 'pack size', 'row' => $this->getRowNumber()]),
            'pack_size.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'pack size', 'row' => $this->getRowNumber()]),
        ];
    }

    public function getProductNumber(): int
    {
        return $this->rowNumber - $this->skipRows;
    }
}
