<?php

namespace App\Filament\Resources\PengeluaranResource\Pages;

use App\Filament\Resources\PengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengeluaran extends CreateRecord
{
    protected static string $resource = PengeluaranResource::class;

    protected function afterCreate(): void
    {
        // Ambil semua detail dari penerimaan
        $details = $this->record->details;

        foreach ($details as $detail) {
            $bahanBaku = $detail->bahanBaku;
            if ($bahanBaku) {
                // Tambahkan stok sesuai qty yang diterima
                $bahanBaku->decrement('stok', $detail->qty);
            }
        }
    }
}
