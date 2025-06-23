<?php

namespace App\Filament\Admin\Resources\OrderStatusResource\Pages;

use App\Filament\Admin\Resources\OrderStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderStatuses extends ListRecords
{
    protected static string $resource = OrderStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
