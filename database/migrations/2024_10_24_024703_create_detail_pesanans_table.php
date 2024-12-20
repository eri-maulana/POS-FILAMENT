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

        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained()->references('id')->on('pesanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('produk_id')->constrained()->references('id')->on('produks')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('kuantitas');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
