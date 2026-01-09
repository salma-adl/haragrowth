<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TherapistScheduleResource\Pages;
use App\Filament\Resources\TherapistScheduleResource\RelationManagers;
use App\Models\TherapistSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TherapistScheduleResource extends Resource
{
    protected static ?string $model = TherapistSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTherapistSchedules::route('/'),
            'create' => Pages\CreateTherapistSchedule::route('/create'),
            'edit' => Pages\EditTherapistSchedule::route('/{record}/edit'),
        ];
    }
}
