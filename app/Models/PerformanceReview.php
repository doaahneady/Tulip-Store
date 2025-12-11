<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'reviewer_id', 'review_period', 'review_date',
        'performance_score', 'attendance_score', 'quality_score',
        'teamwork_score', 'overall_rating', 'strengths',
        'areas_for_improvement', 'goals', 'comments'
    ];

    protected $casts = [
        'review_date' => 'date',
        'performance_score' => 'integer',
        'attendance_score' => 'integer',
        'quality_score' => 'integer',
        'teamwork_score' => 'integer',
        'overall_rating' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
