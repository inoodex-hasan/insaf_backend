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
        'offer_letter_received',
        'offer_letter_received_date',
        'vfs_appointment',
        'vfs_appointment_date',
        'file_submission',
        'file_submission_date',
        'visa_status',
        'visa_decision_date',
        'visa_approval_date',
        'tuition_fee_status',
        'service_charge_status',
        'application_priority',
        'internal_notes',
        'documents_checklist',
        'final_status',
        'security_deposit_status',
        'cvu_fee_status',
        'admission_fee_status',
        'final_payment_status',
        'emgs_payment_status',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'offer_letter_received' => 'boolean',
        'vfs_appointment' => 'boolean',
        'file_submission' => 'boolean',
        'security_deposit_status' => 'boolean',
        'cvu_fee_status' => 'boolean',
        'admission_fee_status' => 'boolean',
        'final_payment_status' => 'boolean',
        'emgs_payment_status' => 'boolean',
        'offer_letter_received_date' => 'date',
        'vfs_appointment_date' => 'date',
        'file_submission_date' => 'date',
        'visa_decision_date' => 'date',
        'visa_approval_date' => 'date',
        'documents_checklist' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($application) {
            $lastApplication = static::whereYear('created_at', date('Y'))->latest('id')->first();
            $nextNumber = $lastApplication ? ((int) substr($lastApplication->application_id, -5)) + 1 : 1;
            $application->application_id = 'APP-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });

        static::saving(function ($application) {
            // Auto-set dates when boolean fields are toggled
            if ($application->offer_letter_received) {
                $application->offer_letter_received_date = $application->offer_letter_received_date ?? now();
            } else {
                $application->offer_letter_received_date = null;
            }

            if ($application->vfs_appointment) {
                $application->vfs_appointment_date = $application->vfs_appointment_date ?? now();
            } else {
                $application->vfs_appointment_date = null;
            }

            if ($application->file_submission) {
                $application->file_submission_date = $application->file_submission_date ?? now();
            } else {
                $application->file_submission_date = null;
            }

            // Auto-set visa approval date when status changes to approved
            if ($application->visa_status === 'approved' && !$application->visa_approval_date) {
                $application->visa_approval_date = now();
            }

            // Clear visa approval date if status is not approved
            if ($application->visa_status !== 'approved') {
                $application->visa_approval_date = null;
            }
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

    public function vfsChecklist()
    {
        return $this->hasMany(VfsChecklist::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}
