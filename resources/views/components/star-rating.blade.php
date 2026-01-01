@props(['rating', 'size' => 'md', 'showValue' => false])

@php
$sizes = [
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="flex items-center gap-1">
    @for($i = 1; $i <= 5; $i++)
    <i data-lucide="star" class="{{ $sizeClass }} {{ $i <= $rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}"></i>
    @endfor
    @if($showValue)
    <span class="ml-1 font-semibold text-foreground">{{ $rating }}/5</span>
    @endif
</div>
