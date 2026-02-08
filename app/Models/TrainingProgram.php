<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'trainer', 'start_date', 'end_date',
        'duration_hours', 'location', 'cost', 'max_participants', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration_hours' => 'integer',
        'cost' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }
}
