<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPemakaianBahanResource\Pages;
use App\Filament\Resources\LaporanPemakaianBahanResource\RelationManagers;
use App\Models\LaporanPemakaianBahan;
use App\Models\PengeluaranDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class LaporanPemakaianBahanResource extends Resource
{
    protected static ?string $model = PengeluaranDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Laporan Pemakaian Bahan';
    protected static ?string $pluralLabel = 'Laporan Pemakaian Bahan';
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
                PengeluaranDetail::query()
                    ->with(['bahanBaku', 'pengeluaran'])
                    ->latest('pengeluaran_id')
            )
            ->columns([
                TextColumn::make('pengeluaran.tanggal_pengeluaran')
                    ->label('Tanggal')
                    ->sortable()
                    ->date(),

                TextColumn::make('bahanBaku.nama_bahan_baku')
                    ->label('Bahan Baku')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('qty')
                    ->label('Jumlah Keluar')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keperluan')
                    ->limit(50),
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
            'index' => Pages\ListLaporanPemakaianBahans::route('/'),
            'create' => Pages\CreateLaporanPemakaianBahan::route('/create'),
            'edit' => Pages\EditLaporanPemakaianBahan::route('/{record}/edit'),
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
