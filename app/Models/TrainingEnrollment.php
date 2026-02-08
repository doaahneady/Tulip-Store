<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_program_id', 'employee_id', 'status', 'score', 'feedback',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
