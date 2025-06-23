<?php

namespace App\Filament\Casir\Resources\OrderResource\Pages;

use App\Filament\Casir\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
