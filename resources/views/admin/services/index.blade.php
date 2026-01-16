@extends('layouts.app')

@section('title', 'Kelola Layanan - Admin')
@section('page-title', 'Layanan')

@section('content')
<div class="space-y-6" x-data="deleteModal">
    <!-- Page Header -->
    <x-page-header title="Kelola Layanan" subtitle="Kelola layanan service AC">
        <x-slot:actions>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Layanan
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- Filters -->
    <x-forms.search-filter 
        action="{{ route('admin.services.index') }}"
        placeholder="Cari layanan..."
    />

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
                    <x-toggle-switch :active="$service->is_active" :route="route('admin.services.toggle', $service)" />
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
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-3 pl-4 font-semibold text-gray-600 w-16">Icon</th>
                        <th class="py-3 font-semibold text-gray-600">Nama Layanan</th>
                        <th class="py-3 font-semibold text-gray-600">Durasi</th>
                        <th class="py-3 font-semibold text-gray-600">Harga Mulai</th>
                        <th class="py-3 font-semibold text-gray-600">Order</th>
                        <th class="py-3 font-semibold text-gray-600">Status</th>
                        <th class="py-3 pr-4 font-semibold text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($services as $service)
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="py-4 pl-4">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-200">
                                <i data-lucide="{{ $service->icon }}" class="w-5 h-5"></i>
                            </div>
                        </td>
                        <td class="py-4">
                            <h3 class="font-medium text-foreground">{{ $service->name }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-1">{{ $service->description }}</p>
                        </td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                {{ $service->duration_minutes }} menit
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="font-semibold text-green-600">{{ $service->formatted_starting_price }}</span>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                <i data-lucide="shopping-bag" class="w-4 h-4 text-gray-400"></i>
                                {{ $service->orders_count }}
                            </div>
                        </td>
                        <td class="py-4">
                            <x-toggle-switch :active="$service->is_active" :route="route('admin.services.toggle', $service)" />
                        </td>
                        <td class="py-4 pr-4">
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
    <x-delete-modal title="Hapus Layanan" itemLabel="layanan" />
</div>
@endsection
