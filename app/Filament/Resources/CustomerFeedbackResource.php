<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerFeedbackResource\Pages;
use App\Filament\Resources\CustomerFeedbackResource\RelationManagers;
use App\Models\CustomerFeedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerFeedbackResource extends Resource
{
    protected static ?string $model = CustomerFeedback::class;

    protected static ?string $navigationGroup = 'Customer';

    protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';

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
                TextColumn::make('id'),
                TextColumn::make('customer.name')  // Mengambil 'name' dari relasi 'customer'
                    ->label('Name')        // Nama label kolom
                    ->sortable(),
                TextColumn::make('customer.email')  // Mengambil 'email' dari relasi 'customer'
                    ->label('Email')      // Nama label kolom
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('topic')  // Mengambil 'email' dari relasi 'customer'
                    // ->label('Email')      // Nama label kolom
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('message')  // Mengambil 'email' dari relasi 'customer'
                    // ->label('Email')      // Nama label kolom
                    ->limit(50)
                    ->sortable(),
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
            'index' => Pages\ListCustomerFeedback::route('/'),
            'create' => Pages\CreateCustomerFeedback::route('/create'),
            'edit' => Pages\EditCustomerFeedback::route('/{record}/edit'),
        ];
    }
}
