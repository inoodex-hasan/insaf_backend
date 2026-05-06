<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\ChartOfAccount;
use App\Models\OfficeAccount;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $accounts = OfficeAccount::whereNull('chart_of_account_id')->get();

        $maxCode = (int) ChartOfAccount::where('type', 'asset')->max('code');
        $nextCode = $maxCode ? $maxCode + 1 : 10001;

        foreach ($accounts as $account) {
            $coa = ChartOfAccount::create([
                'code' => (string) $nextCode++,
                'name' => $account->account_name,
                'type' => 'asset',
                'is_active' => $account->status === 'active',
            ]);

            $account->update(['chart_of_account_id' => $coa->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The created ChartOfAccount records will remain; this is non-destructive.
    }
};
