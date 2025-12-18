<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'department',
        'description',
        'requirements',
        'salary_min',
        'salary_max',
        'employment_type',
        'status',
        'hiring_manager_id',
        'application_deadline',
    ];

    protected $casts = [
        'requirements' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'application_deadline' => 'date',
    ];

    public function hiringManager()
    {
        return $this->belongsTo(User::class, 'hiring_manager_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'position_id');
    }
}