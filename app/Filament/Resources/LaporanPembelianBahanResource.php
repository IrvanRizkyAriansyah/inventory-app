<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPembelianBahanResource\Pages;
use App\Filament\Resources\LaporanPembelianBahanResource\RelationManagers;
use App\Models\LaporanPembelianBahan;
use App\Models\PenerimaanDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class LaporanPembelianBahanResource extends Resource
{
    protected static ?string $model = PenerimaanDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';
    protected static ?string $navigationLabel = 'Laporan Penerimaan Bahan';
    protected static ?string $pluralLabel = 'Laporan Penerimaan Bahan';
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
                PenerimaanDetail::query()->with(['bahanBaku', 'penerimaan.suplier'])->latest('penerimaan_id')
            )
            ->columns([
                TextColumn::make('penerimaan.tanggal_terima')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('penerimaan.no_transaksi')
                    ->label('No Transaksi')
                    ->searchable(),

                TextColumn::make('bahanBaku.nama_bahan_baku')
                    ->label('Bahan Baku')
                    ->searchable(),

                TextColumn::make('qty')
                    ->label('Qty'),

                TextColumn::make('harga')
                    ->label('Harga Satuan')
                    ->money('IDR', true),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', true),

                TextColumn::make('penerimaan.suplier.nama_suplier')
                    ->label('Suplier')
                    ->searchable(),
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
            'index' => Pages\ListLaporanPembelianBahans::route('/'),
            'create' => Pages\CreateLaporanPembelianBahan::route('/create'),
            'edit' => Pages\EditLaporanPembelianBahan::route('/{record}/edit'),
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
