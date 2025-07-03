<?php

namespace App\Filament\Resources\LaporanStokMinimumResource\Pages;

use App\Filament\Resources\LaporanStokMinimumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanStokMinimums extends ListRecords
{
    protected static string $resource = LaporanStokMinimumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
