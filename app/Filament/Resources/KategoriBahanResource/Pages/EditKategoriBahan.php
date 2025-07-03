<?php

namespace App\Filament\Resources\KategoriBahanResource\Pages;

use App\Filament\Resources\KategoriBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriBahan extends EditRecord
{
    protected static string $resource = KategoriBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
