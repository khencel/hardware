<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationPayments extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'payment_method' => 'json'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function addons()
    {
        return $this->hasMany(RoomReservationAddon::class, 'reservation_payment_id');
    }
}
