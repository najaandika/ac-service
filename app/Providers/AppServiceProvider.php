<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Setting;
use App\View\Composers\PublicLayoutComposer;
use Carbon\Carbon;
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
        // Set Carbon locale for Indonesian date format
        Carbon::setLocale('id');
        
        // Share pendingOrderCount globally with all admin views
        View::composer('layouts.app', function ($view) {
            $view->with('pendingOrderCount', Order::where('status', 'pending')->count());
            $view->with('settings', Setting::getAllAsArray());
        });

        // Also share to sidebar partial when included
        View::composer('layouts.partials.sidebar', function ($view) {
            if (!isset($view->getData()['pendingOrderCount'])) {
                $view->with('pendingOrderCount', Order::where('status', 'pending')->count());
            }
            if (!isset($view->getData()['settings'])) {
                $view->with('settings', Setting::getAllAsArray());
            }
        });

        // Share settings with public layout views
        View::composer([
            'layouts.public',
            'layouts.partials.public.header',
            'layouts.partials.public.footer',
            'layouts.partials.public.mobile-nav',
            'home',
            'order.track',
            'order.success'
        ], PublicLayoutComposer::class);
    }
}

