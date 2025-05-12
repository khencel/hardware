<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FoodCategory extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class, 'category_id');
    }

    /**
     * Configure activity log options for the FoodCategory model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('item category') 
            ->logOnly(['name', 'is_available']) 
            ->logOnlyDirty() 
            ->dontSubmitEmptyLogs(); 
    }
}
