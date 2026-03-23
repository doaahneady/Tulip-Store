<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardRolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_key',
        'dashboard_key',
        'can_view',
        'can_edit',
        'sections',
        'actions',
        'can_view_sensitive',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_edit' => 'boolean',
        'sections' => 'array',
        'actions' => 'array',
        'can_view_sensitive' => 'boolean',
    ];
}

