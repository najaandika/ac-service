<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public string $type;
    public string $badgeClass;

    public function __construct(string $type = 'success')
    {
        $this->type = $type;
        
        $classes = [
            'success' => 'badge-success',
            'warning' => 'badge-warning',
            'error' => 'badge-error',
            'info' => 'badge-info',
            'alert' => 'badge-alert',
        ];
        
        $this->badgeClass = 'badge ' . ($classes[$type] ?? 'badge-success');
    }

    public function render()
    {
        return view('components.badge');
    }
}
