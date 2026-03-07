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
        if (DB::table('invoice_colors')->count() === 0) {
            DB::table('invoice_colors')->insert([
                ['name' => 'Modrá', 'hex' => '#3B82F6', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Zelená', 'hex' => '#22C55E', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Červená', 'hex' => '#EF4444', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Oranžová', 'hex' => '#F97316', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Fialová', 'hex' => '#A855F7', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Tyrkysová', 'hex' => '#06B6D4', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Ružová', 'hex' => '#EC4899', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Sivá', 'hex' => '#6B7280', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        $defaultColorId = (int) DB::table('invoice_colors')->orderBy('id')->value('id') ?: 1;

        Schema::table('users', function (Blueprint $table) use ($defaultColorId) {
            $table->foreignId('invoice_color_id')
                ->after('company_logo_id')
                ->nullable()
                ->default($defaultColorId)
                ->constrained('invoice_colors')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['invoice_color_id']);
        });
    }
};
