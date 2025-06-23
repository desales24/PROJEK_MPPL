<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\PaymentResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pembayaran Saya';
    protected static ?string $modelLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('order_id')
                ->label('Pesanan')
                ->searchable()
                ->required()
                ->options(
                    Order::where('customer_id', Auth::id())
                        ->whereHas('payment', fn ($q) => $q->whereIn('status', ['belum bayar', 'gagal']))
                        ->orWhereDoesntHave('payment')
                        ->with('customer')
                        ->get()
                        ->mapWithKeys(fn ($order) => [
                            $order->id => '#' . $order->id . ' (Rp ' . number_format($order->total, 0, ',', '.') . ')',
                        ])
                )
                ->live()
                ->afterStateUpdated(fn ($set, $state) => $set('amount', Order::find($state)?->total ?? 0)),

            Select::make('method')
                ->label('Metode Pembayaran')
                ->options([
                    'tunai' => 'Tunai',
                    'kartu kredit' => 'Kartu Kredit',
                    'qris' => 'QRIS',
                ])
                ->required()
                ->live(),

            TextInput::make('amount')
                ->label('Jumlah Pembayaran')
                ->numeric()
                ->disabled()
                ->dehydrated(),

            FileUpload::make('proof_of_payment')
                ->label('Bukti Pembayaran')
                ->image()
                ->directory('proofs')
                ->downloadable()
                ->previewable(),

            DateTimePicker::make('paid_at')
                ->label('Waktu Pembayaran')
                ->default(now())
                ->required(),

            // Status tidak ditampilkan di form customer
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('order.id')->label('No. Pesanan'),
            TextColumn::make('method')->label('Metode'),
            TextColumn::make('amount')->label('Jumlah')->money('IDR', true),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'belum bayar' => 'warning',
                    'lunas' => 'success',
                    'gagal' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('paid_at')->label('Waktu Bayar')->dateTime(),

            TextColumn::make('proof_of_payment')
                ->label('Bukti Pembayaran')
                ->formatStateUsing(fn ($state) => $state ? 'Lihat' : '-'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make()
                ->visible(fn ($record) => in_array($record->status, ['belum bayar', 'gagal'])),
            Action::make('cetakStruk')
                ->label('Cetak Struk')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->visible(fn ($record) => $record->status === 'lunas')
                ->url(fn ($record) => route('print.struk', ['payment' => $record->id]))
                ->openUrlInNewTab(),
        ])
        ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        });
    }
}
