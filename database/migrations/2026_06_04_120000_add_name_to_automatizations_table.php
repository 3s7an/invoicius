<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TYPE_LABELS = [
        'invoice_auto_gen' => 'Automatické generovanie faktúr',
        'invoice_report' => 'Mesačný report faktúr',
        'invoice_due_reminder' => 'Upozornenie na splatnosť',
    ];

    public function up(): void
    {
        Schema::table('automatizations', function (Blueprint $table) {
            $table->string('name')->nullable()->after('recipient_id');
        });

        DB::table('automatizations')->orderBy('id')->each(function ($row) {
            DB::table('automatizations')
                ->where('id', $row->id)
                ->update([
                    'name' => self::TYPE_LABELS[$row->type] ?? $row->type,
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('automatizations', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
