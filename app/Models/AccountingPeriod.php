<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'is_closed',
        'closed_at',
        'closed_by',
        'remarks',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the display name for the period
     */
    public function getDisplayNameAttribute(): string
    {
        $monthName = date('F', mktime(0, 0, 0, $this->month, 1));
        return "{$monthName} {$this->year}";
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'period_id');
    }

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
