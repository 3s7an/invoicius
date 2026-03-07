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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('name');
            $table->string('ico', 20)->nullable()->after('state');
            $table->string('dic', 20)->nullable()->after('ico');
            $table->string('ic_dph', 20)->nullable()->after('dic');
            $table->string('iban', 34)->nullable()->after('ic_dph');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'ico', 'dic', 'ic_dph', 'iban']);
        });
    }
};
