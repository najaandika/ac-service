@props([
    'id',
    'title' => null,
    'size' => 'md',
    'closeButton' => true
])

<div 
    id="{{ $id }}" 
    class="modal-overlay fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center p-4"
    onclick="if(event.target === this) closeModal('{{ $id }}')"
>
    <div class="modal-content bg-white rounded-[var(--radius-card)] p-6 w-full {{ $size === 'sm' ? 'max-w-sm' : ($size === 'lg' ? 'max-w-lg' : ($size === 'xl' ? 'max-w-xl' : 'max-w-md')) }} max-h-[90vh] overflow-y-auto">
        @if($title || $closeButton)
            <div class="flex items-center justify-between mb-4">
                @if($title)
                    <h3 class="text-foreground text-xl font-bold">{{ $title }}</h3>
                @else
                    <div></div>
                @endif
                @if($closeButton)
                    <button onclick="closeModal('{{ $id }}')" class="p-2 hover:bg-gray-100 rounded-lg cursor-pointer transition-all">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                @endif
            </div>
        @endif
        
        {{ $slot }}
        
        @isset($footer)
            <div class="mt-6 pt-4 border-t border-border flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
