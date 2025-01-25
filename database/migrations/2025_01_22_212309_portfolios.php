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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('asset_type');       // e.g. stock, bond, ETF
            $table->string('asset_name');
            $table->decimal('investment_amount', 15, 2)->default(0);
            $table->decimal('quantity', 15, 4)->default(0);
            $table->date('purchase_date')->nullable();
            $table->decimal('current_value', 15, 2)->default(0);
            $table->decimal('risk_score', 5, 2)->default(0);
            $table->decimal('return_on_investment', 6, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
