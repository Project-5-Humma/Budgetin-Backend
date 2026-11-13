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
        if (!Schema::hasTable('setoran_tabungan')) {
            Schema::create('setoran_tabungan', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('target_tabungan_id')->constrained('target_tabungan');
                $table->foreignUuid('wallet_id')->nullable()->constrained('wallets');
                $table->decimal('nominal', 15, 2);
                $table->date('tanggal_setor');
                $table->string('catatan', 255)->nullable();
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
        Schema::dropIfExists('setoran_tabungan');
    }
};

