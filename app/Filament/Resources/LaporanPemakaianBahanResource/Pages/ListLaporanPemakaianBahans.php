<?php

namespace App\Filament\Resources\LaporanPemakaianBahanResource\Pages;

use App\Filament\Exports\LaporanPemakaianBahanExporter;
use App\Filament\Exports\LaporanStokBahanExporter;
use App\Filament\Resources\LaporanPemakaianBahanResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPemakaianBahans extends ListRecords
{
    protected static string $resource = LaporanPemakaianBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            ExportAction::make()
            ->exporter(LaporanPemakaianBahanExporter::class)
            ->formats([
                ExportFormat::Xlsx,
            ])
            ->label('Ekspor laporan pemakaian bahan')
        ];
    }
}
