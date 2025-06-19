<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->required()
                ->label('Pelanggan'),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required(),

            Repeater::make('orderItems')
                ->label('Daftar Menu')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('menu_id')
                        ->label('Menu')
                        ->relationship('menu', 'name')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $menu = Menu::find($state);
                            if ($menu) {
                                $set('subtotal', $menu->price);
                            }
                        }),

                    Forms\Components\TextInput::make('quantity')
                        ->numeric()
                        ->default(1)
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $menu = Menu::find($get('menu_id'));
                            if ($menu) {
                                $set('subtotal', $state * $menu->price);
                            }
                        }),

                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->label('Subtotal'),
                ])
                ->defaultItems(1)
                ->minItems(1)
                ->columns(3),

            Forms\Components\TextInput::make('total')
                ->label('Total')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->afterStateHydrated(function (callable $set, $record) {
                    if ($record) {
                        $set('total', $record->orderItems->sum('subtotal'));
                    }
                })
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Pelanggan'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('total')->money('IDR', true),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
