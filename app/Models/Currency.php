<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('active_currencies');
        });

        static::deleted(function () {
            Cache::forget('active_currencies');
        });
    }

    /**
     * Scope: only active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the default currency.
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first();
    }
}
