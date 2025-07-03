<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Penerimaan extends Model
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

        static::deleting(function ($penerimaan) {
            // Jika pakai soft delete, cek ini supaya stok hanya berkurang saat force delete
            if (method_exists($penerimaan, 'isForceDeleting') && !$penerimaan->isForceDeleting()) {
                return;
            }

            foreach ($penerimaan->details as $detail) {
                $bahanBaku = $detail->bahanBaku;
                if ($bahanBaku) {
                    $bahanBaku->decrement('stok', $detail->qty);
                }
            }
        });
    }

    public function details()
    {
        return $this->hasMany(PenerimaanDetail::class);
    }

    public function suplier()
    {
        return $this->belongsTo(Suplier::class);
    }
}
