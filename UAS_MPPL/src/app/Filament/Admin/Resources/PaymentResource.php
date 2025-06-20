<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->label('Pesanan')
                ->options(function () {
                    $usedOrderIds = Payment::pluck('order_id')->toArray();

                    return Order::with('customer')
                        ->whereNotIn('id', $usedOrderIds)
                        ->get()
                        ->mapWithKeys(function ($order) {
                            return [
                                $order->id => 'Order #' . $order->id . ' - ' . $order->customer->name,
                            ];
                        });
                })
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $set) {
                    $order = Order::find($state);
                    if ($order) {
                        $set('amount', $order->total);
                    }
                }),

            Forms\Components\Select::make('method')
                ->label('Metode Pembayaran')
                ->options([
                    'DANA' => 'DANA',
                    'GoPay' => 'GoPay',
                    'QRIS' => 'QRIS',
                ])
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('Jumlah Bayar')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->required()
                ->afterStateHydrated(function ($state, callable $set, $record) {
                    if ($record && $record->order) {
                        $set('amount', $record->order->total);
                    }
                }),

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
                Tables\Columns\TextColumn::make('order.id')
                    ->label('ID Pesanan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metode'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Payment $record) => route('filament.struk', $record))
                    ->openUrlInNewTab(),

                Action::make('Download Struk')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->url(fn (Payment $record) => route('filament.struk.download', $record))
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
