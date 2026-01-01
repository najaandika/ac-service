@props([
    'icon' => 'inbox',
    'title' => 'Tidak ada data',
    'description' => 'Data yang Anda cari tidak ditemukan.',
    'action' => null,
    'actionText' => 'Tambah Baru'
])

<div class="flex flex-col items-center justify-center py-12 px-4 text-center">
    <div class="w-20 h-20 bg-muted rounded-full flex items-center justify-center mb-4">
        <i data-lucide="{{ $icon }}" class="w-10 h-10 text-gray-400"></i>
    </div>
    <h3 class="text-foreground text-lg font-semibold mb-2">{{ $title }}</h3>
    <p class="text-gray-500 text-sm mb-6 max-w-sm">{{ $description }}</p>
    
    @if($action)
        <x-button variant="primary" icon="plus" href="{{ $action }}">
            {{ $actionText }}
        </x-button>
    @endif
</div>
