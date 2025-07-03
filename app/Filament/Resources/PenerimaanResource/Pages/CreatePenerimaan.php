<?php

namespace App\Filament\Resources\PenerimaanResource\Pages;

use App\Filament\Resources\PenerimaanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenerimaan extends CreateRecord
{
    protected static string $resource = PenerimaanResource::class;
    protected function afterCreate(): void
    {
        // Ambil semua detail dari penerimaan
        $details = $this->record->details;

        foreach ($details as $detail) {
            $bahanBaku = $detail->bahanBaku;
            if ($bahanBaku) {
                // Tambahkan stok sesuai qty yang diterima
                $bahanBaku->increment('stok', $detail->qty);
            }
        }
    }

}
