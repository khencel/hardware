<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leisure extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
