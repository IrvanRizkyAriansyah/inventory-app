<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::deleting(function ($pengeluaran) {
            // Jika pakai soft delete, cek ini supaya stok hanya berkurang saat force delete
            if (method_exists($pengeluaran, 'isForceDeleting') && !$pengeluaran->isForceDeleting()) {
                return;
            }

            foreach ($pengeluaran->details as $detail) {
                $bahanBaku = $detail->bahanBaku;
                if ($bahanBaku) {
                    $bahanBaku->increment('stok', $detail->qty);
                }
            }
        });
    }

    public function details()
    {
        return $this->hasMany(PengeluaranDetail::class);
    }

    public function suplier()
    {
        return $this->belongsTo(Suplier::class);
    }


}
