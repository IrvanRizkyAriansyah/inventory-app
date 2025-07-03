<?php

namespace App\Filament\Resources\LaporanStokBahanResource\Pages;

use App\Filament\Resources\LaporanStokBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanStokBahans extends ListRecords
{
    protected static string $resource = LaporanStokBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
