<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanStokMinimumResource\Pages;
use App\Filament\Resources\LaporanStokMinimumResource\RelationManagers;
use App\Models\BahanBaku;
use App\Models\LaporanStokMinimum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class LaporanStokMinimumResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';
    protected static ?string $navigationLabel = 'Laporan Stok Minimum';
    protected static ?string $pluralLabel = 'Laporan Stok Minimum';
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
                BahanBaku::query()->whereColumn('stok', '<', 'stok_minimum')->with(['satuan', 'suplier'])
            )
            ->columns([
                TextColumn::make('kode_bahan_baku')->label('Kode'),
                TextColumn::make('nama_bahan_baku')->label('Nama Bahan')->searchable(),
                TextColumn::make('stok')->label('Stok')->sortable(),
                TextColumn::make('stok_minimum')->label('Minimum')->sortable(),
                TextColumn::make('satuan.nama_satuan')->label('Satuan'),
                TextColumn::make('suplier.nama_suplier')->label('Suplier'),
                TextColumn::make('selisih')
                    ->label('Selisih')
                    ->getStateUsing(fn ($record) => $record->stok - $record->stok_minimum)
                    ->color(fn ($record) => $record->stok < $record->stok_minimum ? 'danger' : 'success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLaporanStokMinimums::route('/'),
            'create' => Pages\CreateLaporanStokMinimum::route('/create'),
            'edit' => Pages\EditLaporanStokMinimum::route('/{record}/edit'),
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
