<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_company_logos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('link');
            $table->string('file_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_company_logos');
    }
};
