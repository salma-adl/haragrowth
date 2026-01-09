<?php

namespace App\Filament\Resources\ParameterSettingResource\Pages;

use App\Filament\Resources\ParameterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParameterSettings extends ListRecords
{
    protected static string $resource = ParameterSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
