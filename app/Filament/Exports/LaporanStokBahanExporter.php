<?php

namespace App\Filament\Exports;

use App\Models\BahanBaku;
use App\Models\LaporanStokBahan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LaporanStokBahanExporter extends Exporter
{
    protected static ?string $model = BahanBaku::class;

    public static function getColumns(): array
    {
        return [
            //
            ExportColumn::make('kode_bahan_baku')
            -> label('Kode Bahan Baku'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your laporan stok bahan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
