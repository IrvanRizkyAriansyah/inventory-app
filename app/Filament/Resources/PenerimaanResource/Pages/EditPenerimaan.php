<?php

namespace App\Filament\Resources\PenerimaanResource\Pages;

use App\Filament\Resources\PenerimaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaan extends EditRecord
{
    protected static string $resource = PenerimaanResource::class;

    protected array $oldDetailsData = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    // Simpan data lama sebelum proses update dimulai
    protected function beforeSave(): void
    {
        // Ambil data detail lama dari DB (sebelum update)
        $this->oldDetailsData = $this->record->details()->get()->map(function ($detail) {
            return [
                'bahan_baku_id' => $detail->bahan_baku_id,
                'qty' => $detail->qty,
            ];
        })->toArray();
    }

    protected function afterSave(): void
    {
        // Kurangi stok berdasarkan data lama dengan cek stok minimal 0
        foreach ($this->oldDetailsData as $oldDetail) {
            $bahanBaku = \App\Models\BahanBaku::find($oldDetail['bahan_baku_id']);
            if ($bahanBaku) {
                $newStok = max(0, $bahanBaku->stok - $oldDetail['qty']); // jangan sampai kurang dari 0
                $bahanBaku->update(['stok' => $newStok]);
            }
        }

        // Tambah stok berdasarkan data baru
        $newDetails = $this->record->details()->get();

        foreach ($newDetails as $newDetail) {
            $bahanBaku = $newDetail->bahanBaku;
            if ($bahanBaku) {
                $bahanBaku->increment('stok', $newDetail->qty);
            }
        }
    }
}
