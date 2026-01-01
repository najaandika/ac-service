<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-2"
    {{ $attributes->merge(['class' => "p-4 rounded-[var(--radius-card)] border-l-4 $bgClass $borderClass"]) }}
>
    <div class="flex items-start gap-3">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $iconColor }} flex-shrink-0 mt-0.5"></i>
        <div class="flex-1">
            @if($title)
                <h4 class="text-foreground text-sm font-medium">{{ $title }}</h4>
            @endif
            <p class="text-gray-500 text-sm {{ $title ? 'mt-1' : '' }}">{{ $slot }}</p>
        </div>
        @if($dismissible)
            <button @click="show = false" class="p-1 hover:bg-white/50 rounded cursor-pointer transition-all">
                <i data-lucide="x" class="w-4 h-4 text-gray-400"></i>
            </button>
        @endif
    </div>
</div>
