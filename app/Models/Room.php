<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'room_reservation')->withTimestamps();
    }

    public function reservationRoomDetails()
    {
        return $this->hasMany(ReservationRoomDetails::class, 'room_id');
    }
}
