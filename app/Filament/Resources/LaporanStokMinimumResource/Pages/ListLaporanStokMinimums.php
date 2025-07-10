<?php

namespace App\Filament\Resources\LaporanStokMinimumResource\Pages;

use App\Filament\Exports\LaporanStokMinimumExporter;
use App\Filament\Resources\LaporanStokMinimumResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListLaporanStokMinimums extends ListRecords
{
    protected static string $resource = LaporanStokMinimumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            ExportAction::make()
            ->exporter(LaporanStokMinimumExporter::class)
            ->formats([
                ExportFormat::Xlsx,
            ])
            ->label('Ekspor laporan stok minimum')
        ];
    }
}
