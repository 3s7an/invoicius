<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->after('iban')->constrained()->nullOnDelete();
            $table->foreignId('company_logo_id')->nullable()->after('currency_id')->constrained('user_company_logos')->nullOnDelete();
            $table->foreignId('invoice_color_id')->nullable()->after('company_logo_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
            $table->dropConstrainedForeignId('company_logo_id');
            $table->dropConstrainedForeignId('invoice_color_id');
        });
    }
};
