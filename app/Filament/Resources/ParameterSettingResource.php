<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParameterSettingResource\Pages;
use App\Filament\Resources\ParameterSettingResource\RelationManagers;
use App\Models\ParameterSetting;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParameterSettingResource extends Resource
{
    protected static ?string $model = ParameterSetting::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                TextInput::make('key')
                    ->hint('Use lowercase letters and replace spaces with underscores (_)')
                    ->regex('/^[^\s]*$/')
                    ->required(),
                Textarea::make('value')
                    ->required(),
                Select::make('type')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'contact' => 'Contact Information',
                        'general' => 'General Information',
                    ])
                    ->required(),
                // FileUpload::make('attachment')
                //     ->image()
                //     ->openable()
                //     ->downloadable() 
                //     ->hint('If needed to use a file'),
 
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('key'),
                TextColumn::make('value')->limit(30),
                TextColumn::make('type'),
                // TextColumn::make('attachment'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                ->options([
                    'header' => 'Header',
                    'footer' => 'Footer',
                    'contact' => 'Contact Information',
                    'general' => 'General Information',
                ])
                ->placeholder('All Types'),
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
            'index' => Pages\ListParameterSettings::route('/'),
            'create' => Pages\CreateParameterSetting::route('/create'),
            'edit' => Pages\EditParameterSetting::route('/{record}/edit'),
        ];
    }
}
