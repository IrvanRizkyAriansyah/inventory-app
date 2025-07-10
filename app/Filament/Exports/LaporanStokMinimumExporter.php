<?php

namespace App\Filament\Exports;

use App\Models\BahanBaku;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LaporanStokMinimumExporter extends Exporter
{
    protected static ?string $model = BahanBaku::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_bahan_baku')->label('Kode'),
            ExportColumn::make('nama_bahan_baku')->label('Nama Bahan'),
            ExportColumn::make('stok')->label('Stok Saat Ini'),
            ExportColumn::make('stok_minimum')->label('Stok Minimum'),
            ExportColumn::make('satuan.nama_satuan')->label('Satuan'),
            ExportColumn::make('suplier.nama_suplier')->label('Suplier'),
            ExportColumn::make('selisih')->label('Selisih')->getStateUsing(function ($record) {
                return $record->stok - $record->stok_minimum;
            }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Laporan stok minimum berhasil diekspor. '
              . number_format($export->successful_rows) . ' '
              . str('baris')->plural($export->successful_rows) . ' berhasil.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                   . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
