<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Loggable;


class Food extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected $table = 'foods';

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('items')
            ->logOnly(['name', 'price', 'category_id', 'margin_percentage','is_available', 'description','cost_price','wholesale_price','retail_price', 'barcode','quantity']) 
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs(); 
    }
}
