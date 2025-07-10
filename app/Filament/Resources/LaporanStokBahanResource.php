<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanStokBahanResource\Pages;
use App\Filament\Resources\LaporanStokBahanResource\RelationManagers;
use App\Models\BahanBaku;
use App\Models\LaporanStokBahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class LaporanStokBahanResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Laporan Stok Bahan';
    protected static ?string $pluralLabel = 'Laporan Stok Bahan';
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                BahanBaku::query()->with(['kategori', 'satuan', 'suplier'])
            )
            ->columns([
                TextColumn::make('kode_bahan_baku')->label('Kode'),
                TextColumn::make('nama_bahan_baku')->label('Nama Bahan'),
                TextColumn::make('kategori.nama_kategori')->label('Kategori'),
                TextColumn::make('satuan.nama_satuan')->label('Satuan'),
                TextColumn::make('stok')->label('Stok Saat Ini'),
                TextColumn::make('stok_minimum')->label('Stok Minimum'),
                TextColumn::make('suplier.nama_suplier')->label('Suplier'),

                TextColumn::make('Status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->stok < $record->stok_minimum ? 'danger' : 'success')
                    ->getStateUsing(fn ($record) => $record->stok < $record->stok_minimum ? 'Perlu Restock' : 'Cukup'),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanStokBahans::route('/'),
            'create' => Pages\CreateLaporanStokBahan::route('/create'),
            'edit' => Pages\EditLaporanStokBahan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('filament-access', 'view_laporan');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canViewAny();
    }

}
