<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'email',
        'phone',
        'current_education',
        'preferred_country',
        'preferred_course',
        'source',
        'status',
        'notes',
        'last_contacted_at',
        'next_follow_up_at',
        'follow_up_history',
        'created_by',
        'consultant_id',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'follow_up_history' => 'array',
    ];

    public function getFollowUpDateHistoryAttribute(): array
    {
        return $this->follow_up_timeline
            ->pluck('date')
            ->values()
            ->all();
    }

    public function getFollowUpTimelineAttribute(): Collection
    {
        $history = collect($this->follow_up_history ?? [])
            ->map(fn ($entry) => $this->normalizeFollowUpEntry($entry))
            ->filter(fn ($entry) => $entry !== null);

        $currentEntry = $this->normalizeFollowUpEntry([
            'date' => $this->next_follow_up_at,
            'notes' => $this->notes,
        ]);

        if ($currentEntry !== null && ! $this->entriesEqual($history->last(), $currentEntry)) {
            $history->push($currentEntry);
        }

        return $history->values();
    }

    private function normalizeFollowUpEntry(mixed $entry): ?array
    {
        if (blank($entry)) {
            return null;
        }

        if (! is_array($entry)) {
            $date = $entry instanceof Carbon ? $entry : Carbon::parse($entry);

            return [
                'date' => $date,
                'notes' => null,
            ];
        }

        $dateValue = $entry['date'] ?? $entry['follow_up_date'] ?? $entry['next_follow_up_at'] ?? null;

        if (blank($dateValue)) {
            return null;
        }

        return [
            'date' => $dateValue instanceof Carbon ? $dateValue : Carbon::parse($dateValue),
            'notes' => $entry['notes'] ?? null,
        ];
    }

    private function entriesEqual(?array $left, ?array $right): bool
    {
        if ($left === null || $right === null) {
            return false;
        }

        return $left['date']->toDateString() === $right['date']->toDateString()
            && (string) ($left['notes'] ?? '') === (string) ($right['notes'] ?? '');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function consultant()
    {
        return $this->belongsTo(\App\Models\User::class, 'consultant_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'preferred_country');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'preferred_course');
    }
}
