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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Check if any of the "hasil terapi" fields are filled
        // We use strip_tags to ensure we don't count empty HTML tags from RichEditor as filled
        $hasDiagnosis = !empty(trim(strip_tags($data['diagnosis'] ?? '')));
        $hasNotes = !empty(trim(strip_tags($data['therapist_notes'] ?? '')));
        $hasRecommendation = !empty(trim(strip_tags($data['recommendation'] ?? '')));

        if ($hasDiagnosis || $hasNotes || $hasRecommendation) {
            $data['status'] = 'completed';
        }

        return $data;
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
