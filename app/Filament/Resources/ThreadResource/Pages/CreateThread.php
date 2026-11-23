<?php

namespace App\Filament\Resources\ThreadResource\Pages;

use App\Filament\Resources\ThreadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateThread extends CreateRecord
{
    protected static string $resource = ThreadResource::class;

    protected function getRedirectUrl(): string
    {
        return ThreadResource::getUrl('index');
    }
}
