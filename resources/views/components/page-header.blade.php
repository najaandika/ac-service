@props([
    'title',
    'subtitle' => null,
    'actions' => null,
])

<x-cards.card>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">{{ $title }}</h1>
            @if($subtitle)
            <p class="text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
        
        @if($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
        @endif
        
        @if(!$actions && !$slot->isEmpty())
        <div>
            {{ $slot }}
        </div>
        @endif
    </div>
</x-cards.card>
