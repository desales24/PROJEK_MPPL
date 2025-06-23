<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->relationship('order', 'id')
                            ->label('ID Pesanan')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('method')
                            ->options([
                                'tunai' => 'Tunai',
                                'kartu kredit' => 'Kartu Kredit',
                                'qris' => 'QRIS',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->maxValue(99999999.99),

                        Forms\Components\Select::make('status')
                            ->options([
                                'belum bayar' => 'Belum Bayar',
                                'lunas' => 'Lunas',
                                'gagal' => 'Gagal',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Waktu Pembayaran')
                            ->native(false),

                        Forms\Components\FileUpload::make('proof_of_payment')
                            ->label('Bukti Pembayaran')
                            ->directory('payment-proofs')
                            ->image()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('order.id')
                    ->label('ID Pesanan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metode')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tunai' => 'Tunai',
                        'kartu kredit' => 'Kartu Kredit',
                        'qris' => 'QRIS',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'belum bayar' => 'warning',
                        'gagal' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'lunas' => 'Lunas',
                        'belum bayar' => 'Belum Bayar',
                        'gagal' => 'Gagal',
                        default => $state,
                    })
                    ->action(
                        Tables\Actions\Action::make('markAsPaid')
                            ->label('Tandai sebagai Lunas')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                            ->requiresConfirmation()
                            ->modalHeading('Konfirmasi Pembayaran')
                            ->modalDescription('Apakah Anda yakin ingin menandai pembayaran ini sebagai lunas?')
                            ->action(function (Payment $record) {
                                $record->update([
                                    'status' => 'lunas',
                                    'paid_at' => now(),
                                ]);
                            })
                            ->visible(fn (Payment $record): bool => $record->status !== 'lunas')
                    ),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('method')
                    ->options([
                        'tunai' => 'Tunai',
                        'kartu kredit' => 'Kartu Kredit',
                        'qris' => 'QRIS',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'belum bayar' => 'Belum Bayar',
                        'lunas' => 'Lunas',
                        'gagal' => 'Gagal',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // ViewAction dihapus sesuai permintaan
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
            // Tambahkan relation managers jika diperlukan
        ];
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