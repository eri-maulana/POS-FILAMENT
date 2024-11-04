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

        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained()->references('id')->on('kategoris')->onDelete('cascade')->onUpdate('cascade');
            $table->string('gambar')->nullable();
            $table->string('nama');
            $table->string('sku')->unique();
            $table->text('deskripsi');
            $table->integer('jumlah_stok');
            $table->integer('harga');
            $table->integer('harga_modal');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
