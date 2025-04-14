<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->price);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($po) {
            $customer = $po->customer;
            $customer->current_balance -= $po->total_amount;
            $customer->save();
        });
    }
}
