<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Filament\Resources\BahanBakuResource\RelationManagers;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Bahan Baku';
    protected static ?string $pluralLabel = 'Bahan Baku';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('kode_bahan_baku')
                ->label('Kode Bahan Baku')
                ->required()
                ->maxLength(50)
                ->unique(
                    table: 'bahan_bakus',
                    column: 'kode_bahan_baku',
                    ignoreRecord: true
                ),

                TextInput::make('nama_bahan_baku')
                    ->label('Nama Bahan Baku')
                    ->required()
                    ->maxLength(100)
                    ->unique(
                        table: 'bahan_bakus',
                        column: 'nama_bahan_baku',
                        ignoreRecord: true
                    ),

                Select::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('satuan_id')
                    ->label('Satuan')
                    ->relationship('satuan', 'nama_satuan')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('stok_minimum')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->default(0)
                    ->minValue(1)
                    ->required(),

                Select::make('suplier_id')
                    ->label('Suplier')
                    ->relationship('suplier', 'nama_suplier')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                ->sortable()
                ->label('ID'),

                TextColumn::make('kode_bahan_baku')
                    ->sortable()
                    ->searchable()
                    ->label('Kode'),

                TextColumn::make('nama_bahan_baku')
                    ->sortable()
                    ->searchable()
                    ->label('Nama'),

                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('satuan.nama_satuan')
                    ->label('Satuan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('stok_minimum')
                    ->label('Stok Minimum')
                    ->sortable(),

                TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable(),

                // TextColumn::make('harga_satuan')
                //     ->label('Harga Satuan')
                //     ->money('IDR', true)
                //     ->sortable(),

                TextColumn::make('suplier.nama_suplier')
                    ->label('Suplier')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->role === 'admin'),
                ]),
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
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('filament-access', 'view_master');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('filament-access', 'view_master');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('filament-access', 'view_master') && auth()->user()->role === 'purchasing' || auth()->user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canViewAny();
    }
}
