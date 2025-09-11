<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OwenIt\Auditing\Models\Audit;
use App\Models\Usuario;

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
    public function boot() {}
}
