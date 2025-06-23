<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $label = 'Pembayaran';
    protected static ?string $pluralLabel = 'Data Pembayaran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->label('Order (Belum / Gagal / Pending)')
                ->options(function () {
                    return Order::whereDoesntHave('payment')
                        ->orWhereHas('payment', function ($query) {
                            $query->whereIn('status', ['pending', 'failed']);
                        })
                        ->with('customer')
                        ->get()
                        ->mapWithKeys(fn ($order) => [
                            $order->id => 'Order #' . $order->id . ' - ' . $order->customer->name
                        ]);
                })
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $order = Order::with('orderItems.menu')->find($state);
                    if ($order) {
                        $total = 0;
                        foreach ($order->orderItems as $item) {
                            $total += $item->menu->price * $item->quantity;
                        }
                        $set('amount', $total);
                    }
                })
                ->required(),

            Forms\Components\Select::make('method')
                ->label('Metode Pembayaran')
                ->options([
                    'cash' => 'Tunai',
                    'qris' => 'QRIS',
                    'debit' => 'Transfer Bank',
                ])
                ->default('debit')
                ->required()
                ->reactive(),

            Forms\Components\FileUpload::make('proof_of_payment')
                ->label('Bukti Pembayaran')
                ->directory('bukti-pembayaran')
                ->image()
                ->imagePreviewHeight('150')
                ->nullable()
                ->disk('public'),

            Forms\Components\TextInput::make('amount')
                ->label('Jumlah Bayar')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'paid' => 'Lunas',
                    'pending' => 'Pending',
                    'failed' => 'Gagal',
                ])
                ->required(),

            Forms\Components\DateTimePicker::make('paid_at')
                ->label('Waktu Pembayaran')
                ->default(now())
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.customer.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metode'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->tooltip(fn ($record) => $record->status !== 'paid' ? 'Klik untuk tandai lunas' : null)
                    ->action(function ($record) {
                        if ($record->status !== 'paid') {
                            $record->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);
                        }
                    }),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\ImageColumn::make('proof_of_payment')
                    ->label('Bukti')
                    ->disk('public')
                    ->circular()
                    ->height(80)
                    ->width(80),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('cetakStruk')
                    ->label('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn ($record) => route('cetak.struk', ['payment' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('downloadStruk')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.struk.download', ['payment' => $record->id]))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
