<?php

namespace App\View\Composers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\View\View;

class AdminLayoutComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('pendingOrderCount', Order::where('status', 'pending')->count());
        $view->with('settings', Setting::getAllAsArray());
    }
}
