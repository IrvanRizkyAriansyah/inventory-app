<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokOpnameResource\Pages;
use App\Filament\Resources\StokOpnameResource\RelationManagers;
use App\Models\StokOpname;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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

class StokOpnameResource extends Resource
{
    protected static ?string $model = StokOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Stok Opname';
    protected static ?string $pluralLabel = 'Stok Opname';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                DatePicker::make('tanggal_opname')->required(),
                Repeater::make('details')
                    ->label('Detail Opname')
                    ->relationship('details')
                    ->schema([
                        Select::make('bahan_baku_id')
                            ->label('Bahan Baku')
                            ->relationship('bahanBaku', 'nama_bahan_baku')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $bahanBaku = \App\Models\BahanBaku::find($state);
                                    if ($bahanBaku) {
                                        $set('jumlah_tercatat', $bahanBaku->stok);
                                    }
                                }
                            }),

                        TextInput::make('jumlah_tercatat')
                            ->label('Jumlah Tercatat')
                            ->numeric()
                            ->disabled() // agar tidak bisa diubah manual
                            ->dehydrated() // tetap disimpan
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $selisih = ($get('jumlah_fisik') ?? 0) - $state;
                                $set('selisih', $selisih);
                            }),


                        TextInput::make('jumlah_fisik')
                            ->label('Jumlah Fisik')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $selisih = $state - ($get('jumlah_tercatat') ?? 0);
                                $set('selisih', $selisih);
                            }),

                        TextInput::make('selisih')
                            ->label('Selisih')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->nullable(),
                    ])
                    ->columns(2)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('tanggal_opname')->date(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
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
                    ->visible(fn () => Gate::allows('filament-access', 'delete_stok_opname')),
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
            'index' => Pages\ListStokOpnames::route('/'),
            'create' => Pages\CreateStokOpname::route('/create'),
            'edit' => Pages\EditStokOpname::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('filament-access', 'view_stok_opname');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('filament-access', 'create_stok_opname');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('filament-access', 'edit_stok_opname');
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('filament-access', 'delete_stok_opname');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canViewAny();
    }

}
