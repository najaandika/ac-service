<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public string $type;
    public string $variant;
    public string $size;
    public ?string $icon;
    public string $iconPosition;
    public bool $disabled;
    public bool $loading;
    public ?string $href;
    public string $classes;

    public function __construct(
        string $type = 'button',
        string $variant = 'primary',
        string $size = 'md',
        ?string $icon = null,
        string $iconPosition = 'left',
        bool $disabled = false,
        bool $loading = false,
        ?string $href = null
    ) {
        $this->type = $type;
        $this->variant = $variant;
        $this->size = $size;
        $this->icon = $icon;
        $this->iconPosition = $iconPosition;
        $this->disabled = $disabled;
        $this->loading = $loading;
        $this->href = $href;
        
        $baseClasses = 'btn';
        $variantClass = match($variant) {
            'primary' => 'btn-primary',
            'outline' => 'btn-outline',
            'danger' => 'bg-error text-white hover:bg-error-dark',
            'success' => 'bg-success text-white hover:bg-success-dark',
            default => 'btn-primary'
        };
        $sizeClass = match($size) {
            'sm' => 'px-3 py-1.5 text-sm',
            'lg' => 'px-6 py-3 text-lg',
            default => 'px-4 py-2.5'
        };
        $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
        $this->classes = "$baseClasses $variantClass $sizeClass $disabledClass";
    }

    public function render()
    {
        return view('components.button');
    }
}
