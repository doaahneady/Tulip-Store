<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'resume_data',
        'cover_letter',
        'attachments',
        'status',
        'interview_notes',
        'rating',
    ];

    protected $casts = [
        'resume_data' => 'array',
        'attachments' => 'array',
        'interview_notes' => 'array',
        'rating' => 'decimal:2',
    ];

    public function position()
    {
        return $this->belongsTo(JobPosition::class);
    }
}
