<?php

namespace App\Filament\Resources\FaqPointResource\Pages;

use App\Filament\Resources\FaqPointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqPoints extends ListRecords
{
    protected static string $resource = FaqPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
