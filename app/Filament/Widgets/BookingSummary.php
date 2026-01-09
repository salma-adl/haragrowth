<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;


class BookingSummary extends Widget
{
    protected static string $view = 'filament.widgets.booking-summary';

    protected int | string | array $columnSpan = 'full';

    public $totalBookings;
    public $inSessionVisitors;
    public $finishedToday;

    public function mount(): void
    {
        $today = Carbon::today();

        $this->totalBookings = Booking::count();
        $this->inSessionVisitors = Booking::where('status', 'in_session')->count();
        $this->finishedToday = Booking::where('status', 'done')
            ->whereDate('updated_at', $today)
            ->count();
    }
}
