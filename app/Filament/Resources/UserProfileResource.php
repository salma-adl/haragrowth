<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserProfileResource\Pages;
use App\Models\UserProfile;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserProfileResource extends Resource
{
    protected static ?string $model = UserProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('user_id')
                            ->label('Email')
                            ->relationship('user', 'email')
                            ->hint('*Pastikan profil telah didaftarkan akun nya pada menu user')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('phone')
                            ->label('No Telepon')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->required(),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        DatePicker::make('birthdate')
                            ->label('Tanggal Lahir')
                            ->native(false),
                        TextInput::make('str_number')
                            ->label('Nomor STR'),
                        TextInput::make('sipp_number')
                            ->label('Nomor SIPP'),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->required(),
                        Select::make('services')
                            ->label('Layanan')
                            ->multiple()
                            ->relationship('services', 'name')
                            ->required(),
                        RichEditor::make('bio')
                            ->label('Bio'),
                        FileUpload::make('attachment')->image()->openable()->downloadable()->disk('public'), // Pastikan menggunakan disk 'public',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                ImageColumn::make('attachment')->label('Pas Foto'),
                TextColumn::make('name')->label('Nama'),
                TextColumn::make('services.name')
                    ->label('Bidang Keahlian')
                    ->badge()
                    ->limit(3)
                    ->html()
                    ->separator('<br>'),
                TextColumn::make('str_number')->label('Nomor STR'),
                TextColumn::make('sipp_number')->label('Nomor SIPP'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUserProfiles::route('/'),
            'create' => Pages\CreateUserProfile::route('/create'),
            'edit' => Pages\EditUserProfile::route('/{record}/edit'),
        ];
    }
}
