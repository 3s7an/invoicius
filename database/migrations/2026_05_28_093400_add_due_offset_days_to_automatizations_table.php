<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('automatizations', function (Blueprint $table) {
            $table->integer('due_offset_days')->nullable()->after('date_trigger');
            $table->index('due_offset_days');
        });
    }

    public function down(): void
    {
        Schema::table('automatizations', function (Blueprint $table) {
            $table->dropIndex(['due_offset_days']);
            $table->dropColumn('due_offset_days');
        });
    }
};

