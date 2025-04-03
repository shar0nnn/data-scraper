<?php

namespace App\Imports;

use App\Models\PackSize;
use App\Models\Product;
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
    use RegistersEventListeners;

    private int $fileRows = 0;
    private int $dbRows = 0;
    private int $memoryUsage = 0;
    private float $executionTime = 0;

    public function model(array $row): Model|Product|null
    {
        $this->fileRows++;
        $this->memoryUsage = memory_get_usage();
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
            'title.required' => __('messages.The field is required.', ['field' => 'title']),
            'title.string' => __('messages.The field must be a string.', ['field' => 'title']),
            'title.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'title']),
            'description.string' => __('messages.The field must be a string.', ['field' => 'description']),
            'manufacturer_part_number.required' => __('messages.The field is required.', ['field' => 'manufacturer part number']),
            'manufacturer_part_number.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'manufacturer part number']),
            'pack_size.required' => __('messages.The field is required.', ['field' => 'pack size']),
            'pack_size.string' => __('messages.The field must be a string.', ['field' => 'pack size']),
            'pack_size.max' => __('messages.The field is too long.', ['max' => 255, 'field' => 'pack size']),
        ];
    }

    public function beforeImport(): void
    {
        $this->memoryUsage = memory_get_usage();
        $this->executionTime = microtime(true);
    }

    public function afterImport(): void
    {
        $this->memoryUsage = memory_get_peak_usage() - $this->memoryUsage;
        $this->executionTime = microtime(true) - $this->executionTime;
    }

    public function getFileRows(): int
    {
        return $this->fileRows;
    }

    public function getDBRows(): int
    {
        return $this->dbRows;
    }

    public function getMemoryUsage(): string
    {
        $memoryUsage = round($this->memoryUsage / 1024, 2);

        return "$memoryUsage KB";
    }

    public function getExecutionTime(): string
    {
        $executionTime = round($this->executionTime, 2);

        return "$executionTime seconds";
    }
}
