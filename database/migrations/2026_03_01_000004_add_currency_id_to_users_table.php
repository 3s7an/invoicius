<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::table('currencies')->count() === 0) {
            DB::table('currencies')->insert([
                ['name' => 'Euro', 'symbol' => '€', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Czech Koruna', 'symbol' => 'Kč', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'US Dollar', 'symbol' => '$', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        $eurId = (int) DB::table('currencies')->where('symbol', '€')->value('id');

        Schema::table('users', function (Blueprint $table) use ($eurId) {
            $table->foreignId('currency_id')
                ->after('iban')
                ->default($eurId)
                ->constrained('currencies')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });
    }
};
