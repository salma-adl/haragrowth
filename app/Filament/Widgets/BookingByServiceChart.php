<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingByServiceChart extends ChartWidget
{
    protected static ?string $heading = 'Pasien per Layanan (7 Hari Terakhir)';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return !auth()->user()->hasRole('therapist');
    }

    protected function getData(): array
    {
        $startDate = Carbon::today()->subDays(365);
        $endDate = Carbon::today();

        $bookings = Booking::with('service')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($booking) => $booking->service?->name ?? 'Tanpa Layanan');

        $labels = [];
        $data = [];

        foreach ($bookings as $serviceName => $grouped) {
            $labels[] = $serviceName;
            $data[] = $grouped->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pasien',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
        ];
    }
}
