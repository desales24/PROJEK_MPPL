<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderItemResource\Pages;
use App\Models\OrderItem;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->relationship('order', 'id')
                ->required(),

            Forms\Components\Select::make('menu_id')
                ->relationship('menu', 'name')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $menu = Menu::find($state);
                    if ($menu) {
                        $set('subtotal', $menu->price); // asumsi default quantity = 1
                    }
                }),

            Forms\Components\TextInput::make('quantity')
                ->required()
                ->numeric()
                ->default(1)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $menu = Menu::find($get('menu_id'));
                    if ($menu) {
                        $set('subtotal', $state * $menu->price);
                    }
                }),

            Forms\Components\TextInput::make('subtotal')
                ->required()
                ->numeric()
                ->disabled() // tidak bisa diketik manual
                ->dehydrated(), // tetap dikirim ke database
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order ID')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('menu.name')
                    ->label('Menu')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
