<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TherapistStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('therapist')) {
            return [];
        }

        $userProfileId = $user->profile?->id;
        
        if (!$userProfileId) {
             return [];
        }

        $handledCount = Booking::where('user_profile_id', $userProfileId)
            ->where('status', 'completed')
            ->distinct('customer_id')
            ->count('customer_id');

        $pendingCount = Booking::where('user_profile_id', $userProfileId)
            ->whereIn('status', ['booked', 'in_session'])
            ->distinct('customer_id')
            ->count('customer_id');

        return [
            Stat::make('Patients Already Handled', $handledCount)
                ->description('Unique patients completed')
                ->color('success'),
            Stat::make('Pending Patients', $pendingCount)
                ->description('Unique patients upcoming')
                ->color('warning'),
        ];
    }
    
    public static function canView(): bool
    {
        // Only show this widget if the user is a therapist
        // If an admin is also a therapist, they will see it too, which is fine.
        // If we want ONLY pure therapists, we can check roles.
        // "Therapist Dashboard" implies when they are acting as therapist.
        return auth()->user()->hasRole('therapist');
    }
}
