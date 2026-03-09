<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('street')->nullable();
            $table->string('street_num')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('state')->nullable();
            $table->string('ico')->nullable();
            $table->string('dic')->nullable();
            $table->string('ic_dph')->nullable();
            $table->string('iban', 34)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipients');
    }
};
