@extends('layouts.app')

@section('title', 'Kelola Teknisi - Admin')
@section('page-title', 'Teknisi')

@section('content')
<div class="space-y-6" x-data="deleteModal">
    <!-- Page Header -->
    <x-page-header title="Kelola Teknisi" subtitle="Kelola data teknisi service AC">
        <x-slot:actions>
            <a href="{{ route('admin.technicians.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Teknisi
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- Alerts -->
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    <!-- Filters -->
    <x-forms.search-filter
        action="{{ route('admin.technicians.index') }}"
        placeholder="Cari nama atau telepon..."
    >
        <div class="w-full md:w-40">
            <label for="status" class="sr-only">Filter Status</label>
            <select name="status" id="status" class="form-select w-full">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </x-forms.search-filter>

    <!-- Technicians List -->
    <x-cards.card>
        @if($technicians->count() > 0)

        <!-- Mobile: Card Layout -->
        <div class="space-y-4 md:hidden">
            @foreach($technicians as $technician)
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start gap-4 mb-3">
                    <img src="{{ $technician->photo_url }}" alt="{{ $technician->name }}" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-foreground truncate">{{ $technician->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $technician->phone }}</p>
                        <p class="text-xs text-gray-400">{{ $technician->specialty ?? 'Semua jenis AC' }}</p>
                    </div>
                    <!-- Toggle Switch -->
                    <x-toggle-switch :active="$technician->is_active" :route="route('admin.technicians.toggle', $technician)" />
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                            {{ number_format($technician->rating, 1) }}
                        </span>
                        <span>{{ $technician->orders_count }} order</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.technicians.edit', $technician) }}" class="btn btn-sm btn-outline">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        @if($technician->orders_count === 0)
                        <button type="button"
                            @click="openDeleteModal('{{ route('admin.technicians.destroy', $technician) }}', '{{ $technician->name }}')"
                            class="btn btn-sm btn-error">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        @else
                        <button type="button" disabled class="btn btn-sm bg-gray-200 text-gray-400 cursor-not-allowed" title="Tidak bisa dihapus karena memiliki order">
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
                        <th class="py-3 pl-4 font-semibold text-gray-600">Teknisi</th>
                        <th class="py-3 font-semibold text-gray-600">Telepon</th>
                        <th class="py-3 font-semibold text-gray-600">Spesialisasi</th>
                        <th class="py-3 font-semibold text-gray-600 text-center">Rating</th>
                        <th class="py-3 font-semibold text-gray-600 text-center">Orders</th>
                        <th class="py-3 font-semibold text-gray-600">Status</th>
                        <th class="py-3 pr-4 font-semibold text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($technicians as $technician)
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="py-4 pl-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $technician->photo_url }}" alt="{{ $technician->name }}" class="w-10 h-10 rounded-full object-cover">
                                <span class="font-medium text-foreground">{{ $technician->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-gray-600">{{ $technician->phone }}</td>
                        <td class="py-4 text-gray-600">{{ $technician->specialty ?? 'Semua jenis AC' }}</td>
                        <td class="py-4 text-center">
                            <span class="inline-flex items-center gap-1 justify-center">
                                <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                                {{ number_format($technician->rating, 1) }}
                            </span>
                        </td>
                        <td class="py-4 text-center">
                            <span class="font-bold text-foreground">{{ $technician->orders_count }}</span>
                        </td>
                        <td class="py-4">
                            <x-toggle-switch :active="$technician->is_active" :route="route('admin.technicians.toggle', $technician)" />
                        </td>
                        <td class="py-4 pr-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.technicians.edit', $technician) }}" class="btn btn-sm btn-outline" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                @if($technician->orders_count === 0)
                                <button type="button"
                                    @click="openDeleteModal('{{ route('admin.technicians.destroy', $technician) }}', '{{ $technician->name }}')"
                                    class="btn btn-sm btn-error" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                @else
                                <button type="button" disabled class="btn btn-sm bg-gray-200 text-gray-400 cursor-not-allowed" title="Tidak bisa dihapus karena memiliki order">
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
        @if($technicians->hasPages())
        <div class="mt-6 pt-4 border-t border-gray-100">
            {{ $technicians->withQueryString()->links() }}
        </div>
        @endif

        @else
        <x-empty-state
            icon="hard-hat"
            title="Belum ada teknisi"
            description="Mulai tambahkan teknisi untuk mengelola tim service AC Anda."
        >
            <a href="{{ route('admin.technicians.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Teknisi
            </a>
        </x-empty-state>
        @endif
    </x-cards.card>

    <!-- Delete Confirmation Modal -->
    <x-delete-modal title="Hapus Teknisi" itemLabel="teknisi" />
</div>
@endsection
