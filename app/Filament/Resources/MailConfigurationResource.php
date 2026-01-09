<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailConfigurationResource\Pages;
use App\Filament\Resources\MailConfigurationResource\RelationManagers;
use App\Models\MailConfiguration;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MailConfigurationResource extends Resource
{
    protected static ?string $model = MailConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('mail_host')
                        ->label('Mail Host')
                        ->required(),
                    
                    TextInput::make('mail_port')
                        ->label('Mail Port')
                        ->required(),
                    
                    TextInput::make('mail_username')
                        ->label('Mail Username')
                        ->required()
                        ->reactive() 
                        ->afterStateUpdated(function ($state, $set) {
                            $set('mail_from_address', $state);
                        }),

                    TextInput::make('mail_password')
                        ->label('Mail Password')
                        ->password()
                        ->required()
                        ->revealable(),

                    Select::make('mail_encryption')
                        ->label('Mail Encryption')
                        ->options([
                            'tls' => 'TLS',
                            'ssl' => 'SSL',
                        ])
                        ->required(),

                    TextInput::make('mail_from_address')
                        ->label('Mail From Address')
                        ->required(),

                    TextInput::make('mail_from_name')
                        ->label('Mail From Name')
                        ->required(),
                    Toggle::make('is_active'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mail_host'),
                TextColumn::make('mail_port'),
                TextColumn::make('mail_username'),
                TextColumn::make('mail_from_address'),
                TextColumn::make('mail_from_name'),
                CheckboxColumn::make('is_active'),
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailConfigurations::route('/'),
            'create' => Pages\CreateMailConfiguration::route('/create'),
            'edit' => Pages\EditMailConfiguration::route('/{record}/edit'),
        ];
    }
}
