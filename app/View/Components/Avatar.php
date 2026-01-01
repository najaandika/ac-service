<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public string $src;
    public string $alt;
    public string $size;
    public string $rounded;
    public string $classes;

    public function __construct(
        string $src,
        string $alt = '',
        string $size = 'md',
        string $rounded = 'full'
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->size = $size;
        $this->rounded = $rounded;
        
        $sizeClasses = [
            'sm' => 'w-8 h-8',
            'md' => 'w-12 h-12',
            'lg' => 'w-16 h-16',
            'xl' => 'w-20 h-20',
        ];
        
        $roundedClasses = [
            'full' => 'rounded-full',
            'lg' => 'rounded-lg',
            'md' => 'rounded-md',
        ];
        
        $this->classes = ($sizeClasses[$size] ?? 'w-12 h-12') . ' ' . 
                         ($roundedClasses[$rounded] ?? 'rounded-full') . 
                         ' object-cover flex-shrink-0';
    }

    public function render()
    {
        return view('components.avatar');
    }
}
