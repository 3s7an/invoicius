<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('unit', 20)->nullable(); // ks, kg, hod, ...
            $table->decimal('quantity', 12, 4)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('unit_wo_vat', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->default(0)->nullable();
            $table->decimal('vat', 12, 2)->nullable(); // percent or amount
            $table->unsignedSmallInteger('position')->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
