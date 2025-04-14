<?php

namespace App\Models;

use App\Http\Traits\LogsActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomReservationAddon extends Model
{
    use HasFactory, LogsActions, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'addon_details' => 'array'
    ];

    public function reservationRoomDetails()
    {
        return $this->belongsTo(ReservationRoomDetails::class, 'room_reservation_details_id');
    }
}
