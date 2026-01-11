<?php

namespace App\Filament\Resources\TherapistScheduleResource\Widgets;

use App\Models\Booking;
use Filament\Widgets\Widget;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class TherapistCalendarWidget extends Widget
{
    protected static string $view = 'filament.widgets.therapist-calendar-widget';
    protected int | string | array $columnSpan = 'full';

    public $currentYear;
    public $currentMonth;

    public function mount()
    {
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasRole('therapist');
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
    }

    public function prevMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentYear = $date->year;
        $this->currentMonth = $date->month;
    }

    protected function getViewData(): array
    {
        return [
            'days' => $this->getDays(),
            'monthName' => Carbon::create($this->currentYear, $this->currentMonth, 1)->format('F Y'),
        ];
    }

    public function getDays()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $startOfWeek = $startOfMonth->copy()->startOfWeek(); 
        $endOfWeek = $endOfMonth->copy()->endOfWeek();

        $bookings = Booking::query()
            ->where('user_profile_id', auth()->user()->profile?->id)
            ->where('booking_code', 'like', 'HG-' . $this->currentYear . str_pad($this->currentMonth, 2, '0', STR_PAD_LEFT) . '%')
            ->get();
            
        $events = [];
        foreach ($bookings as $booking) {
            if (preg_match('/HG-(\d{8})-/', $booking->booking_code, $matches)) {
                $dateCode = $matches[1];
                try {
                    $date = Carbon::createFromFormat('Ymd', $dateCode)->format('Y-m-d');
                    $events[$date][] = $booking;
                } catch (\Exception $e) {
                    // ignore
                }
            }
        }

        $days = [];
        $current = $startOfWeek->copy();
        
        while ($current->lte($endOfWeek)) {
            $dateStr = $current->format('Y-m-d');
            $dayEvents = $events[$dateStr] ?? [];
            
            usort($dayEvents, fn($a, $b) => strcmp($a->start_time, $b->start_time));

            $days[] = [
                'date' => $current->copy(),
                'dayNumber' => $current->day,
                'isCurrentMonth' => $current->month == $this->currentMonth,
                'isToday' => $current->isToday(),
                'events' => $dayEvents,
            ];
            
            $current->addDay();
        }

        return $days;
    }
}
