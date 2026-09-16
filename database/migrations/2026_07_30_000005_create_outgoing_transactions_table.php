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
        Schema::create('outgoing_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->date('tanggal');
            $table->string('tujuan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('outgoing_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outgoing_transaction_id')->constrained('outgoing_transactions')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_transaction_details');
        Schema::dropIfExists('outgoing_transactions');
    }
};
