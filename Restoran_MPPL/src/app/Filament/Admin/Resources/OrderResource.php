<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as TableComponent;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $label = 'Order';
    protected static ?string $pluralLabel = 'Daftar Order';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['orderItems.menu', 'customer']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('customer_id')
                ->label('Pelanggan')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')->label('Nama')->required(),
                    Forms\Components\TextInput::make('phone')->label('Telepon'),
                    Forms\Components\TextInput::make('email')->label('Email')->email(),
                ]),

            Forms\Components\Repeater::make('orderItems')
                ->label('Daftar Pesanan')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('menu_id')
                        ->label('Menu')
                        ->relationship('menu', 'name')
                        ->required()
                        ->reactive(),

                    Forms\Components\Placeholder::make('menu_price')
                        ->label('Harga Menu')
                        ->content(function ($get) {
                            $menuId = $get('menu_id');
                            if (!$menuId) return '-';
                            $menu = Menu::find($menuId);
                            return $menu ? 'Rp ' . number_format($menu->price, 0, ',', '.') : '-';
                        }),

                    Forms\Components\TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->columns(3)
                ->createItemButtonLabel('Tambah Item'),

            Forms\Components\Placeholder::make('total')
                ->label('Total Harga')
                ->content(function ($state, $get) {
                    $items = $get('orderItems') ?? [];
                    $total = 0;
                    foreach ($items as $item) {
                        if (!isset($item['menu_id'], $item['quantity'])) continue;
                        $menu = Menu::find($item['menu_id']);
                        if ($menu) $total += $menu->price * intval($item['quantity']);
                    }
                    return 'Rp ' . number_format($total, 0, ',', '.');
                })
                ->disabled()
                ->columnSpanFull(),
        ]);
    }

    public static function table(TableComponent $table): TableComponent
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Pelanggan')
                ->searchable(),

            Tables\Columns\TextColumn::make('total_harga')
                ->label('Total Harga')
                ->getStateUsing(function ($record) {
                    $total = 0;
                    foreach ($record->orderItems as $item) {
                        $total += $item->quantity * ($item->menu->price ?? 0);
                    }
                    return 'Rp ' . number_format($total, 0, ',', '.');
                }),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i')
                ->sortable(),
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
        return [
            // Tambahkan RelationManager jika diperlukan
        ];
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
