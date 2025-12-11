<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'user_full_name',
        'mobile',
        'phone',
        'birth_date',
        'address',
        'language',
        'gender',
        'currency',
        'verified',
        'google_id',
        'is_trader',
        'is_admin',
        'is_it_super',
        'is_it',
        'role_id',
    ];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verified' => 'boolean',
            'is_trader' => 'boolean',
        ];
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the role for the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the support tickets created by the user.
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the support tickets assigned to the user (CS Agent).
     */
    public function assignedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    /**
     * Get the customer feedback submitted by the user.
     */
    public function customerFeedback()
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }
}
