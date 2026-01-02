@extends('layouts.app')

@section('title', 'Kelola Teknisi - Admin')
@section('page-title', 'Teknisi')

@section('content')
<div class="space-y-6" x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '', 
    technicianName: '',
    openDeleteModal(url, name) {
        this.deleteUrl = url;
        this.technicianName = name;
        this.showDeleteModal = true;
    }
}">
    <!-- Page Header -->
    <x-cards.card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-foreground text-2xl font-bold hidden lg:block">Kelola Teknisi</h1>
                <p class="text-gray-600">Kelola data teknisi service AC</p>
            </div>
            <a href="{{ route('admin.technicians.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Teknisi
            </a>
        </div>
    </x-cards.card>

    <!-- Alerts -->
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    <!-- Filters -->
    <x-cards.card>
        <form action="{{ route('admin.technicians.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari Teknisi</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama atau telepon..." class="form-input">
            </div>
            <div class="w-full md:w-40">
                <label for="status" class="sr-only">Filter Status</label>
                <select name="status" id="status" class="form-select w-full">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari
                </button>
                <a href="{{ route('admin.technicians.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </x-cards.card>

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
                    <div x-data="{ isActive: {{ $technician->is_active ? 'true' : 'false' }}, loading: false }">
                        <button 
                            type="button"
                            @click="
                                loading = true;
                                fetch('{{ route('admin.technicians.toggle', $technician) }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    isActive = data.is_active;
                                    loading = false;
                                })
                                .catch(() => { loading = false; });
                            "
                            :class="isActive ? 'toggle-switch-active' : 'toggle-switch-inactive'"
                            class="toggle-switch"
                            :disabled="loading"
                        >
                            <span 
                                :class="isActive ? 'toggle-switch-dot-active' : 'toggle-switch-dot-inactive'"
                                class="toggle-switch-dot"
                            ></span>
                        </button>
                    </div>
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
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 text-sm border-b border-gray-200">
                        <th class="pb-3 font-medium">Teknisi</th>
                        <th class="pb-3 font-medium">Telepon</th>
                        <th class="pb-3 font-medium">Spesialisasi</th>
                        <th class="pb-3 font-medium text-center">Rating</th>
                        <th class="pb-3 font-medium text-center">Orders</th>
                        <th class="pb-3 font-medium text-center">Status</th>
                        <th class="pb-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($technicians as $technician)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $technician->photo_url }}" alt="{{ $technician->name }}" class="w-10 h-10 rounded-full object-cover">
                                <span class="font-medium text-foreground">{{ $technician->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-gray-600">{{ $technician->phone }}</td>
                        <td class="py-4 text-gray-600">{{ $technician->specialty ?? 'Semua jenis AC' }}</td>
                        <td class="py-4 text-center">
                            <span class="flex items-center justify-center gap-1">
                                <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                                {{ number_format($technician->rating, 1) }}
                            </span>
                        </td>
                        <td class="py-4 text-center">
                            <span class="font-bold text-foreground">{{ $technician->orders_count }}</span>
                        </td>
                        <td class="py-4 text-center">
                            <div x-data="{ isActive: {{ $technician->is_active ? 'true' : 'false' }}, loading: false }" class="flex justify-center">
                                <button 
                                    type="button"
                                    @click="
                                        loading = true;
                                        fetch('{{ route('admin.technicians.toggle', $technician) }}', {
                                            method: 'PATCH',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json'
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            isActive = data.is_active;
                                            loading = false;
                                        })
                                        .catch(() => { loading = false; });
                                    "
                                    :class="isActive ? 'toggle-switch-active' : 'toggle-switch-inactive'"
                                    class="toggle-switch"
                                    :disabled="loading"
                                >
                                    <span 
                                        :class="isActive ? 'toggle-switch-dot-active' : 'toggle-switch-dot-inactive'"
                                        class="toggle-switch-dot"
                                    ></span>
                                </button>
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center justify-end gap-2">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $technicians->withQueryString()->links() }}
        </div>

        @else
        <x-empty-state 
            icon="hard-hat" 
            title="Belum ada teknisi" 
            description="Mulai tambahkan teknisi untuk mengelola tim service AC Anda."
            action="{{ route('admin.technicians.create') }}"
            actionText="Tambah Teknisi"
        />
        @endif
    </x-cards.card>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showDeleteModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 py-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Hapus Teknisi</h3>
                            <p class="mt-2 text-gray-600">
                                Apakah Anda yakin ingin menghapus teknisi <strong x-text="technicianName"></strong>? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" @click="showDeleteModal = false" class="btn btn-outline w-full sm:w-auto justify-center">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error w-full justify-center">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Hapus Teknisi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
