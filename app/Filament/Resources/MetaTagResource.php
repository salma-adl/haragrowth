<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetaTagResource\Pages;
use App\Filament\Resources\MetaTagResource\RelationManagers;
use App\Models\MetaTag;
use App\Models\Route;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetaTagResource extends Resource
{
    protected static ?string $model = MetaTag::class;

    protected static ?string $navigationGroup = 'Menu';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Card::make()
                ->schema([
                    // Select::make('route_id')
                    // ->options(Route::all()->mapWithKeys(function ($route) {
                    //     return [$route->id => "{$route->name}"];
                    // })),
                    // TextInput::make('title')
                    //     ->required(),
                    // // TextInput::make('description')
                    // //     ->required(),
                    Select::make('route_id')
                        ->relationship('route','name')->reactive() // Menandakan bahwa perubahan pada field ini akan menyebabkan update pada field lain
                        ->afterStateUpdated(function (callable $set, $state) {
                            // Cari route berdasarkan route_id yang dipilih
                            $route = \App\Models\Route::find($state);
                            if ($route) {
                                // Set nilai og_url berdasarkan path dari route
                                $set('og_url', $route->path);
                                // Set nilai twitter_site berdasarkan twitter_site dari route
                                $set('twitter_site', $route->path);
                            }
                        }),
    
                // Kolom 'description' (text) => Textarea
                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(500),
    
                // Kolom 'keywords' (string) => TextInput
                TagsInput::make('keywords')
                ->label('Keywords')
                ->required()
                ->placeholder('Add keywords (e.g. about us, tech, Finance)')
                // ->maxItems(10)  // Maksimum jumlah tag yang bisa ditambahkan
                ->separator(',') // Memisahkan tag dengan koma
                ->helperText('Add keywords related to your page, separated by commas.'),
    
                TextInput::make('og_title')
                    ->label('Open Graph Title')
                    ->required()
                    ->maxLength(255),
                // Kolom 'og_description' (text) => Textarea
                Textarea::make('og_description')
                    ->label('Open Graph Description')
                    ->required()
                    ->maxLength(500),
    
                // Kolom 'og_image' (string) => 
                FileUpload::make('og_image')->image()->openable()->downloadable(),
                // Kolom 'og_url' (string) => TextInput (URL untuk halaman)
                TextInput::make('og_url')
                    ->label('Open Graph URL')
                    ->required()
                    ->maxLength(255),
    
                // Kolom 'twitter_card' (string) => Select untuk pilihan tipe card
                Select::make('twitter_card')
                    ->label('Twitter Card Type')
                    ->options([
                        'summary' => 'Summary',
                        'summary_large_image' => 'Summary with Large Image',
                        'app' => 'App',
                        'player' => 'Player',
                    ])
                    ->default('summary_large_image')
                    ->required(),
    
                // Kolom 'twitter_title' (string) => TextInput
                TextInput::make('twitter_title')
                    ->label('Twitter Title')
                    ->required()
                    ->maxLength(255),
    
                // Kolom 'twitter_description' (text) => Textarea
                Textarea::make('twitter_description')
                    ->label('Twitter Description')
                    ->required()
                    ->maxLength(500),
    
                // Kolom 'twitter_image'
                FileUpload::make('twitter_image')->image()->openable()->downloadable(),
    
                // Kolom 'twitter_site' (string) => TextInput
                TextInput::make('twitter_site')
                    ->label('Twitter Site')
                    ->required()
                    ->maxLength(255),
    
                // Kolom 'twitter_creator' (string) => TextInput (Optional)
                TextInput::make('twitter_creator')
                    ->label('Twitter Creator')
                    ->nullable()
                    ->maxLength(255),

                // Toggle::make('is_active'),
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('og_title')->limit(30)->sortable(),
                TextColumn::make('twitter_title')->limit(30)->sortable(),
                TextColumn::make('description')->limit(30),
                TextColumn::make('og_description')->limit(30),
                TextColumn::make('twitter_description')->limit(30),
                TextColumn::make('route_id')->limit(30),
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
            'index' => Pages\ListMetaTags::route('/'),
            'create' => Pages\CreateMetaTag::route('/create'),
            'edit' => Pages\EditMetaTag::route('/{record}/edit'),
        ];
    }
}
