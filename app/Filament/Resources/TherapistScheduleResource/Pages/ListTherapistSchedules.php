<?php

namespace App\Filament\Resources\TherapistScheduleResource\Pages;

use App\Filament\Resources\TherapistScheduleResource;
use App\Filament\Resources\TherapistScheduleResource\Widgets\TherapistCalendarWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTherapistSchedules extends ListRecords
{
    protected static string $resource = TherapistScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TherapistCalendarWidget::class,
        ];
    }
}
