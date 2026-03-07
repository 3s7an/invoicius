<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('invoice_status_id')->nullable()->after('wo_vat_price')->constrained('invoice_statuses')->nullOnDelete();
        });

        $statuses = DB::table('invoice_statuses')->pluck('id', 'code');
        foreach ($statuses as $code => $id) {
            DB::table('invoices')->where('status', $code)->update(['invoice_status_id' => $id]);
        }

        $draftId = $statuses['draft'] ?? null;
        if ($draftId !== null) {
            DB::table('invoices')->whereNull('invoice_status_id')->update(['invoice_status_id' => $draftId]);
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_status_index');
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('wo_vat_price');
            $table->index('status');
        });

        $statuses = DB::table('invoice_statuses')->pluck('code', 'id');
        foreach ($statuses as $id => $code) {
            DB::table('invoices')->where('invoice_status_id', $id)->update(['status' => $code]);
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['invoice_status_id']);
        });
    }
};
