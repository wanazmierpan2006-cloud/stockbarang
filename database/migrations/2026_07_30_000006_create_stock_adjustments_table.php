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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penyesuaian')->unique();
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->integer('stok_sebelumnya');
            $table->integer('stok_sesudah');
            $table->integer('selisih');
            $table->string('tipe'); // tambah, kurang
            $table->string('alasan'); // Barang Rusak, Barang Hilang, Expired, Selisih Opname, Lainnya
            $table->dateTime('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
