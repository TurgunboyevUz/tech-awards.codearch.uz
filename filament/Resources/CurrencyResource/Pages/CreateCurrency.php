<?php

namespace Filament\Resources\CurrencyResource\Pages;

use App\Traits\FilamentRedirect;
use Filament\Resources\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCurrency extends CreateRecord
{
    use FilamentRedirect;

    protected static string $resource = CurrencyResource::class;
}
