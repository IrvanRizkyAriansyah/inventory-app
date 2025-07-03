<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $guarded=[];

    // Relasi ke KategoriBahan
    public function kategori()
    {
        return $this->belongsTo(KategoriBahan::class, 'kategori_id');
    }

    // Relasi ke Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    // Relasi ke Suplier
    public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'suplier_id');
    }

    public function penerimaanDetails()
    {
        return $this->hasMany(PenerimaanDetail::class);
    }

}
