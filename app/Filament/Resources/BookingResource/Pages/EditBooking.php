<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Database\Eloquent\Builder;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function afterSave(): void
    {
        if (!empty($this->record->diagnosis)) {
            $this->record->update([
                'status' => 'completed',
            ]);
        }
    }

    protected function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('customer');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
