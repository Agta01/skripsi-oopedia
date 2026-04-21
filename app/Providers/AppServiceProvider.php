<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Material;

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
        if (config('app.env') !== 'local' || !in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1'])) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        \Illuminate\Pagination\Paginator::useBootstrapFive();
        
        View::composer('components.navbars.sidebar', function ($view) {
            $view->with('materials', Material::orderBy('created_at', 'asc')->get());
        });
    }
}
