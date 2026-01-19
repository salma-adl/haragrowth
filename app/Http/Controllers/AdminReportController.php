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
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $serviceId = $request->input('service_id', 'all');
        if ($serviceId !== 'all') {
            $query->where('service_id', $serviceId);
        }

        $therapistId = $request->input('therapist_id', 'all');
        if ($therapistId !== 'all') {
            $query->where('user_profile_id', $therapistId);
        }
        
        $bookings = $query->get();

        $reportCategory = $request->input('report_category', 'service');
        $reportData = [];

        if ($reportCategory === 'service') {
            $serviceGroups = $bookings->groupBy(function($booking) {
                return $booking->service->name ?? 'Unknown Service';
            });

            foreach ($serviceGroups as $serviceName => $serviceBookings) {
                $reportData[] = [
                    'service' => $serviceName,
                    'booking_count' => $serviceBookings->count(),
                    'client_count' => $serviceBookings->pluck('customer_id')->unique()->count(),
                ];
            }
        } elseif ($reportCategory === 'therapist') {
            $grouped = $bookings->groupBy(function($booking) {
                return $booking->userProfile->name ?? 'Unassigned';
            });

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
        }

        $filename = "laporan_booking_" . date('Ymd') . ".xlsx";
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BookingReportExport($reportData, $reportCategory), $filename);
    }
}
