<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class FeatureCard extends Component
{
    public string $icon;
    public string $color;
    public string $title;
    public string $description;
    public string $bgClass;
    public string $iconColor;

    public function __construct(
        string $icon,
        string $title,
        string $description,
        string $color = 'teal'
    ) {
        $this->icon = $icon;
        $this->color = $color;
        $this->title = $title;
        $this->description = $description;
        
        $colorClasses = [
            'lime' => 'bg-accent-lime',
            'teal' => 'bg-accent-teal',
            'peach' => 'bg-accent-peach',
            'primary' => 'bg-primary/20',
        ];
        
        $this->bgClass = $colorClasses[$color] ?? 'bg-accent-teal';
        $this->iconColor = $color === 'primary' ? 'text-primary' : 'text-foreground';
    }

    public function render()
    {
        return view('components.home.feature-card');
    }
}
