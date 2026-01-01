@extends('layouts.app')

@section('title', 'Kelola Layanan - Admin')
@section('page-title', 'Layanan')

@section('content')
<div class="space-y-6" x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '', 
    serviceName: '',
    openDeleteModal(url, name) {
        this.deleteUrl = url;
        this.serviceName = name;
        this.showDeleteModal = true;
    }
}">
    <!-- Page Header -->
    <x-cards.card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-foreground text-2xl font-bold hidden lg:block">Kelola Layanan</h1>
                <p class="text-gray-600">Kelola layanan service AC</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Layanan
            </a>
        </div>
    </x-cards.card>

    <!-- Filters -->
    <x-cards.card>
        <form action="{{ route('admin.services.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="form-input">
            </div>
            <div class="w-full md:w-40">
                <select name="status" class="form-select w-full">
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
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </x-cards.card>

    <!-- Services List -->
    <x-cards.card>
        @if($services->count() > 0)
        
        <!-- Mobile: Card Layout -->
        <div class="space-y-4 md:hidden">
            @foreach($services as $service)
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <i data-lucide="{{ $service->icon }}" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-foreground">{{ $service->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $service->duration_minutes }} menit</p>
                        </div>
                    </div>
                    <!-- Toggle Switch for mobile -->
                    <div x-data="{ isActive: {{ $service->is_active ? 'true' : 'false' }}, loading: false }">
                        <button 
                            type="button"
                            @click="
                                loading = true;
                                fetch('{{ route('admin.services.toggle', $service) }}', {
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
                    <div>
                        <p class="text-xs text-gray-500">Mulai dari</p>
                        <p class="font-semibold text-green-600">{{ $service->formatted_starting_price }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        @if($service->orders_count === 0)
                        <button type="button" @click="openDeleteModal('{{ route('admin.services.destroy', $service) }}', '{{ $service->name }}')" class="btn btn-sm btn-error">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-sm bg-gray-200 text-gray-400 cursor-not-allowed" disabled title="Tidak bisa dihapus karena sudah ada {{ $service->orders_count }} order">
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
                    <tr class="text-left text-sm text-gray-500 border-b">
                        <th class="pb-3 font-medium">Layanan</th>
                        <th class="pb-3 font-medium">Durasi</th>
                        <th class="pb-3 font-medium">Harga Mulai</th>
                        <th class="pb-3 font-medium text-center">Order</th>
                        <th class="pb-3 font-medium text-center">Status</th>
                        <th class="pb-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($services as $service)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                    <i data-lucide="{{ $service->icon }}" class="w-5 h-5 text-primary"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">{{ $service->name }}</p>
                                    <p class="text-sm text-gray-500 line-clamp-1">{{ Str::limit($service->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-gray-600">{{ $service->duration_minutes }} menit</td>
                        <td class="py-4 font-semibold text-green-600">{{ $service->formatted_starting_price }}</td>
                        <td class="py-4 text-center">
                            <span class="font-bold text-foreground">{{ $service->orders_count }}</span>
                        </td>
                        <td class="py-4 text-center">
                            <div x-data="{ isActive: {{ $service->is_active ? 'true' : 'false' }}, loading: false }" class="flex justify-center">
                                <button 
                                    type="button"
                                    @click="
                                        loading = true;
                                        fetch('{{ route('admin.services.toggle', $service) }}', {
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
                                    :title="isActive ? 'Aktif - Klik untuk nonaktifkan' : 'Nonaktif - Klik untuk aktifkan'"
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
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                @if($service->orders_count === 0)
                                <button type="button" @click="openDeleteModal('{{ route('admin.services.destroy', $service) }}', '{{ $service->name }}')" class="btn btn-sm btn-error" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                @else
                                <button type="button" class="btn btn-sm bg-gray-200 text-gray-400 cursor-not-allowed" disabled title="Tidak bisa dihapus karena sudah ada {{ $service->orders_count }} order">
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
        @if($services->hasPages())
        <div class="mt-6 pt-4 border-t border-gray-100">
            {{ $services->withQueryString()->links() }}
        </div>
        @endif

        @else
        <x-empty-state 
            icon="wrench"
            title="Belum ada layanan"
            description="Mulai tambahkan layanan AC untuk ditampilkan"
        >
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Layanan
            </a>
        </x-empty-state>
        @endif
    </x-cards.card>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showDeleteModal = false"></div>

            <!-- Modal Panel -->
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 py-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Hapus Layanan</h3>
                            <p class="mt-2 text-gray-600">
                                Apakah Anda yakin ingin menghapus layanan <strong x-text="serviceName"></strong>? Tindakan ini tidak dapat dibatalkan.
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
                            Hapus Layanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
