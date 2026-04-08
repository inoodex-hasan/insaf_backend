<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'degree_level',
        'duration',
        'tuition_fee',
        'status'
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function intakes()
    {
        return $this->hasMany(CourseIntake::class);
    }

}

