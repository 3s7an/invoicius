<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('currencies')) {
            return;
        }

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol', 10);
            $table->timestamps();
        });

        if (DB::table('currencies')->count() === 0) {
            DB::table('currencies')->insert([
                ['name' => 'Euro', 'symbol' => '€', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Czech Koruna', 'symbol' => 'Kč', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'US Dollar', 'symbol' => '$', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
