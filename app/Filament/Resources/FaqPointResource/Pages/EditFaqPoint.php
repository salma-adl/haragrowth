<?php

namespace App\Filament\Resources\FaqPointResource\Pages;

use App\Filament\Resources\FaqPointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaqPoint extends EditRecord
{
    protected static string $resource = FaqPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
