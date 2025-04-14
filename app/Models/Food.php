<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory, SoftDeletes;

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
}
