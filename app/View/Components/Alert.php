<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;
    public ?string $title;
    public bool $dismissible;
    public string $bgClass;
    public string $borderClass;
    public string $icon;
    public string $iconColor;

    public function __construct(
        string $type = 'info',
        ?string $title = null,
        bool $dismissible = true
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->dismissible = $dismissible;
        
        $config = [
            'success' => ['bg' => 'bg-success-light', 'border' => 'border-success', 'icon' => 'check-circle', 'iconColor' => 'text-success-dark'],
            'warning' => ['bg' => 'bg-warning-light', 'border' => 'border-warning', 'icon' => 'alert-triangle', 'iconColor' => 'text-warning-dark'],
            'error' => ['bg' => 'bg-error-light', 'border' => 'border-error', 'icon' => 'alert-circle', 'iconColor' => 'text-error-dark'],
            'info' => ['bg' => 'bg-info-light', 'border' => 'border-info', 'icon' => 'info', 'iconColor' => 'text-info-dark'],
        ];
        
        $c = $config[$type] ?? $config['info'];
        $this->bgClass = $c['bg'];
        $this->borderClass = $c['border'];
        $this->icon = $c['icon'];
        $this->iconColor = $c['iconColor'];
    }

    public function render()
    {
        return view('components.alert');
    }
}
