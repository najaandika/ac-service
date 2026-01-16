@props([
    'action',
    'placeholder' => 'Cari...',
    'statusOptions' => [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif'
    ]
])

<x-cards.card>
    <form action="{{ $action }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label for="search" class="sr-only">Cari</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ $placeholder }}" class="form-input">
        </div>
        <div class="w-full md:w-40">
            <label for="status" class="sr-only">Filter Status</label>
            <select name="status" id="status" class="form-select w-full">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="search" class="w-4 h-4"></i>
                Cari
            </button>
            <a href="{{ $action }}" class="btn btn-outline">Reset</a>
        </div>
    </form>
</x-cards.card>
