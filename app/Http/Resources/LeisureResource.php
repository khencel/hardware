<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeisureResource extends JsonResource
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
            'item_name' => $this->item_name,
            'type' => $this->type,
            'price_rate' => $this->price_rate,
            'counter' => $this->counter,
            'package' => optional($this->package)->display_name,
            'image' => $this->image ? url('storage/' . $this->image) : null, // Full URL to image
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'availability' => $this->availability
        ];
    }
}
