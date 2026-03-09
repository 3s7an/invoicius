<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vat_type_id')->nullable()->constrained('vat_types')->nullOnDelete();
            $table->string('name');
            $table->string('unit', 20)->nullable();
            $table->decimal('quantity', 12, 4)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('unit_wo_vat', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->default(0)->nullable();
            $table->decimal('vat', 12, 2)->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->decimal('line_wo_vat', 12, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
