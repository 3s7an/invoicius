<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $eurId = (int) DB::table('currencies')->where('symbol', '€')->value('id') ?: 1;
        $defaultColorId = (int) DB::table('invoice_colors')->orderBy('id')->value('id') ?: 1;

        if (! Schema::hasColumn('users', 'currency_id')) {
            Schema::table('users', function (Blueprint $table) use ($eurId) {
                $table->unsignedBigInteger('currency_id')->nullable()->default($eurId)->after('iban');
                $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();
            });
        } else {
            $this->ensureNullableAndFk('users', 'currency_id', 'currencies', 'users_currency_id_foreign', $eurId);
        }

        if (! Schema::hasColumn('users', 'company_logo_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('company_logo_id')->nullable()->after('currency_id');
                $table->foreign('company_logo_id')->references('id')->on('users_companies_logo')->nullOnDelete();
            });
        } else {
            $this->ensureFk('users', 'company_logo_id', 'users_companies_logo', 'users_company_logo_id_foreign');
        }

        if (! Schema::hasColumn('users', 'invoice_color_id')) {
            Schema::table('users', function (Blueprint $table) use ($defaultColorId) {
                $table->unsignedBigInteger('invoice_color_id')->nullable()->default($defaultColorId)->after('company_logo_id');
                $table->foreign('invoice_color_id')->references('id')->on('invoice_colors')->nullOnDelete();
            });
        } else {
            $this->ensureNullableAndFk('users', 'invoice_color_id', 'invoice_colors', 'users_invoice_color_id_foreign', $defaultColorId);
        }
    }

    private function ensureFk(string $table, string $column, string $refTable, string $fkName): void
    {
        if ($this->constraintExists($table, $fkName)) {
            return;
        }
        Schema::table($table, function (Blueprint $t) use ($column, $refTable) {
            $t->foreign($column)->references('id')->on($refTable)->nullOnDelete();
        });
    }

    private function ensureNullableAndFk(string $table, string $column, string $refTable, string $fkName, int $default): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE {$table} MODIFY {$column} BIGINT UNSIGNED NULL DEFAULT ?", [$default]);
        }
        $this->ensureFk($table, $column, $refTable, $fkName);
    }

    private function constraintExists(string $tableName, string $constraintName): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->exists();
    }

    public function down(): void
    {
        if ($this->constraintExists('users', 'users_currency_id_foreign')) {
            Schema::table('users', fn (Blueprint $t) => $t->dropForeign(['currency_id']));
        }
        if ($this->constraintExists('users', 'users_company_logo_id_foreign')) {
            Schema::table('users', fn (Blueprint $t) => $t->dropForeign(['company_logo_id']));
        }
        if ($this->constraintExists('users', 'users_invoice_color_id_foreign')) {
            Schema::table('users', fn (Blueprint $t) => $t->dropForeign(['invoice_color_id']));
        }
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'currency_id')) {
                $table->dropColumn('currency_id');
            }
            if (Schema::hasColumn('users', 'company_logo_id')) {
                $table->dropColumn('company_logo_id');
            }
            if (Schema::hasColumn('users', 'invoice_color_id')) {
                $table->dropColumn('invoice_color_id');
            }
        });
    }
};
