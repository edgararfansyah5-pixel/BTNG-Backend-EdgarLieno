<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paksa HTTPS di production (Clever Cloud)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Scramble: izinkan siapa pun (termasuk guest) membuka /docs/api
        Gate::define('viewApiDocs', function (?User $user = null) {
            return true;
        });
    }
}
