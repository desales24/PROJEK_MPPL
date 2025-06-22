<?php

namespace App\Filament\Admin\Resources\OrderStatusResource\Pages;

use App\Filament\Admin\Resources\OrderStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderStatus extends EditRecord
{
    protected static string $resource = OrderStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
