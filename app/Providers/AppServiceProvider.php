<?php

namespace App\Providers;

use App\Models\TherapistSchedule;
use App\Models\UserProfile;
use App\Policies\TherapistSchedulePolicy;
use App\Policies\UserProfilePolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(UserProfile::class, UserProfilePolicy::class);
        Gate::policy(TherapistSchedule::class, TherapistSchedulePolicy::class);

        Gate::before(function ($user, $ability) {
        return $user->is_superuser ? true : null;
    });
    }
}
