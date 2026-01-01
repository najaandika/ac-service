@props([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melanjutkan?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'confirmVariant' => 'danger', // primary, danger
    'action' => null,
    'method' => 'POST'
])

<x-modal :id="$id" size="sm">
    <div class="text-center">
        <div class="w-16 h-16 {{ $confirmVariant === 'danger' ? 'bg-error-light' : 'bg-warning-light' }} rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="alert-triangle" class="w-8 h-8 {{ $confirmVariant === 'danger' ? 'text-error-dark' : 'text-warning-dark' }}"></i>
        </div>
        <h3 class="text-foreground text-xl font-bold mb-2">{{ $title }}</h3>
        <p class="text-gray-500 text-sm mb-6">{{ $message }}</p>
        
        <div class="flex items-center justify-center gap-3">
            <x-button variant="outline" onclick="closeModal('{{ $id }}')">
                {{ $cancelText }}
            </x-button>
            
            @if($action)
                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @if($method !== 'POST')
                        @method($method)
                    @endif
                    <x-button type="submit" :variant="$confirmVariant">
                        {{ $confirmText }}
                    </x-button>
                </form>
            @else
                <x-button :variant="$confirmVariant" onclick="closeModal('{{ $id }}')">
                    {{ $confirmText }}
                </x-button>
            @endif
        </div>
    </div>
</x-modal>
