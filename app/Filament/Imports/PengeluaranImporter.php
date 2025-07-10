<?php

namespace App\Filament\Imports;

use App\Models\Pengeluaran;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PengeluaranImporter extends Importer
{
    protected static ?string $model = Pengeluaran::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tanggal_pengeluaran')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('created_by')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Pengeluaran
    {
        // return Pengeluaran::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Pengeluaran();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pengeluaran import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
