<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            $slug = Str::slug($inventory->item_name);

            $count = Inventory::where('item_code', 'LIKE', "$slug%")->count();
            $inventory->item_code = $count ? "{$slug}-" . ($count + 1) : $slug;

            $inventory->total_cost = $inventory->quantity * $inventory->unit_price;
        });

        static::updating(function ($inventory) {
            if ($inventory->isDirty('item_name')) {
                $slug = Str::slug($inventory->item_name);
                $count = Inventory::where('item_code', 'LIKE', "$slug%")->where('id', '!=', $inventory->id)->count();
                $inventory->item_code = $count ? "{$slug}-" . ($count + 1) : $slug;
            }

            $inventory->total_cost = $inventory->quantity * $inventory->unit_price;
        });

        static::created(function ($inventory) {
            InventoryTransaction::create([
                'inventory_id'     => $inventory->id,
                'quantity_used'    => $inventory->quantity, // Initial stock
                'remaining_amount' => $inventory->quantity,
                'used_by'          => 'System', // Since no one took it yet
                'user_id'          => auth()->id(), // The user who created the inventory
                'used_at'          => now(),
                'transaction_type' => 'addition',
                'previous_quantity' => 0
            ]);
        });

        static::updated(function ($inventory) {
            if ($inventory->isDirty('quantity')) {
                $previousQuantity = $inventory->getOriginal('quantity');
                $newQuantity = $inventory->quantity;
                $quantityChange = $newQuantity - $previousQuantity;

                InventoryTransaction::create([
                    'inventory_id'     => $inventory->id,
                    'quantity_used'    => $quantityChange,
                    'remaining_amount' => $newQuantity,
                    'used_by'          => request()->input('used_by', 'Unknown'),
                    'user_id'          => auth()->id(),
                    'used_at'          => now(),
                    'transaction_type' => $quantityChange > 0 ? 'addition' : 'deduction',
                    'previous_quantity' => $previousQuantity,
                ]);
            }
        });
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_id');
    }
}
