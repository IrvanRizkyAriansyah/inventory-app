<?php

namespace App\Filament\Resources\LaporanPembelianBahanResource\Pages;

use App\Filament\Resources\LaporanPembelianBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPembelianBahan extends EditRecord
{
    protected static string $resource = LaporanPembelianBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
