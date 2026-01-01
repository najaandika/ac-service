@props([
    'title' => null,
    'action' => null,
    'actionText' => 'Lihat Semua'
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title || $action || isset($header))
        <div class="flex items-center justify-between mb-4 px-3">
            @if(isset($header))
                {{ $header }}
            @elseif($title)
                <h3 class="text-foreground text-lg font-bold">{{ $title }}</h3>
            @else
                <div></div>
            @endif
            @if($action)
                <a href="{{ $action }}" class="text-sm text-primary hover:underline">{{ $actionText }}</a>
            @endif
        </div>
    @endif
    
    <div class="card-inner">
        {{ $slot }}
    </div>
</div>
