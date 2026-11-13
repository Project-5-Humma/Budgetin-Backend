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
        if (!Schema::hasTable('daftar_keinginan')) {
            Schema::create('daftar_keinginan', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignId('user_id')->constrained('users');
                $table->foreignUuid('target_tabungan_id')->nullable()->constrained('target_tabungan');
                $table->string('nama_barang', 200);
                $table->text('link_pembelian')->nullable();
                $table->decimal('harga', 15, 2);
                $table->decimal('nominal_tersimpan', 15, 2)->default(0);
                $table->unsignedInteger('masa_tunda_hari')->default(7);
                $table->date('tanggal_tunda_berakhir')->nullable();
                $table->enum('status', ['tertunda', 'menunggu_keputusan', 'dibeli', 'batal'])->default('tertunda');
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
        Schema::dropIfExists('daftar_keinginan');
    }
};

