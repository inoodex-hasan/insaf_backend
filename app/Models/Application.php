<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'application_id',
        'student_id',
        'university_id',
        'course_id',
        'course_intake_id',
        'tuition_fee',
        'total_fee',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($application) {
            $lastApplication = static::whereYear('created_at', date('Y'))->latest('id')->first();
            $nextNumber = $lastApplication ? ((int) substr($lastApplication->application_id, -5)) + 1 : 1;
            $application->application_id = 'APP-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function intake()
    {
        return $this->belongsTo(CourseIntake::class, 'course_intake_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
