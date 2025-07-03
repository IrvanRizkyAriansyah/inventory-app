<?php

namespace App\Filament\Resources\PengeluaranResource\Pages;

use App\Filament\Resources\PengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengeluaran extends EditRecord
{
    protected static string $resource = PengeluaranResource::class;

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
        // Tambah kembali stok dari data lama (anggap rollback)
        foreach ($this->oldDetailsData as $oldDetail) {
            $bahanBaku = \App\Models\BahanBaku::find($oldDetail['bahan_baku_id']);
            if ($bahanBaku) {
                $bahanBaku->increment('stok', $oldDetail['qty']);
            }
        }

        // Kurangi stok berdasarkan data baru, pastikan tidak kurang dari 0
        $newDetails = $this->record->details()->get();

        foreach ($newDetails as $newDetail) {
            $bahanBaku = $newDetail->bahanBaku;
            if ($bahanBaku) {
                $newStok = max(0, $bahanBaku->stok - $newDetail->qty);
                $bahanBaku->update(['stok' => $newStok]);
            }
        }
    }
}
