<?php

namespace App\Filament\Exports;

use App\Models\PengeluaranDetail;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LaporanPemakaianBahanExporter extends Exporter
{
    protected static ?string $model = PengeluaranDetail::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('pengeluaran.tanggal_pengeluaran')
                ->label('Tanggal Pengeluaran'),

            ExportColumn::make('bahanBaku.nama_bahan_baku')
                ->label('Nama Bahan Baku'),

            ExportColumn::make('qty')
                ->label('Jumlah Keluar'),

            ExportColumn::make('keterangan')
                ->label('Keperluan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Laporan pemakaian bahan selesai. '
              . number_format($export->successful_rows) . ' '
              . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                   . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
