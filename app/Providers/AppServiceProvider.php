<?php

namespace App\Providers;

use App\View\Composers\AdminLayoutComposer;
use App\View\Composers\PublicLayoutComposer;
use Illuminate\Support\Facades\View;
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
        // Share pendingOrderCount with admin layout views
        View::composer([
            'layouts.app',
            'layouts.partials.sidebar'
        ], AdminLayoutComposer::class);

        // Share settings with public layout views
        View::composer([
            'layouts.public',
            'layouts.partials.public.header',
            'layouts.partials.public.footer',
            'layouts.partials.public.mobile-nav',
            'home'
        ], PublicLayoutComposer::class);
    }
}
