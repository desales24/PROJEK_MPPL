<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TableResource\Pages;
use App\Models\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as TableComponent;

class TableResource extends Resource
{
    protected static ?string $model = Table::class;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $label = 'Meja';
    protected static ?string $pluralLabel = 'Meja Restoran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('table_number')
                ->label('Nomor Meja')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(10),

            Forms\Components\TextInput::make('capacity')
                ->label('Kapasitas')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'available' => 'Tersedia',
                    'occupied' => 'Terisi',
                    'reserved' => 'Reservasi',
                ])
                ->required(),
        ]);
    }

    public static function table(TableComponent $table): TableComponent
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('table_number')
                    ->label('Meja')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'occupied',
                        'warning' => 'reserved',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'available' => 'Tersedia',
                        'occupied' => 'Terisi',
                        'reserved' => 'Reservasi',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            // nanti bisa ditambahkan relation ke orders
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
