<?php

namespace App\Filament\Resources\ParameterSettingResource\Pages;

use App\Filament\Resources\ParameterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateParameterSetting extends CreateRecord
{
    protected static string $resource = ParameterSettingResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
