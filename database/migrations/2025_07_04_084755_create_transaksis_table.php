<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_transaksi');
            $table->string('slug')->unique();
            $table->enum('status', ['diterima', 'diproses', 'selesai', 'diambil']);
            $table->boolean('pembayaran');
            $table->integer('total');
            $table->date('selesai')->nullable();
            $table->dateTime('diambil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
