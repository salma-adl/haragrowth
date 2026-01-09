<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubMenuResource\Pages;
use App\Filament\Resources\SubMenuResource\RelationManagers;
use App\Models\Menu;
use App\Models\SubMenu;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubMenuResource extends Resource
{
    protected static ?string $model = SubMenu::class;

    protected static ?string $navigationGroup = 'Menu';

    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->can('view-submenu')) {
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
                    Select::make('menu_id')
                    ->options(Menu::all()->mapWithKeys(function ($menu) {
                        return [$menu->id => "{$menu->title} - {$menu->type}"];
                    })),
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('description'),
                    Select::make('route_id')
                        ->relationship('route','name')
                        ->required(),
                    
                    FileUpload::make('icon')->image()->openable()->downloadable()->disk('public'),
                    TextInput::make('index')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(20)
                        ->required(),
                    Toggle::make('is_active'),
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                ImageColumn::make('icon'),
                TextColumn::make('title')->limit(50)->sortable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('index')->sortable(),
                TextColumn::make('menu_id')->limit(50),
                // TextColumn::make('route_id'),
                CheckboxColumn::make('is_active'),
            ])
            ->filters([
                SelectFilter::make('menu_id')
                ->label('Filter by Menu')
                ->options(function () {
                    return Menu::all()->mapWithKeys(function ($menu) {
                        return [$menu->id => "{$menu->title} - {$menu->type}"];
                    });
                })
                ->multiple() 
                ->preload(),             
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
            'index' => Pages\ListSubMenus::route('/'),
            'create' => Pages\CreateSubMenu::route('/create'),
            'edit' => Pages\EditSubMenu::route('/{record}/edit'),
        ];
    }
}
