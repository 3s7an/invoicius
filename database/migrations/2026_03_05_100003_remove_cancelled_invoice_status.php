<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $cancelled = DB::table('invoice_statuses')->where('code', 'cancelled')->first();
        if ($cancelled) {
            $draft = DB::table('invoice_statuses')->where('code', 'draft')->first();
            if ($draft) {
                DB::table('invoices')->where('invoice_status_id', $cancelled->id)->update(['invoice_status_id' => $draft->id]);
            }
            DB::table('invoice_statuses')->where('id', $cancelled->id)->delete();
        }
    }

    public function down(): void
    {
        $now = now();
        DB::table('invoice_statuses')->insert([
            ['code' => 'cancelled', 'name' => 'Cancelled', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
};
