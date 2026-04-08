<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ChartOfAccount;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Migrate finance_categories to chart_of_accounts
        $financeCategories = DB::table('finance_categories')->get();

        foreach ($financeCategories as $category) {
            // Map finance_categories type to chart_of_accounts type
            $coaType = match ($category->type) {
                'expense' => 'expense',
                'income' => 'revenue',
                'both' => 'expense', // Default to expense for 'both', can be adjusted
            };

            // Check if account already exists
            $exists = ChartOfAccount::where('name', $category->name)
                ->where('type', $coaType)
                ->exists();

            if (!$exists) {
                // Create a new chart of accounts entry
                $chartOfAccount = ChartOfAccount::create([
                    'code' => $this->generateCode($coaType),
                    'name' => $category->name,
                    'type' => $coaType,
                    'is_active' => $category->is_active,
                    'is_default' => false,
                ]);

                // Log the mapping for future reference
                Log::info("Migrated finance category '{$category->name}' to chart_of_account ID: {$chartOfAccount->id}");
            }
        }

        // Step 2: Add chart_of_account_id column to budgets table
        if (!Schema::hasColumn('budgets', 'chart_of_account_id')) {
            Schema::table('budgets', function (Blueprint $table) {
                $table->foreignId('chart_of_account_id')->nullable()->after('id')->constrained('chart_of_accounts');
            });
        }

        // Step 3: Migrate budget category references to chart_of_account_id
        $budgets = DB::table('budgets')->whereNull('chart_of_account_id')->get();
        foreach ($budgets as $budget) {
            if ($budget->category) {
                $chartOfAccount = ChartOfAccount::where('name', $budget->category)->first();
                if ($chartOfAccount) {
                    DB::table('budgets')
                        ->where('id', $budget->id)
                        ->update(['chart_of_account_id' => $chartOfAccount->id]);
                }
            }
        }

        // Step 4: Migrate expense category references to chart_of_account_id
        $expenses = DB::table('expenses')->whereNull('chart_of_account_id')->get();
        foreach ($expenses as $expense) {
            if ($expense->category) {
                $chartOfAccount = ChartOfAccount::where('name', $expense->category)->first();
                if ($chartOfAccount) {
                    DB::table('expenses')
                        ->where('id', $expense->id)
                        ->update(['chart_of_account_id' => $chartOfAccount->id]);
                }
            }
        }

        // Step 5: Drop the category column from budgets and expenses (after migration)
        Schema::table('budgets', function (Blueprint $table) {
            if (Schema::hasColumn('budgets', 'category')) {
                $table->dropColumn('category');
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'category')) {
                $table->dropColumn('category');
            }
        });

        // Step 6: Drop the finance_categories table
        Schema::dropIfExists('finance_categories');

        // Step 7: Delete the FinanceCategorySeeder file (optional cleanup)
        $seederPath = database_path('seeders/FinanceCategorySeeder.php');
        if (file_exists($seederPath)) {
            unlink($seederPath);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate finance_categories table
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['expense', 'income', 'both'])->default('both');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add category column back to budgets
        if (!Schema::hasColumn('budgets', 'category')) {
            Schema::table('budgets', function (Blueprint $table) {
                $table->string('category')->nullable();
            });
        }

        // Add category column back to expenses
        if (!Schema::hasColumn('expenses', 'category')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->string('category')->nullable();
            });
        }

        // Note: Data reversal would need to be done manually
    }

    /**
     * Generate a unique code for chart of accounts
     */
    private function generateCode(string $type): string
    {
        $prefix = match ($type) {
            'asset' => '1',
            'liability' => '2',
            'equity' => '3',
            'revenue' => '4',
            'expense' => '5',
        };

        // Get the next available code
        $lastAccount = ChartOfAccount::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastAccount) {
            $nextNumber = intval(substr($lastAccount->code, 1)) + 1;
        } else {
            $nextNumber = 1001;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
};
