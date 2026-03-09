<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('street')->nullable();
            $table->string('street_num')->nullable();
            $table->string('city')->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('state')->nullable();
            $table->string('ico', 20)->nullable();
            $table->string('dic', 20)->nullable();
            $table->string('ic_dph', 20)->nullable();
            $table->string('iban', 34)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
