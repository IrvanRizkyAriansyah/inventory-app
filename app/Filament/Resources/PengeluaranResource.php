<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranResource\Pages;
use App\Filament\Resources\PengeluaranResource\RelationManagers;
use App\Models\Pengeluaran;
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
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Pengeluaran';
    protected static ?string $pluralLabel = 'Pengeluaran';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

                DatePicker::make('tanggal_pengeluaran')
                ->label('Tanggal Pengeluaran')
                ->required(),

            Repeater::make('details')
                ->label('Daftar Bahan Keluar')
                ->relationship('details')
                ->schema([
                    Select::make('bahan_baku_id')
                        ->label('Bahan Baku')
                        ->relationship('bahanBaku', 'nama_bahan_baku')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('qty')
                        ->label('Jumlah Keluar')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            $qty = $state;
                            $harga = $get('harga_satuan') ?? 0;
                            $set('subtotal', $qty * $harga);
                        })
                        ->rule(function (callable $get) {
                            $bahanBakuId = $get('bahan_baku_id');
                            if (!$bahanBakuId) {
                                // Harus array, bukan string
                                return ['required', 'numeric', 'min:1'];
                            }
                            $stok = \App\Models\BahanBaku::find($bahanBakuId)?->stok ?? 0;

                            return ['required', 'numeric', 'min:1', "max:$stok"];
                        })
                        ->helperText(function (callable $get) {
                            $bahanBakuId = $get('bahan_baku_id');
                            $stok = \App\Models\BahanBaku::find($bahanBakuId)?->stok ?? 0;

                            return "Stok tersedia: $stok";
                        }),

                    TextInput::make('keterangan')
                        ->label('Keterangan'),
                ])
                ->columns(3)
                ->columnSpan('full')
                // ->rules([
                //     'array',
                //     function ($attribute, $value, $fail) {
                //         $ids = array_column($value ?? [], 'bahan_baku_id');
                //         if (count($ids) !== count(array_unique($ids))) {
                //             $fail('Tidak boleh ada bahan baku yang sama.');
                //         }
                //     }
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('tanggal_pengeluaran')->date()->label('Tanggal Pengeluaran')->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Dibuat'),

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
            'index' => Pages\ListPengeluarans::route('/'),
            'create' => Pages\CreatePengeluaran::route('/create'),
            'edit' => Pages\EditPengeluaran::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('filament-access', 'view_pengeluaran');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('filament-access', 'create_pengeluaran');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('filament-access', 'edit_pengeluaran');
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
