<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Role;
use App\Http\Traits\UpdateUser;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use UpdateUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    /**
     * Check if user has any of the specified roles.
     *
     * @param  \App\Models\User  $user
     * @param  array  $roles
     * @return bool
     */
    protected function userHasAnyRole($user, $roles)
    {
        return $user->roles()->whereIn('name', $roles)->exists();
    }

    public function scopeHasRole($query, $user_ids)
    {
        return $query->whereHas('roles', function ($query) use ($user_ids) {
            return $query->whereIn('role_id', $user_ids);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full name (firstname + lastname).
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get the user's role (you can modify this as per your needs).
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return $this->roles->isEmpty()
            ? 'No Role'
            : $this->roles->first()->name;
    }

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? 'Active' : 'Inactive';
    }
}
