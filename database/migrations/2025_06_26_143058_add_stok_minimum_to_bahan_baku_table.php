<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStokMinimumToBahanBakuTable extends Migration
{
    public function up()
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->integer('stok_minimum')->default(0)->after('stok');
            // Sesuaikan default dan posisi kolom 'after' jika perlu
        });
    }

    public function down()
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->dropColumn('stok_minimum');
        });
    }
}
