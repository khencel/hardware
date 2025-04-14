<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReservationDetailsResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "reservation_room_details_id" => $this->id,
            "name" => optional($this->reservation)->name,
            "room" => optional($this->room)->name,
            "room_id" => $this->room_id,
            "reservation_id" => $this->reservation_id,
            "email" => optional($this->reservation)->email,
            "address" => optional($this->reservation)->address,
            "phone" => optional($this->reservation)->phone,
            "nationality" => optional($this->reservation)->nationality,
            "type" => optional($this->reservation)->type,
            "start_date" => $this->check_in_date,
            "end_date" => $this->check_out_date,
            "remarks" => optional($this->reservation)->remarks,
            "status" => $this->status,
            "guest" => $this->guest,
            "other_rooms" => $this->otherRooms(),
            "add_ons" => optional($this->reservation)->addons
        ];
    }
}
