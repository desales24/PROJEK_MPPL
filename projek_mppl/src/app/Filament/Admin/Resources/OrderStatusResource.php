<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderStatusResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as TableComponent;

class OrderStatusResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $label = 'Status Order';
    protected static ?string $pluralLabel = 'Status Order';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->label('Status Order')
                ->options([
                    'preparing' => 'Sedang Dipersiapkan',
                    'served' => 'Disajikan',
                    'finished' => 'Selesai',
                ])
                ->required(),
        ]);
    }

    public static function table(TableComponent $table): TableComponent
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('customer.name')
                ->label('Pelanggan')
                ->searchable(),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'primary' => 'preparing',
                    'warning' => 'served',
                    'success' => 'finished',
                ]),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i')
                ->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Ubah Status'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderStatuses::route('/'),
            'edit' => Pages\EditOrderStatus::route('/{record}/edit'),
        ];
    }
}
