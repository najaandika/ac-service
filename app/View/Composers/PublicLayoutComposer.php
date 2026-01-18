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
            'testimoni' => request()->is('testimoni') || request()->is('testimoni/*'),
            'gallery' => request()->is('gallery'),
            'faq' => request()->is('faq'),
            'track' => request()->is('track'),
            'order' => request()->is('order'),
        ]);
    }
}

