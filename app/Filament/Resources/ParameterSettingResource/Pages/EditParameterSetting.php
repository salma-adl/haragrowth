<?php

namespace App\Filament\Resources\ParameterSettingResource\Pages;

use App\Filament\Resources\ParameterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParameterSetting extends EditRecord
{
    protected static string $resource = ParameterSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
