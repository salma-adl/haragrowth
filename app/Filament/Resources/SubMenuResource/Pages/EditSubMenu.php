<?php

namespace App\Filament\Resources\SubMenuResource\Pages;

use App\Filament\Resources\SubMenuResource;
use App\Models\SubMenu;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditSubMenu extends EditRecord
{
    protected static string $resource = SubMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(function (SubMenu $record) {
                // delete single
                if ($record->icon) {
                   Storage::disk('public')->delete($record->icon);
                }
             }),
        ];
    }
}
