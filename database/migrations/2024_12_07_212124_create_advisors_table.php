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
        Schema::create('advisors', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('first_name');
            $table->string('user_type')->default('advisor');
            $table->string('last_name');
            $table->string('phone');
            $table->string('specialization');
            $table->string('certification')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->string('status')->default('free');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisors');
    }
};
