<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_type',
        'chart_of_account_id',
        'provider_name',
        'account_number',
        'branch_name',
        'opening_balance',
        'remaining_balance',
        'status',
        'created_by',
        'notes',
    ];

    protected static function booted()
    {
        static::creating(function ($account) {
            if (auth()->check() && !$account->created_by) {
                $account->created_by = auth()->id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function incomingJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'period_id')
            ->whereHas('items', function ($query) {
                $query->where('credit', '>', 0);
            });
    }

    public function outgoingJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'period_id')
            ->whereHas('items', function ($query) {
                $query->where('debit', '>', 0);
            });
    }
}
