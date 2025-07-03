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
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan_baku', 50)->unique();
            $table->string('nama_bahan_baku', 100);

            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id')->references('id')->on('kategori_bahans')->onDelete('set null');

            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->foreign('satuan_id')->references('id')->on('satuans')->onDelete('set null');

            $table->decimal('stok', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);

            $table->unsignedBigInteger('suplier_id')->nullable();
            $table->foreign('suplier_id')->references('id')->on('supliers')->onDelete('set null');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
