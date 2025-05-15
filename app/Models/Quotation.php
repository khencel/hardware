<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'array',
        'date' => 'datetime',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    } 
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
