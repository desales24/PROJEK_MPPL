<?php

namespace App\Filament\Casir\Resources\PaymentResource\Pages;

use App\Filament\Casir\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
