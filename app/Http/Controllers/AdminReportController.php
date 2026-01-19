<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function download(Request $request)
    {
        // Security check
        if (!Auth::check() || !(Auth::user()->is_superuser || Auth::user()->hasRole('admin'))) {
            abort(403, 'Unauthorized');
        }

        $startDate = Carbon::parse($request->input('start_date', now()->subMonth()));
        $endDate = Carbon::parse($request->input('end_date', now()));

        $query = Booking::query()
            ->with(['service', 'userProfile', 'customer'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        $serviceId = $request->input('service_id', 'all');
        if ($serviceId !== 'all') {
            $query->where('service_id', $serviceId);
        }

        $therapistId = $request->input('therapist_id', 'all');
        if ($therapistId !== 'all') {
            $query->where('user_profile_id', $therapistId);
        }
        
        $bookings = $query->get();

        $grouped = $bookings->groupBy(function($booking) {
            return $booking->userProfile->name ?? 'Unassigned';
        });

        $reportData = [];
        foreach ($grouped as $therapistName => $therapistBookings) {
            $serviceGroups = $therapistBookings->groupBy(function($booking) {
                return $booking->service->name ?? 'Unknown Service';
            });

            foreach ($serviceGroups as $serviceName => $serviceBookings) {
                $reportData[] = [
                    'therapist' => $therapistName,
                    'service' => $serviceName,
                    'booking_count' => $serviceBookings->count(),
                    'client_count' => $serviceBookings->pluck('customer_id')->unique()->count(),
                ];
            }
        }

        $filename = "laporan_booking_" . date('Ymd') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($reportData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Terapis', 'Layanan', 'Jumlah Booking', 'Jumlah Klien Unik']);

            foreach ($reportData as $row) {
                fputcsv($file, [$row['therapist'], $row['service'], $row['booking_count'], $row['client_count']]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
