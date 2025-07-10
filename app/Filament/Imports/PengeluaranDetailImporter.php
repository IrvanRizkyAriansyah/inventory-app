<?php

namespace App\Filament\Imports;

use App\Models\PengeluaranDetail;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PengeluaranDetailImporter extends Importer
{
    protected static ?string $model = PengeluaranDetail::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('pengeluaran_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('bahan_baku_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('qty')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('keterangan')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?PengeluaranDetail
    {
        // return PengeluaranDetail::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new PengeluaranDetail();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pengeluaran detail import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
