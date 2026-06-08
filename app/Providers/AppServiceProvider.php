<?php

namespace App\Providers;

use App\Models\Certificate;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Plan;
use App\Models\User;
use App\Policies\CertificatePolicy;
use App\Policies\OrderPolicy;
use App\Policies\PatientPolicy;
use App\Policies\PlanPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Administrador') ? true : null;
        });
        Gate::policy(Plan::class, PlanPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(Certificate::class, CertificatePolicy::class);
    }
}
