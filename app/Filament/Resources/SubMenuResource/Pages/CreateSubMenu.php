<?php

namespace App\Filament\Resources\SubMenuResource\Pages;

use App\Filament\Resources\SubMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubMenu extends CreateRecord
{
    protected static string $resource = SubMenuResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
