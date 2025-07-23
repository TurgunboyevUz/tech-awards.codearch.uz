<?php

namespace Filament\Resources\ConvertationResource\Pages;

use Filament\Resources\ConvertationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConvertation extends EditRecord
{
    protected static string $resource = ConvertationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
