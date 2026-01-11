<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;

class TherapistUpcomingBookings extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    public static function canView(): bool
    {
        return auth()->user()->hasRole('therapist');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('user_profile_id', auth()->user()->profile?->id)
                    ->whereIn('status', ['booked', 'in_session'])
            )
            ->heading('Next Customer Within 7 Days')
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Service'),
                TextColumn::make('schedule_info')
                    ->label('Date & Time')
                    ->getStateUsing(function (Booking $record) {
                        if (!$record->schedule) return '-';
                        
                        $day = ucfirst($record->schedule->available_day);
                        $startTime = $record->schedule->start_time;
                        $endTime = $record->schedule->end_time;
                        
                        // Calculate next date logic
                        try {
                             // Use 'this day' if today matches and time is future, else 'next day'
                             // But simplest is generic "next day" for visualization
                             $date = Carbon::parse("next $day");
                             $dateStr = $date->format('d M Y');
                        } catch (\Exception $e) {
                             $dateStr = $day;
                        }

                        return "$day, $dateStr ($startTime - $endTime)";
                    }),
            ]);
    }
}
