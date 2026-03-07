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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('varsym')->nullable();
            $table->string('number');
            $table->string('payment_type')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_street')->nullable();
            $table->string('recipient_street_num')->nullable();
            $table->string('recipient_city')->nullable();
            $table->string('recipient_state')->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('iban', 34)->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('vat_price', 12, 2)->default(0);
            $table->decimal('wo_vat_price', 12, 2)->default(0);
            $table->string('status')->default('draft'); // draft, sent, paid, overdue, cancelled
            $table->string('currency', 3)->default('EUR');
            $table->text('notes')->nullable();
            $table->unsignedInteger('sequence_number')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'number']);
            $table->index('status');
            $table->index('issue_date');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
