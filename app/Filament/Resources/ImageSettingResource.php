<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageSettingResource\Pages;
use App\Filament\Resources\ImageSettingResource\RelationManagers;
use App\Models\ImageSetting;
use Faker\Core\File;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Nette\Utils\ImageColor;

class ImageSettingResource extends Resource
{
    protected static ?string $model = ImageSetting::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

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
                TextInput::make('alt')
                    ->required(),
                Select::make('type')
                    ->options([
                        'industry-logo' => 'Industry Logo',
                        'footer' => 'Footer',
                    ])
                    ->required(),
                FileUpload::make('attachment')
                    ->image()
                    ->disk('public')
                    ->openable()
                    ->downloadable()
                    ->required()
                    ->label('Image'),
                FileUpload::make('dark_attachment')
                    ->image()
                    ->disk('public')
                    ->openable()
                    ->downloadable()
                    ->required()
                    ->label('Image Dark Mode')
                    ->hint('upload the same image if there is no dark mode'),    
                Toggle::make('is_dark_mode'),
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('key'),
                ImageColumn::make('attachment')->label('Image'),
                ImageColumn::make('dark_attachment')->label('Dark Image'),
                TextColumn::make('alt')->limit(30),
                TextColumn::make('type'),
                CheckboxColumn::make('is_dark_mode'),
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
            'index' => Pages\ListImageSettings::route('/'),
            'create' => Pages\CreateImageSetting::route('/create'),
            'edit' => Pages\EditImageSetting::route('/{record}/edit'),
        ];
    }
}
