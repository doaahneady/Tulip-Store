<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_type',
        'period',
        'file_url',
        'filed_by',
        'filed_at',
    ];

    protected $casts = [
        'filed_at' => 'datetime',
    ];

    public function filedBy()
    {
        return $this->belongsTo(Employee::class, 'filed_by');
    }
}
