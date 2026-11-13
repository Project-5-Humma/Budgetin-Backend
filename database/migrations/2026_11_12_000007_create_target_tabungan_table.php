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
        if (!Schema::hasTable('target_tabungan')) {
            Schema::create('target_tabungan', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignId('user_id')->constrained('users');
                $table->string('nama_target', 150);
                $table->decimal('target_nominal', 15, 2);
                $table->decimal('nominal_terkumpul', 15, 2)->default(0);
                $table->date('tanggal_target')->nullable();
                $table->enum('status', ['aktif', 'tercapai', 'dibatalkan'])->default('aktif');
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
        Schema::dropIfExists('target_tabungan');
    }
};

