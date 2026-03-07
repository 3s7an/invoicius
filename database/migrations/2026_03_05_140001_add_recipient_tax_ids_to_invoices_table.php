<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('recipient_ico', 20)->nullable()->after('recipient_state');
            $table->string('recipient_dic', 20)->nullable()->after('recipient_ico');
            $table->string('recipient_ic_dph', 20)->nullable()->after('recipient_dic');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['recipient_ico', 'recipient_dic', 'recipient_ic_dph']);
        });
    }
};
