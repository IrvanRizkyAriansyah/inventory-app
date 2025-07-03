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
        Schema::create('penerimaans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_terima');
            $table->string('no_transaksi')->unique();
            $table->unsignedBigInteger('suplier_id');
            $table->decimal('total_biaya', 15, 2);
            $table->unsignedBigInteger('created_by'); // user_id

            $table->timestamps();

            $table->foreign('suplier_id')->references('id')->on('supliers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaans');
    }
};
