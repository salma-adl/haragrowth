<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class BookingChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengunjung 7 Hari Terakhir';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return !auth()->user()->hasRole('therapist');
    }

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        foreach (range(6, 0) as $i) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('D, d M'); // Contoh: Mon, 05 Aug
            $data[] = Booking::whereDate('created_at', $date)->count(); // atau sesuaikan field waktu
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengunjung',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
