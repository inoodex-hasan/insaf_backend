<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\OfficeAccount;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Convert all office_transactions to journal entries
        $transactions = DB::table('office_transactions')->get();

        foreach ($transactions as $transaction) {
            // Skip if already converted (has a linked journal_entry)
            // Check if journal_entries table has a reference to this transaction

            try {
                DB::beginTransaction();

                $entryData = [
                    'date' => $transaction->transaction_date,
                    'reference_number' => $transaction->reference ?? 'OT-' . $transaction->id,
                    'note' => $transaction->notes ?? 'Converted from office transaction',
                    'status' => 'posted',
                    'created_by' => $transaction->created_by,
                    'currency_id' => 1, // Default BDT
                    'period_id' => $this->getPeriodId($transaction->transaction_date),
                ];

                $journalEntry = JournalEntry::create($entryData);

                // Create journal entry items based on transaction type
                match ($transaction->transaction_type) {
                    'income' => $this->createIncomeEntry($journalEntry, $transaction),
                    'expense' => $this->createExpenseEntry($journalEntry, $transaction),
                    'transfer' => $this->createTransferEntry($journalEntry, $transaction),
                    'deposit' => $this->createDepositEntry($journalEntry, $transaction),
                    'withdrawal' => $this->createWithdrawalEntry($journalEntry, $transaction),
                };

                DB::commit();

                Log::info("Converted office transaction #{$transaction->id} to journal entry #{$journalEntry->id}");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to convert office transaction #{$transaction->id}: " . $e->getMessage());
            }
        }

        // Step 2: Drop the office_transactions table
        Schema::dropIfExists('office_transactions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate office_transactions table
        Schema::create('office_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_account_id')->nullable()->constrained('office_accounts');
            $table->foreignId('to_account_id')->nullable()->constrained('office_accounts');
            $table->decimal('amount', 10, 2);
            $table->date('transaction_date');
            $table->enum('transaction_type', ['transfer', 'deposit', 'withdrawal', 'income', 'expense']);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Note: Data reversal would need to be done manually
    }

    /**
     * Get or create an accounting period for the given date
     */
    private function getPeriodId(string $date): ?int
    {
        $dateObj = new \DateTime($date);
        $year = (int) $dateObj->format('Y');
        $month = (int) $dateObj->format('m');

        $period = DB::table('accounting_periods')
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        return $period ? $period->id : null;
    }

    /**
     * Create journal entry for income
     * Debit: Bank/Cash account (to_account)
     * Credit: Income account
     */
    private function createIncomeEntry(JournalEntry $entry, $transaction): void
    {
        $toAccount = OfficeAccount::find($transaction->to_account_id);

        if ($toAccount && $toAccount->chart_of_account_id) {
            // Debit the receiving account
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_of_account_id' => $toAccount->chart_of_account_id,
                'debit' => $transaction->amount,
                'credit' => 0,
                'description' => $transaction->reference ?? 'Income received',
            ]);

            // Credit a default income account (you may need to adjust this)
            $incomeAccount = DB::table('chart_of_accounts')
                ->where('type', 'revenue')
                ->where('is_active', true)
                ->first();

            if ($incomeAccount) {
                JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'chart_of_account_id' => $incomeAccount->id,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => $transaction->reference ?? 'Income received',
                ]);
            }
        }
    }

    /**
     * Create journal entry for expense
     * Debit: Expense account
     * Credit: Bank/Cash account (from_account)
     */
    private function createExpenseEntry(JournalEntry $entry, $transaction): void
    {
        $fromAccount = OfficeAccount::find($transaction->from_account_id);

        if ($fromAccount && $fromAccount->chart_of_account_id) {
            // Debit a default expense account
            $expenseAccount = DB::table('chart_of_accounts')
                ->where('type', 'expense')
                ->where('is_active', true)
                ->first();

            if ($expenseAccount) {
                JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'chart_of_account_id' => $expenseAccount->id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->reference ?? 'Expense paid',
                ]);
            }

            // Credit the paying account
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_of_account_id' => $fromAccount->chart_of_account_id,
                'debit' => 0,
                'credit' => $transaction->amount,
                'description' => $transaction->reference ?? 'Expense paid',
            ]);
        }
    }

    /**
     * Create journal entry for transfer
     * Debit: To account
     * Credit: From account
     */
    private function createTransferEntry(JournalEntry $entry, $transaction): void
    {
        $fromAccount = OfficeAccount::find($transaction->from_account_id);
        $toAccount = OfficeAccount::find($transaction->to_account_id);

        if ($fromAccount && $toAccount) {
            // Debit the receiving account
            if ($toAccount->chart_of_account_id) {
                JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'chart_of_account_id' => $toAccount->chart_of_account_id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->reference ?? 'Account transfer',
                ]);
            }

            // Credit the sending account
            if ($fromAccount->chart_of_account_id) {
                JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'chart_of_account_id' => $fromAccount->chart_of_account_id,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => $transaction->reference ?? 'Account transfer',
                ]);
            }
        }
    }

    /**
     * Create journal entry for deposit
     * Debit: Bank account (to_account)
     * Credit: Cash or another account
     */
    private function createDepositEntry(JournalEntry $entry, $transaction): void
    {
        $toAccount = OfficeAccount::find($transaction->to_account_id);

        if ($toAccount && $toAccount->chart_of_account_id) {
            // Debit the receiving account
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_of_account_id' => $toAccount->chart_of_account_id,
                'debit' => $transaction->amount,
                'credit' => 0,
                'description' => $transaction->reference ?? 'Deposit',
            ]);

            // Credit a default source account
            $assetAccount = DB::table('chart_of_accounts')
                ->where('type', 'asset')
                ->where('is_active', true)
                ->first();

            if ($assetAccount) {
                JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'chart_of_account_id' => $assetAccount->id,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                    'description' => $transaction->reference ?? 'Deposit',
                ]);
            }
        }
    }

    /**
     * Create journal entry for withdrawal
     * Debit: Cash or another account
     * Credit: Bank account (from_account)
     */
    private function createWithdrawalEntry(JournalEntry $entry, $transaction): void
    {
        $fromAccount = OfficeAccount::find($transaction->from_account_id);

        if ($fromAccount && $fromAccount->chart_of_account_id) {
            // Debit a default asset account
            $assetAccount = DB::table('chart_of_accounts')
                ->where('type', 'asset')
                ->where('is_active', true)
                ->first();

            if ($assetAccount) {
                JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'chart_of_account_id' => $assetAccount->id,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                    'description' => $transaction->reference ?? 'Withdrawal',
                ]);
            }

            // Credit the withdrawing account
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_of_account_id' => $fromAccount->chart_of_account_id,
                'debit' => 0,
                'credit' => $transaction->amount,
                'description' => $transaction->reference ?? 'Withdrawal',
            ]);
        }
    }
};
