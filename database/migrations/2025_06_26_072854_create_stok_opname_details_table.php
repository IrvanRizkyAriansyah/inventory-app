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
        Schema::create('stok_opname_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stok_opname_id');
            $table->unsignedBigInteger('bahan_baku_id');
            $table->decimal('jumlah_tercatat', 10, 2);
            $table->decimal('jumlah_fisik', 10, 2);
            $table->decimal('selisih', 10, 2);
            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('stok_opname_id')->references('id')->on('stok_opnames')->onDelete('cascade');
            $table->foreign('bahan_baku_id')->references('id')->on('bahan_bakus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opname_details');
    }
};
