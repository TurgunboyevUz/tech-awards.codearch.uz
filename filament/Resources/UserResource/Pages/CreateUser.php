<?php

namespace Filament\Resources\UserResource\Pages;

use App\Traits\FilamentRedirect;
use Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use FilamentRedirect;

    protected static string $resource = UserResource::class;
}
