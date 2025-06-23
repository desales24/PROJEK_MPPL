<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Menu';
    protected static ?string $pluralLabel = 'Daftar Menu';
    protected static ?string $modelLabel = 'Menu';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'makanan',
                        'info' => 'minuman',
                        'warning' => 'camilan',
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('available')
                    ->label('Tersedia')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'makanan' => 'Makanan',
                        'minuman' => 'Minuman',
                        'camilan' => 'Camilan',
                    ]),
                Tables\Filters\TernaryFilter::make('available')
                    ->label('Tersedia')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
            ])
            ->actions([]) // Customer tidak bisa edit
            ->bulkActions([]); // Customer tidak bisa delete
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    // Optional: hanya tampilkan menu yang tersedia
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('available', true);
    }
}
