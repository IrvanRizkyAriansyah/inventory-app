<?php

namespace App\Filament\Resources\LaporanPembelianBahanResource\Pages;

use App\Filament\Exports\LaporanPembelianBahanExporter;
use App\Filament\Resources\LaporanPembelianBahanResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPembelianBahans extends ListRecords
{
    protected static string $resource = LaporanPembelianBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            ExportAction::make()
            ->exporter(LaporanPembelianBahanExporter::class)
            ->formats([
                ExportFormat::Xlsx,
            ])
            ->label('Ekspor laporan penerimaan bahan')
        ];
    }
}
