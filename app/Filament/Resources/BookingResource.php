<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getPluralLabel(): string
    {
        return 'Daftar Appointment';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->disabled()
                            ->required(),

                        Section::make('Data Pasien')
                            ->description('Periksa data customer dan pastikan data telah sesuai.')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Placeholder::make('customer_name')
                                            ->label('Nama')
                                            ->content(fn($record) => $record->customer?->name ?? '-')
                                            ->extraAttributes(['class' => 'border border-gray-300 rounded-md p-2 bg-white']),

                                        Placeholder::make('customer_email')
                                            ->label('Email')
                                            ->content(fn($record) => $record->customer?->email ?? '-')
                                            ->extraAttributes(['class' => 'border border-gray-300 rounded-md p-2 bg-white']),

                                        Placeholder::make('customer_age')
                                            ->label('Usia')
                                            ->content(fn($record) => $record->customer?->age ?? '-')
                                            ->extraAttributes(['class' => 'border border-gray-300 rounded-md p-2 bg-white']),
                                    ])
                            ]),

                        Section::make('Konsultasi Pasien')
                            ->schema([
                                Placeholder::make('service_name')
                                    ->label('Konsultasi')
                                    ->content(fn($record) => $record->service?->name ?? '-')
                                    ->extraAttributes(['class' => 'border border-gray-300 rounded-md p-2 bg-white']),
                                Placeholder::make('notes')
                                    ->label('Catatan')
                                    ->content(fn($record) => $record->notes ?? '-')
                                    ->extraAttributes([
                                        'class' => 'border border-gray-300 rounded-md p-2 bg-white',
                                    ]),
                            ]),

                        Section::make('Hasil Terapi')
                            ->description('*Catat hasil tindakan dan hal yang perlu pasien perhatikan setelah menjalankan terapi.')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Card::make()
                                            ->schema([
                                                Select::make('user_profile_id')
                                                    ->label('Terapis')
                                                    ->options(function ($record) {
                                                        if (!$record || !$record->service_id) {
                                                            return [];
                                                        }

                                                        return \App\Models\UserProfile::whereHas('services', function ($query) use ($record) {
                                                            $query->where('services.id', $record->service_id);
                                                        })
                                                            ->pluck('name', 'id');
                                                    })
                                                    ->searchable()
                                                    ->required(),
                                            ])
                                            ->columnSpanFull(),
                                        Card::make()
                                            ->schema([
                                                RichEditor::make('diagnosis')
                                                    ->label('Diagnosa')
                                                    ->required()
                                                    ->toolbarButtons([
                                                        'bold',
                                                        'italic',
                                                        'underline',
                                                        'strike',
                                                        'link',
                                                        'blockquote',
                                                        'codeBlock',
                                                        'heading',
                                                        'subheading',
                                                        'bulletList',
                                                        'orderedList',
                                                        'redo',
                                                        'undo',
                                                    ]),
                                            ])
                                            ->columnSpanFull(),

                                        Card::make()
                                            ->schema([
                                                RichEditor::make('therapist_notes')
                                                    ->label('Catatan Terapis')
                                                    ->required()
                                                    ->toolbarButtons([
                                                        'bold',
                                                        'italic',
                                                        'underline',
                                                        'strike',
                                                        'link',
                                                        'blockquote',
                                                        'codeBlock',
                                                        'heading',
                                                        'subheading',
                                                        'bulletList',
                                                        'orderedList',
                                                        'redo',
                                                        'undo',
                                                    ]),
                                            ])
                                            ->columnSpanFull(),

                                        Card::make()
                                            ->schema([
                                                RichEditor::make('recommendation')
                                                    ->label('Rekomendasi')
                                                    ->required()
                                                    ->toolbarButtons([
                                                        'bold',
                                                        'italic',
                                                        'underline',
                                                        'strike',
                                                        'link',
                                                        'blockquote',
                                                        'codeBlock',
                                                        'heading',
                                                        'subheading',
                                                        'bulletList',
                                                        'orderedList',
                                                        'redo',
                                                        'undo',
                                                    ]),
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')->searchable(),
                TextColumn::make('customer.name')->label('Nama')->limit(50)->sortable(),
                TextColumn::make('customer.age')
                    ->label('Usia')
                    ->formatStateUsing(fn($state) => $state !== null ? $state . ' tahun' : '- tahun')
                    ->limit(50),
                TextColumn::make('service.name')->label('Layanan')->limit(50)->sortable(),
                TextColumn::make('schedule_info')
                    ->label('Jadwal')
                    ->getStateUsing(function ($record) {
                        if ($record->schedule?->available_day && $record->schedule?->start_time && $record->schedule?->end_time) {
                            return "{$record->schedule->available_day} ({$record->schedule->start_time} - {$record->schedule->end_time})";
                        }

                        return '-';
                    })
                    ->limit(50),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'booked' => 'info',
                        'in_session' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'booked' => 'BOOKED',
                        'in_session' => 'IN SESSION',
                        'completed' => 'COMPLETED',
                        'cancelled' => 'CANCELLED',
                        default => strtoupper($state),
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'booked' => 'heroicon-o-calendar',
                        'in_session' => 'heroicon-o-clock',
                        'completed' => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('markAsPresent')
                    ->label('Hadir')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn($record) =>
                        $record->status === 'booked' && auth()->user()->can('attendance-booking')
                    )
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update(['status' => 'in_session'])),

                Tables\Actions\Action::make('markAsCancelled')
                    ->label('Batal')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(
                        fn($record) =>
                        $record->status === 'booked' && auth()->user()->can('attendance-booking')
                    )
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update(['status' => 'cancelled'])),
                Tables\Actions\EditAction::make()->visible(fn($record) => $record->status === 'in_session' || $record->status === 'completed'),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
