<?php

namespace App\Filament\Resources\TherapistScheduleResource\Pages;

use App\Filament\Resources\TherapistScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTherapistSchedule extends EditRecord
{
    protected static string $resource = TherapistScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
