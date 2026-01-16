@extends('layouts.app')

@section('title', 'Kelola Promo - Admin')
@section('page-title', 'Promo')

@section('content')
<div class="space-y-6" x-data="deleteModal">
    <!-- Page Header -->
    <x-page-header title="Kelola Promo" subtitle="Kelola kode promo dan diskon">
        <x-slot:actions>
            <a href="{{ route('admin.promos.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Promo
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- Filters -->
    <x-forms.search-filter 
        action="{{ route('admin.promos.index') }}"
        placeholder="Cari kode atau nama promo..."
        :statusOptions="[
            'active' => 'Aktif',
            'expired' => 'Expired',
            'inactive' => 'Nonaktif'
        ]"
    />

    <!-- Promos List -->
    <x-cards.card>
        @if($promos->count() > 0)
        
        <!-- Mobile: Card Layout -->
        <div class="space-y-4 md:hidden">
            @foreach($promos as $promo)
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono font-bold text-primary text-lg">{{ $promo->code }}</span>
                            <span class="badge badge-{{ $promo->status_color }}">{{ $promo->status_label }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $promo->name }}</p>
                    </div>
                    <x-toggle-switch :active="$promo->is_active" :route="route('admin.promos.toggle', $promo)" />
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                    <span class="flex items-center gap-1">
                        <i data-lucide="{{ $promo->type === 'percentage' ? 'percent' : 'banknote' }}" class="w-4 h-4"></i>
                        {{ $promo->formatted_value }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        {{ $promo->usage_count }}/{{ $promo->usage_limit ?? '∞' }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        @if($promo->end_date)
                            Berlaku s/d {{ $promo->end_date->format('d M Y') }}
                        @else
                            Tidak ada batas waktu
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-sm btn-outline">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        @if($promo->usage_count === 0)
                        <button type="button" @click="openDeleteModal('{{ route('admin.promos.destroy', $promo) }}', '{{ $promo->code }}')" class="btn btn-sm btn-error">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop: Table Layout -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-3 font-semibold text-gray-600">Kode / Nama</th>
                        <th class="py-3 font-semibold text-gray-600">Diskon</th>
                        <th class="py-3 font-semibold text-gray-600">Batasan</th>
                        <th class="py-3 font-semibold text-gray-600">Terpakai</th>
                        <th class="py-3 font-semibold text-gray-600">Periode</th>
                        <th class="py-3 font-semibold text-gray-600">Status</th>
                        <th class="py-3 font-semibold text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($promos as $promo)
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="py-4">
                            <div class="font-mono font-bold text-primary">{{ $promo->code }}</div>
                            <div class="text-sm text-gray-600">{{ $promo->name }}</div>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="{{ $promo->type === 'percentage' ? 'percent' : 'banknote' }}" class="w-4 h-4 text-gray-400"></i>
                                <span class="font-medium">{{ $promo->formatted_value }}</span>
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-sm text-gray-600 space-y-1">
                                @if($promo->min_order > 0)
                                <div title="Min. Order">Min: Rp {{ number_format($promo->min_order, 0, ',', '.') }}</div>
                                @endif
                                @if($promo->service)
                                <div class="flex items-center gap-1 text-xs bg-gray-100 w-fit px-1.5 py-0.5 rounded">
                                    <i data-lucide="wrench" class="w-3 h-3"></i>
                                    {{ Str::limit($promo->service->name, 15) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="flex items-start gap-1">
                                <span class="font-medium">{{ $promo->usage_count }}</span>
                                <span class="text-gray-400">/ {{ $promo->usage_limit ?? '∞' }}</span>
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-sm">
                                @if($promo->is_expired)
                                    <span class="text-red-500 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3 h-3"></i> Expired
                                    </span>
                                @elseif($promo->start_date && $promo->start_date->isFuture())
                                    <span class="text-amber-500">Belum mulai</span>
                                @else
                                    <span class="text-green-600">Berjalan</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($promo->start_date)
                                    {{ $promo->start_date->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                                <span class="text-gray-400">s/d</span>
                                @if($promo->end_date)
                                    {{ $promo->end_date->format('d/m/Y') }}
                                @else
                                    ∞
                                @endif
                            </div>
                        </td>
                        <td class="py-4">
                            <x-toggle-switch :active="$promo->is_active" :route="route('admin.promos.toggle', $promo)" />
                        </td>
                        <td class="py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-sm btn-outline">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                @if($promo->usage_count === 0)
                                <button type="button" @click="openDeleteModal('{{ route('admin.promos.destroy', $promo) }}', '{{ $promo->code }}')" class="btn btn-sm btn-error">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                @else
                                <button type="button" class="btn btn-sm bg-gray-200 text-gray-400 cursor-not-allowed" disabled title="Tidak bisa dihapus karena sudah pernah digunakan">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($promos->hasPages())
        <div class="mt-6 pt-4 border-t">
            {{ $promos->links() }}
        </div>
        @endif

        @else
        <x-empty-state 
            icon="ticket-percent" 
            title="Belum ada promo" 
            description="Tambahkan kode promo untuk memberikan diskon kepada pelanggan."
        >
            <a href="{{ route('admin.promos.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Promo
            </a>
        </x-empty-state>
        @endif
    </x-cards.card>

    <!-- Delete Confirmation Modal -->
    <x-delete-modal title="Hapus Promo" itemLabel="promo" />
</div>
@endsection
