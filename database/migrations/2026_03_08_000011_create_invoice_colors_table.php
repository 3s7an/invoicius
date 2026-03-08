<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoice_colors')) {
            return;
        }

        Schema::create('invoice_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex', 7);
            $table->timestamps();
        });

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
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_colors');
    }
};
