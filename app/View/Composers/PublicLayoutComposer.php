<?php

namespace App\View\Composers;

use App\Models\Service;
use App\Models\Setting;
use Illuminate\View\View;

class PublicLayoutComposer
{
    /**
     * Bind settings and services data to public views.
     */
    public function compose(View $view): void
    {
        $view->with('settings', Setting::getAllAsArray());
        $view->with('footerServices', Service::active()->orderBy('sort_order')->limit(5)->get());
        
        // Navigation active states (only for dedicated pages, not anchors)
        $view->with('navActive', [
            'home' => false, // Can't detect hash anchors server-side, so no highlight on homepage
            'layanan' => request()->is('layanan/*'),
            'testimoni' => request()->routeIs('testimoni.*'),
            'track' => request()->routeIs('order.track'),
            'order' => request()->routeIs('order.create'),
        ]);
    }
}

