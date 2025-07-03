<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaanResource\Pages;
use App\Filament\Resources\PenerimaanResource\RelationManagers;
use App\Models\Penerimaan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
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

class PenerimaanResource extends Resource
{
    protected static ?string $model = Penerimaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationLabel = 'Penerimaan';
    protected static ?string $pluralLabel = 'Penerimaan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                DatePicker::make('tanggal_terima')
                    ->label('Tanggal Terima')
                    ->required(),

                TextInput::make('no_transaksi')
                    ->label('No. Transaksi')
                    ->required()
                    ->maxLength(50)
                    ->unique(
                        table: 'penerimaans', 
                        column: 'no_transaksi',
                        ignoreRecord: true 
                    ),

                // Repeater::make('details')
                //     ->columnSpan(2)
                //     ->label('Daftar Bahan')
                //     ->relationship('details')
                //     ->required()
                //     ->schema([
                //         Select::make('bahan_baku_id')
                //             ->label('Bahan Baku')
                //             ->relationship('bahanBaku', 'nama_bahan_baku')
                //             ->searchable()
                //             ->preload()
                //             ->required(),

                //         TextInput::make('qty')
                //             ->label('Jumlah')
                //             ->numeric()
                //             ->required()
                //             ->minValue(1)
                //             ->reactive() 
                //             ->afterStateUpdated(fn ($state, callable $set, $get) => $set('subtotal', $state * $get('harga'))),

                //         TextInput::make('harga')
                //             ->label('Harga Satuan')
                //             ->numeric()
                //             ->required()
                //             ->minValue(0)
                //             ->reactive()
                //             ->afterStateUpdated(fn ($state, callable $set, $get) => $set('subtotal', $state * $get('qty'))),

                //         TextInput::make('subtotal')
                //             ->label('Subtotal')
                //             ->numeric()
                //             ->disabled()
                //             ->dehydrated(false), 
                //     ])
                //     ->columns(4)
                //     ->reactive()
                //     ->afterStateUpdated(function ($state, callable $set) {
                //         // Hitung total biaya dari semua subtotal di repeater
                //         $total = collect($state)->sum(fn ($item) => $item['subtotal'] ?? 0);
                //         $set('total_biaya', $total);
                //     }),     

                Repeater::make('details')
                    ->columnSpan(2)
                    ->label('Daftar Bahan')
                    ->relationship('details')
                    ->schema([
                        Select::make('bahan_baku_id')
                            ->label('Bahan Baku')
                            ->relationship('bahanBaku', 'nama_bahan_baku')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $qty = $state;
                                $harga = $get('harga') ?? 0;
                                $set('subtotal', $qty * $harga);
                            }),

                        TextInput::make('harga')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $harga = $state;
                                $qty = $get('qty') ?? 0;
                                $set('subtotal', $harga * $qty);
                            }),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->minValue(0)
                            ->disabled()
                            ->dehydrated(true),

                    ])
                    ->columns(4)
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Menghitung ulang total_biaya setiap kali details berubah
                        $total = collect($state)->sum(fn ($item) => (float) ($item['subtotal'] ?? 0));
                        $set('total_biaya', $total);
                    }),

                
                Select::make('suplier_id')
                    ->label('Suplier')
                    ->relationship('suplier', 'nama_suplier')
                    ->searchable()
                    ->preload()
                    ->required(),

                // TextInput::make('total_biaya')
                //     ->label('Total Biaya')
                //     ->numeric()
                //     ->disabled(), 
                Hidden::make('total_biaya')
                    ->dehydrateStateUsing(fn ($state, $get) =>
                        collect($get('details'))->sum(fn ($item) => $item['subtotal'] ?? 0)
                    )

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('no_transaksi')->label('No. Transaksi')->sortable()->searchable(),
                TextColumn::make('tanggal_terima')->date()->label('Tanggal Terima')->sortable(),
                TextColumn::make('suplier.nama_suplier')->label('Suplier')->sortable(),
                TextColumn::make('total_biaya')->label('Total Biaya')->money('idr', true)->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Dibuat')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPenerimaans::route('/'),
            'create' => Pages\CreatePenerimaan::route('/create'),
            'edit' => Pages\EditPenerimaan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('filament-access', 'view_penerimaan');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('filament-access', 'create_penerimaan');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('filament-access', 'edit_penerimaan');
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
