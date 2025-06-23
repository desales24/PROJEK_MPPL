<?php

namespace App\Filament\Casir\Resources;

use App\Filament\Casir\Resources\OrderResource\Pages;
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

    private static function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $menu = Menu::find($item['menu_id'] ?? null);
            if ($menu) {
                $total += $menu->price * ($item['quantity'] ?? 0);
            }
        }
        return $total;
    }

    public static function form(Form $form): Form
    {
        $menuOptions = Menu::pluck('name', 'id');

        return $form->schema([
            Forms\Components\Section::make('Informasi Pesanan')->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Pelanggan')
                    ->searchable()
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
            ])->columns(2),

            Forms\Components\Section::make('Item Pesanan')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('menu_id')
                            ->label('Menu')
                            ->options($menuOptions)
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
                    ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation())
                    ->afterStateUpdated(fn (callable $set, $state) =>
                        $set('total', self::calculateTotal($state)))
                    ->afterStateHydrated(fn ($state, callable $set) =>
                        $set('total', self::calculateTotal($state))),
            ]),

            Forms\Components\Section::make('Total')->schema([
                Forms\Components\Placeholder::make('total_display')
                    ->label('Total Pembayaran')
                    ->content(fn (callable $get) =>
                        'Rp ' . number_format(self::calculateTotal($get('items') ?? []), 2)
                    ),

                Forms\Components\Hidden::make('total')
                    ->dehydrateStateUsing(fn (callable $get) =>
                        self::calculateTotal($get('items') ?? [])),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('customer.name')
                ->label('Pelanggan')
                ->sortable(),

            Tables\Columns\SelectColumn::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('IDR', true)
                ->sortable(),

            Tables\Columns\TextColumn::make('order_time')
                ->label('Waktu Pesan')
                ->dateTime()
                ->sortable(),

            Tables\Columns\TextColumn::make('items_count')
                ->label('Jumlah Item')
                ->counts('items'),
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
