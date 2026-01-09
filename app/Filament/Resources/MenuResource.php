<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers\SubMenusRelationManager;
use App\Models\Menu;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationGroup = 'Menu';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->can('view-menu')) {
            return true;
        }else{
            return false;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('description'),
                    Select::make('type')
                        ->options([
                            'header' => 'Header',
                            'footer' => 'Footer',
                        ])
                        ->required(),
                    Select::make('route_id')
                        ->relationship('route','name'),
                    TextInput::make('index')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(20),
                    Toggle::make('is_active'),
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title')->limit(50)->sortable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('type')->limit(50),
                TextColumn::make('index')->sortable(),
                CheckboxColumn::make('is_active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                ->options([
                    'header' => 'Header',
                    'footer' => 'Footer',
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
            SubMenusRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
