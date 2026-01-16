@props([
    'cancelUrl',
    'cancelText' => 'Batal',
    'submitText' => 'Simpan',
    'submitIcon' => 'save',
])

<div class="flex justify-end gap-4">
    <a href="{{ $cancelUrl }}" class="btn btn-outline">{{ $cancelText }}</a>
    <button type="submit" class="btn btn-primary">
        @if($submitIcon)
        <i data-lucide="{{ $submitIcon }}" class="w-4 h-4"></i>
        @endif
        {{ $submitText }}
    </button>
</div>
