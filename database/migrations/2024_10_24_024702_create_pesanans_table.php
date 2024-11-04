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
        Schema::disableForeignKeyConstraints();

        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('pelanggan_id')->nullable()->constrained()->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_pesanan')->unique();
            $table->string('nama_pesanan')->nullable();
            $table->integer('diskon')->nullable();
            $table->integer('total');
            $table->integer('keuntungan')->nullable();
            $table->string('metode_pembayaran');
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
