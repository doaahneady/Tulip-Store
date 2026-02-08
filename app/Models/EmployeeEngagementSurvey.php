<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEngagementSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'survey_period',
        'job_satisfaction',
        'work_life_balance',
        'management_rating',
        'team_collaboration',
        'career_growth',
        'overall_score',
        'comments',
        'submitted_at',
    ];

    protected $casts = [
        'job_satisfaction' => 'integer',
        'work_life_balance' => 'integer',
        'management_rating' => 'integer',
        'team_collaboration' => 'integer',
        'career_growth' => 'integer',
        'overall_score' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateOverallScore()
    {
        $scores = array_filter([
            $this->job_satisfaction,
            $this->work_life_balance,
            $this->management_rating,
            $this->team_collaboration,
            $this->career_growth,
        ]);

        if (empty($scores)) {
            return 0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($survey) {
            $survey->overall_score = $survey->calculateOverallScore();
        });
    }
}
