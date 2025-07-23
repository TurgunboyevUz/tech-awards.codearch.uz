<?php

namespace Filament\Resources\ConvertationResource\Pages;

use Filament\Resources\ConvertationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConvertations extends ListRecords
{
    protected static string $resource = ConvertationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
