<?php

namespace App\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

class PublicLayoutComposer
{
    /**
     * Bind settings data to public views.
     */
    public function compose(View $view): void
    {
        $view->with('settings', Setting::getAllAsArray());
    }
}
