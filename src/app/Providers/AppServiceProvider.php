<?php

namespace App\Providers;

use App\Models\TravelOrder;
use App\Models\User;
use App\Policies\TravelOrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->is_admin) {
                return true;
            }
        });
        Gate::policy(TravelOrder::class, TravelOrderPolicy::class);
    }
}
