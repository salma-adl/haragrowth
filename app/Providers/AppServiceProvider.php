<?php

namespace App\Providers;

use App\Models\TherapistSchedule;
use App\Models\UserProfile;
use App\Policies\TherapistSchedulePolicy;
use App\Policies\UserProfilePolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

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
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');

            // Force Resend transport for SMTP if Resend API key is present
            // This fixes queued jobs that are stuck trying to use 'smtp' mailer on blocked ports
            if (Config::get('services.resend.key')) {
                Config::set('mail.mailers.smtp.transport', 'resend');
                Config::set('mail.default', 'resend');
            }
        }

        Gate::policy(UserProfile::class, UserProfilePolicy::class);
        Gate::policy(TherapistSchedule::class, TherapistSchedulePolicy::class);

        Gate::before(function ($user, $ability) {
        return $user->is_superuser ? true : null;
    });
    }
}
