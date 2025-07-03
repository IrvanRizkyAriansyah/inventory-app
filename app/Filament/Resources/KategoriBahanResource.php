<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriBahanResource\Pages;
use App\Filament\Resources\KategoriBahanResource\RelationManagers;
use App\Models\KategoriBahan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class KategoriBahanResource extends Resource
{
    protected static ?string $model = KategoriBahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Kategori Bahan';
    protected static ?string $pluralLabel = 'Kategori Bahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('nama_kategori')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(50)
                ->unique(
                    table: 'kategori_bahans', 
                    column: 'nama_kategori',
                    ignoreRecord: true 
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->sortable(),
                TextColumn::make('nama_kategori')->sortable()->searchable(),
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
            'index' => Pages\ListKategoriBahans::route('/'),
            'create' => Pages\CreateKategoriBahan::route('/create'),
            'edit' => Pages\EditKategoriBahan::route('/{record}/edit'),
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
        return Gate::allows('filament-access', 'view_master') && auth()->user()->role === 'purchasing'|| auth()->user()->role === 'admin';
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
