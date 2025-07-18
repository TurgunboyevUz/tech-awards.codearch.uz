<?php

namespace Filament\Resources\CurrencyResource\Pages;

use App\Traits\FilamentRedirect;
use Filament\Resources\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurrency extends EditRecord
{
    use FilamentRedirect;

    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
