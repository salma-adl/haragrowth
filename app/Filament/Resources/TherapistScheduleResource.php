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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;

class TherapistScheduleResource extends Resource
{
    protected static ?string $model = TherapistSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('therapist_id')
                    ->relationship('therapist', 'name')
                    ->default(fn () => auth()->user()->hasRole('therapist') ? auth()->id() : null)
                    ->disabled(fn () => auth()->user()->hasRole('therapist'))
                    ->dehydrated()
                    ->required()
                    ->label('Therapist')
                    ->searchable()
                    ->preload(),
                DatePicker::make('available_date')
                    ->required()
                    ->native(false)
                    ->displayFormat('d M Y'),
                TimePicker::make('start_time')
                    ->required()
                    ->seconds(false),
                TimePicker::make('end_time')
                    ->required()
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('therapist.name')
                    ->label('Therapist')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('available_date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time('H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        if ($user && $user->hasRole('therapist') && !$user->hasRole('admin') && !$user->is_superuser) {
             $query->where('therapist_id', $user->id);
        }

        return $query;
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
