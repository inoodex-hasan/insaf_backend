<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'percentage',
        'amount',
        'status',
        'notes',
        'workflow_status',
        'claimed_at',
        'claim_notes',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'proposed_amount',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'proposed_amount' => 'decimal:2',
        'claimed_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Workflow statuses
    public const STATUS_DRAFT = 'draft';
    public const STATUS_CLAIMED = 'claimed';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAID = 'paid';

    public static array $workflowStatuses = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_CLAIMED => 'Claimed',
        self::STATUS_UNDER_REVIEW => 'Under Review',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_PAID => 'Paid',
    ];

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('workflow_status', self::STATUS_DRAFT);
    }

    public function scopeClaimed($query)
    {
        return $query->where('workflow_status', self::STATUS_CLAIMED);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('workflow_status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('workflow_status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('workflow_status', self::STATUS_REJECTED);
    }

    public function scopePaid($query)
    {
        return $query->where('workflow_status', self::STATUS_PAID);
    }

    public function scopePendingReview($query)
    {
        return $query->whereIn('workflow_status', [self::STATUS_CLAIMED, self::STATUS_UNDER_REVIEW]);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForApplication($query, $applicationId)
    {
        return $query->where('application_id', $applicationId);
    }

    // Status checks
    public function isDraft(): bool
    {
        return $this->workflow_status === self::STATUS_DRAFT;
    }

    public function isClaimed(): bool
    {
        return $this->workflow_status === self::STATUS_CLAIMED;
    }

    public function isUnderReview(): bool
    {
        return $this->workflow_status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->workflow_status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->workflow_status === self::STATUS_REJECTED;
    }

    public function isPaid(): bool
    {
        return $this->workflow_status === self::STATUS_PAID;
    }

    public function canBeClaimed(): bool
    {
        return $this->isDraft() || $this->isRejected();
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->workflow_status, [self::STATUS_CLAIMED, self::STATUS_UNDER_REVIEW]);
    }

    public function canBeReviewed(): bool
    {
        return $this->isClaimed() || $this->isUnderReview();
    }

    public function canBePaid(): bool
    {
        return $this->isApproved();
    }

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper methods
    public function getWorkflowStatusLabel(): string
    {
        return self::$workflowStatuses[$this->workflow_status] ?? $this->workflow_status;
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->workflow_status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_CLAIMED => 'info',
            self::STATUS_UNDER_REVIEW => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_PAID => 'primary',
            default => 'secondary',
        };
    }

    // Boot method for automatic old status sync
    protected static function booted()
    {
        static::saving(function ($commission) {
            // Sync old status field for backward compatibility
            $commission->status = match ($commission->workflow_status) {
                self::STATUS_PAID => 'paid',
                self::STATUS_APPROVED => 'pending',
                default => 'pending',
            };
        });
    }
}
