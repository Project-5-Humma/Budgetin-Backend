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
        if (!Schema::hasTable('budget_allocations')) {
            Schema::create('budget_allocations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('budget_id')->constrained('budgets');
                $table->enum('category', ['makan', 'transportasi', 'jajan', 'lainnya', 'tabungan']);
                $table->decimal('percentage', 5, 2);
                $table->decimal('allocated_amount', 15, 2);
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};

