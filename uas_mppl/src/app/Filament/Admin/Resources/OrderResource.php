<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Menu;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $navigationLabel = 'Pesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->label('Pelanggan')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required(),

                        Forms\Components\DateTimePicker::make('order_time')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Item Pesanan')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('menu_id')
                                    ->label('Menu')
                                    ->options(Menu::pluck('name', 'id'))
                                    ->required()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),

                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Item')
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                            )
                            ->afterStateUpdated(function (callable $set, $state) {
                                $total = 0;
                                foreach ($state as $item) {
                                    $menu = Menu::find($item['menu_id'] ?? null);
                                    if ($menu) {
                                        $total += $menu->price * ($item['quantity'] ?? 0);
                                    }
                                }
                                $set('total', $total);
                            })
                            ->afterStateHydrated(function ($state, callable $set) {
                                $total = 0;
                                foreach ($state as $item) {
                                    $menu = Menu::find($item['menu_id'] ?? null);
                                    if ($menu) {
                                        $total += $menu->price * ($item['quantity'] ?? 0);
                                    }
                                }
                                $set('total', $total);
                            }),
                    ]),

                Forms\Components\Section::make('Total')
                    ->schema([
                        Forms\Components\Placeholder::make('total_display')
                            ->label('Total Pembayaran')
                            ->content(function (callable $get) {
                                $total = 0;
                                foreach ($get('items') ?? [] as $item) {
                                    $menu = Menu::find($item['menu_id'] ?? null);
                                    if ($menu) {
                                        $total += $menu->price * ($item['quantity'] ?? 0);
                                    }
                                }
                                return 'Rp ' . number_format($total, 2);
                            }),

                        Forms\Components\Hidden::make('total')
                            ->dehydrateStateUsing(function (callable $get) {
                                $total = 0;
                                foreach ($get('items') ?? [] as $item) {
                                    $menu = Menu::find($item['menu_id'] ?? null);
                                    if ($menu) {
                                        $total += $menu->price * ($item['quantity'] ?? 0);
                                    }
                                }
                                return $total;
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_time')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Jumlah Item'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ]),
            ])
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
