<?php

namespace App\Filament\Exports;

use App\Models\PenerimaanDetail;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LaporanPembelianBahanExporter extends Exporter
{
    protected static ?string $model = PenerimaanDetail::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('penerimaan.tanggal_terima')
                ->label('Tanggal Terima'),

            ExportColumn::make('penerimaan.no_transaksi')
                ->label('No Transaksi'),

            ExportColumn::make('bahanBaku.nama_bahan_baku')
                ->label('Bahan Baku'),

            ExportColumn::make('qty')
                ->label('Jumlah'),

            ExportColumn::make('harga')
                ->label('Harga Satuan'),

            ExportColumn::make('subtotal')
                ->label('Subtotal'),

            ExportColumn::make('penerimaan.suplier.nama_suplier')
                ->label('Suplier'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Laporan pembelian bahan selesai diekspor. '
              . number_format($export->successful_rows) . ' '
              . str('baris')->plural($export->successful_rows) . ' berhasil.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                   . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
