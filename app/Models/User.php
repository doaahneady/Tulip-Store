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
        'is_hr',
        'is_cs',
        'is_finance',
        'country',
        'is_accountant',
        'is_driver_supervisor',
        'role_id',
        'locked_at',
        'locked_until',
        'lock_reason',
        'login_failures',
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
            'locked_at' => 'datetime',
            'locked_until' => 'datetime',
            'login_failures' => 'integer',
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
     * Get the roles for the user (many-to-many).
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot(['assigned_at', 'assigned_by', 'expires_at', 'is_active'])
            ->wherePivot('is_active', true);
    }

    /**
     * Get the role for the user (legacy support).
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the employee record for the user.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the driver record for the user.
     */
    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * Get the store owned by the user.
     */
    public function ownedStore()
    {
        $fk = \Illuminate\Support\Facades\Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id';

        return $this->hasOne(Store::class, $fk);
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
        return $this->roles->contains(function ($role) use ($permission) {
            return $role->hasPermission($permission);
        });
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_array($role)) {
            return $this->roles->whereIn('name', $role)->isNotEmpty();
        }

        return false;
    }

    /**
     * Check if user has any of the specified roles.
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }

        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole($role, $assignedBy = null)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role && ! $this->hasRole($role->name)) {
            $this->roles()->attach($role->id, [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }
}
