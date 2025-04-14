<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->purchaseOrder->customer->decrement('current_balance', $item->quantity * $item->price);
        });

        static::deleted(function ($item) {
            $item->purchaseOrder->customer->increment('current_balance', $item->quantity * $item->price);
        });
    }
}
