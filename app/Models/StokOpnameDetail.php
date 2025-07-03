<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpnameDetail extends Model
{
    use HasFactory;

    protected $guarded=[];

    // Relasi ke Stok Opname (induk)
    public function stockopname()
    {
        return $this->belongsTo(StokOpname::class);
    }

    // Relasi ke BahanBaku (barang yang diterima)
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
