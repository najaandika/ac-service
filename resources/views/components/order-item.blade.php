@props(['icon', 'iconBg', 'title', 'customer', 'price', 'status', 'statusText', 'last' => false])

<div class="list-item {{ $last ? 'border-0 pb-0' : '' }}">
    <div class="list-item-icon {{ $iconBg }}">
        <i data-lucide="{{ $icon }}" class="w-8 h-8 text-foreground"></i>
    </div>
    <div class="flex-1 min-w-0">
        <h4 class="text-foreground text-sm font-bold mb-1 truncate">{{ $title }}</h4>
        <p class="text-gray-500 text-xs mb-2 line-clamp-2">{{ $customer }}</p>
        <div class="flex items-center justify-between">
            <span class="text-gray-400 text-xs">{{ $price }}</span>
            <span class="badge badge-{{ $status }}">{{ $statusText }}</span>
        </div>
    </div>
</div>
