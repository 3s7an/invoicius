<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 50)->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('invoice_statuses')->insert([
            ['code' => 'draft', 'name' => 'Draft', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'sent', 'name' => 'Sent', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'paid', 'name' => 'Paid', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'overdue', 'name' => 'Overdue', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_statuses');
    }
};

