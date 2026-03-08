<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoices')) {
            return;
        }

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained()->nullOnDelete();
            $table->string('varsym')->nullable();
            $table->string('number');
            $table->string('payment_type')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_street')->nullable();
            $table->string('recipient_street_num')->nullable();
            $table->string('recipient_city')->nullable();
            $table->string('recipient_state')->nullable();
            $table->string('recipient_ico', 20)->nullable();
            $table->string('recipient_dic', 20)->nullable();
            $table->string('recipient_ic_dph', 20)->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('iban', 34)->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('vat_price', 12, 2)->default(0);
            $table->decimal('wo_vat_price', 12, 2)->default(0);
            $table->foreignId('invoice_status_id')->nullable()->constrained('invoice_statuses')->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->unsignedInteger('sequence_number')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'number']);
            $table->index('issue_date');
            $table->index('due_date');
            $table->index('recipient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
