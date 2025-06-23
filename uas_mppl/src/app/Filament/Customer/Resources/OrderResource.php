<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\OrderResource\Pages;
use App\Models\Menu;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pesanan Saya';
    protected static ?string $modelLabel = 'Pesanan';

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
            Forms\Components\Hidden::make('customer_id')
                ->default(fn () => Auth::id()),

            Forms\Components\Hidden::make('status')
                ->default('pending'),

            Forms\Components\DateTimePicker::make('order_time')
                ->default(now())
                ->required(),

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
                ->addActionLabel('Tambah Menu')
                ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation())
                ->afterStateUpdated(fn (callable $set, $state) => $set('total', self::calculateTotal($state)))
                ->afterStateHydrated(fn ($state, callable $set) => $set('total', self::calculateTotal($state))),

            Forms\Components\Placeholder::make('total_display')
                ->label('Total Bayar')
                ->content(fn (callable $get) =>
                    'Rp ' . number_format(self::calculateTotal($get('items') ?? []), 2)
                ),

            Forms\Components\Hidden::make('total')
                ->dehydrateStateUsing(fn (callable $get) =>
                    self::calculateTotal($get('items') ?? [])
                ),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_time')
                    ->label('Waktu Pesan')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Jumlah Item'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status === 'pending'),
            ])
            ->bulkActions([]);
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('customer_id', Auth::id());
    }
}
