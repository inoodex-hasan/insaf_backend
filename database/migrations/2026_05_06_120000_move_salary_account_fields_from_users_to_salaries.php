<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('salaries', 'account_number')) {
                $table->string('account_number')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('salaries', 'bank_branch')) {
                $table->string('bank_branch')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('salaries', 'routing_number')) {
                $table->string('routing_number')->nullable()->after('bank_branch');
            }
        });

        if (
            Schema::hasColumn('users', 'basic_salary') &&
            Schema::hasColumn('users', 'account_number') &&
            Schema::hasColumn('users', 'bank_name') &&
            Schema::hasColumn('users', 'bank_branch') &&
            Schema::hasColumn('users', 'routing_number')
        ) {
            DB::statement("
                UPDATE salaries s
                INNER JOIN users u ON u.id = s.user_id
                SET
                    s.basic_salary = COALESCE(NULLIF(s.basic_salary, 0), u.basic_salary, 0),
                    s.account_number = COALESCE(s.account_number, u.account_number),
                    s.bank_name = COALESCE(s.bank_name, u.bank_name),
                    s.bank_branch = COALESCE(s.bank_branch, u.bank_branch),
                    s.routing_number = COALESCE(s.routing_number, u.routing_number)
                WHERE s.user_id IS NOT NULL
            ");
        }

        Schema::table('users', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['basic_salary', 'account_number', 'bank_name', 'bank_branch', 'routing_number'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $dropColumns[] = $column;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'basic_salary')) {
                $table->decimal('basic_salary', 10, 2)->default(0)->after('email');
            }
            if (!Schema::hasColumn('users', 'account_number')) {
                $table->string('account_number')->nullable()->after('basic_salary');
            }
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('users', 'bank_branch')) {
                $table->string('bank_branch')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('users', 'routing_number')) {
                $table->string('routing_number')->nullable()->after('bank_branch');
            }
        });

        Schema::table('salaries', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['account_number', 'bank_branch', 'routing_number'] as $column) {
                if (Schema::hasColumn('salaries', $column)) {
                    $dropColumns[] = $column;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};

