@props(['image', 'name', 'specialty', 'stats', 'badge', 'badgeText', 'last' => false])

<div class="list-item {{ $last ? 'border-0 pb-0' : '' }}">
    <img src="{{ $image }}" alt="{{ $name }}" class="list-item-image">
    <div class="flex-1 min-w-0">
        <h4 class="text-foreground text-sm font-bold mb-1 truncate">{{ $name }}</h4>
        <p class="text-gray-500 text-xs mb-2 line-clamp-2">{{ $specialty }}</p>
        <div class="flex items-center justify-between">
            <span class="text-gray-400 text-xs">{{ $stats }}</span>
            <span class="badge badge-{{ $badge }}">{{ $badgeText }}</span>
        </div>
    </div>
</div>
