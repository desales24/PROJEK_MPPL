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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

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

            FileUpload::make('bukti_pembayaran')
                ->label('Bukti Pembayaran')
                ->disk('public')
                ->directory('bukti-pembayaran')
                ->nullable()
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/png']),

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

                    TextInput::make('quantity')
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

                    TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->label('Subtotal'),
                ])
                ->defaultItems(1)
                ->minItems(1)
                ->columns(3),

            TextInput::make('total')
                ->label('Total')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->afterStateHydrated(function (callable $set, $record) {
                    if ($record) {
                        $set('total', $record->orderItems->sum('subtotal'));
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')->label('Pelanggan'),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->action(function (Order $record) {
                        if ($record->status === 'pending') {
                            $record->update(['status' => 'paid']);
                        }
                    })
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'danger',
                        'paid' => 'success',
                        'cancelled' => 'gray',
                        default => null,
                    })
                    ->extraAttributes(fn (Order $record) => [
                        'style' => $record->status === 'pending'
                            ? 'cursor: pointer; text-decoration: underline;'
                            : '',
                        'title' => $record->status === 'pending'
                            ? 'Klik untuk ubah menjadi Paid'
                            : '',
                    ]),

                ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->circular(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
