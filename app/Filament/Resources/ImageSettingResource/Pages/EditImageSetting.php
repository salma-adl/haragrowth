<?php

namespace App\Filament\Resources\ImageSettingResource\Pages;

use App\Filament\Resources\ImageSettingResource;
use App\Models\ImageSetting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditImageSetting extends EditRecord
{
    protected static string $resource = ImageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->after(function (ImageSetting $record) {
                if ($record->attachment) {
                   Storage::disk('public')->delete($record->attachment);
                }
                if ($record->dark_attachment) {
                    Storage::disk('public')->delete($record->dark_attachment);
                 }
             }),
        ];
    }
}
