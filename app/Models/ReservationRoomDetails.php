<?php

namespace App\Models;

use App\Http\Traits\LogsActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationRoomDetails extends Model
{
    use HasFactory, SoftDeletes, LogsActions;

    protected $guarded = [];

    protected $casts = [
        'room_details' => 'array'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function addons()
    {
        return $this->hasMany(RoomReservationAddon::class, 'room_reservation_details_id');
    }

    public function otherRooms()
    {
        $otherRooms = self::select('*')
            ->where('reservation_id', $this->reservation_id)
            ->where('room_id', '!=', $this->room_id)
            ->with('room')
            ->get();

        $rooms = [];

        if ($otherRooms->count() > 0) {
            foreach ($otherRooms as $room) {
                $rooms[] = [
                    "reservation_room_details_id" => $room->id,
                    "room_id" => $room->room_id,
                    "category_id" => optional($room->room)->room_category_id,
                    "room_name" => optional($room->room)->name,
                    "guest" => $room->guest
                ];
            }
        }
        return $rooms;
    }

    public function scopePaid($query)
    {
        return $query->whereHas('reservation.payments', function ($q) {
            $q->where('balance', '<=', 0)
                ->whereRaw('created_at = (SELECT MAX(created_at) FROM reservation_payments WHERE reservation_id = reservations.id)');
        });
    }

    public function scopeRoomCategory($query, $category_id)
    {
        return $query->whereHas('room', function ($q) use ($category_id) {
            $q->where('room_category_id', $category_id);
        });
    }
}
