<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'manufacturer_part_number' => $this->manufacturer_part_number,
            'pack_size' => $this->packSize->name,
//            'images' => ImageResource::collection($this->images)
        ];
    }
}
