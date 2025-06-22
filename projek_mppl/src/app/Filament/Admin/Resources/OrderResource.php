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

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?string $modelLabel = 'Pesanan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // PILIH ATAU BUAT CUSTOMER
            Forms\Components\Select::make('customer_id')
                ->label('Pelanggan')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('No. HP')
                        ->nullable(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->nullable(),
                ])
                ->createOptionAction(function ($action) {
                    return $action
                        ->modalHeading('Tambah Pelanggan Baru')
                        ->modalSubmitActionLabel('Simpan')
                        ->modalWidth('lg');
                }),

            // PILIH MEJA
            Forms\Components\Select::make('table_id')
                ->label('Meja')
                ->relationship('table', 'table_number')
                ->searchable()
                ->required(),

            // TANGGAL ORDER
            Forms\Components\DateTimePicker::make('order_date')
                ->label('Tanggal Pesanan')
                ->default(now())
                ->required(),

            // STATUS
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Diproses',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ])
                ->default('pending')
                ->required(),

            // UPLOAD BUKTI PEMBAYARAN
            Forms\Components\FileUpload::make('proof_of_payment')
                ->label('Bukti Pembayaran')
                ->directory('payments')
                ->image()
                ->imagePreviewHeight('100')
                ->columnSpanFull()
                ->nullable(),

            // RINCIAN ITEM PESANAN
            Forms\Components\Repeater::make('order_items')
                ->label('Item Pesanan')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('menu_id')
                        ->relationship('menu', 'name')
                        ->label('Menu')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('price', Menu::find($state)?->price ?? 0)),

                    Forms\Components\TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->required()
                        ->default(1),

                    Forms\Components\TextInput::make('price')
                        ->label('Harga per Item')
                        ->numeric()
                        ->disabled()
                        ->required()
                        ->dehydrated(),
                ])
                ->columns(3)
                ->createItemButtonLabel('Tambah Item'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Pelanggan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('table.table_number')->label('Meja')->sortable(),
                Tables\Columns\TextColumn::make('order_date')->label('Tanggal')->dateTime()->sortable(),
                Tables\Columns\BadgeColumn::make('status')->label('Status')->colors([
                    'primary' => 'pending',
                    'warning' => 'processing',
                    'success' => 'completed',
                    'danger' => 'cancelled',
                ]),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
