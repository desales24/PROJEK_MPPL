<?php

namespace App\Filament\Admin\Resources\OrderStatusResource\Pages;

use App\Filament\Admin\Resources\OrderStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderStatus extends CreateRecord
{
    protected static string $resource = OrderStatusResource::class;
}
