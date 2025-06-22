<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as TableComponent;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $label = 'Order';
    protected static ?string $pluralLabel = 'Daftar Order';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // PILIH PELANGGAN (dengan opsi tambah baru)
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

            // PILIH MEJA (langsung muncul semua nomor meja)
            Forms\Components\Select::make('table_id')
                ->label('Meja')
                ->options(fn () => \App\Models\Table::orderBy('table_number')->pluck('table_number', 'id'))
                ->required(),

            // STATUS ORDER
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'served' => 'Disajikan',
                    'completed' => 'Selesai',
                ])
                ->default('pending')
                ->required(),

            // REPEATER UNTUK ITEM PESANAN
            Forms\Components\Repeater::make('orderItems')
                ->label('Daftar Pesanan')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('menu_id')
                        ->label('Menu')
                        ->relationship('menu', 'name')
                        ->required(),

                    Forms\Components\TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->columns(2)
                ->createItemButtonLabel('Tambah Item'),

            // TOTAL HARGA OTOMATIS
            Forms\Components\Placeholder::make('total')
                ->label('Total Harga')
                ->content(function ($state, $get) {
                    $items = $get('orderItems') ?? [];
                    $total = 0;

                    foreach ($items as $item) {
                        if (!isset($item['menu_id'], $item['quantity'])) {
                            continue;
                        }

                        $menu = Menu::find($item['menu_id']);
                        if ($menu) {
                            $total += $menu->price * intval($item['quantity']);
                        }
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

            Tables\Columns\TextColumn::make('table.table_number')
                ->label('Meja'),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'primary' => 'pending',
                    'warning' => 'served',
                    'success' => 'completed',
                ]),

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
            // bisa ditambahkan relation manager jika ingin tampilkan daftar item
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
