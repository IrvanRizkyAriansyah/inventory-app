<?php

namespace App\Filament\Resources\LaporanStokBahanResource\Pages;

use App\Filament\Resources\LaporanStokBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanStokBahan extends EditRecord
{
    protected static string $resource = LaporanStokBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
