<?php

namespace App\Filament\Resources\ImageSettingResource\Pages;

use App\Filament\Resources\ImageSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImageSettings extends ListRecords
{
    protected static string $resource = ImageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
