<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubMenusRelationManager extends RelationManager
{
    protected static string $relationship = 'relationSubMenus';

    public function form(Form $form): Form
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
                    
                    FileUpload::make('icon')->image()->openable()->downloadable(),
                    TextInput::make('index')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(20)
                        ->required(),
                    Toggle::make('is_active'),
            ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('id'),
                ImageColumn::make('icon'),
                TextColumn::make('title')->limit(50)->sortable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('index')->sortable(),
                CheckboxColumn::make('is_active'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
