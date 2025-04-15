<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'location' => $this->location?->name,
            'role' => $this->role->name,
            'retailers' => $this->whenLoaded('retailers', function () {
                return $this->retailers->map(fn($retailer) => [
                    'id' => $retailer->id,
                    'title' => $retailer->title,
                ]);
            }),

        ];
    }
}
