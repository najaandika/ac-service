@extends('layouts.app')

@section('title', 'Data Pelanggan - Admin')
@section('page-title', 'Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Data Pelanggan</h1>
            <p class="text-gray-600">Lihat data pelanggan dari order yang masuk</p>
        </div>
    </x-cards.card>

    <!-- Filters -->
    <x-cards.card>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari Pelanggan</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau email..." class="form-input">
            </div>
            @if($cities->count() > 0)
            <div class="w-full md:w-40">
                <label for="city" class="sr-only">Filter Kota</label>
                <select name="city" id="city" class="form-select w-full">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </x-cards.card>

    <!-- Customers List -->
    <x-cards.card>
        @if($customers->count() > 0)
        
        <!-- Mobile: Card Layout -->
        <div class="space-y-4 md:hidden">
            @foreach($customers as $customer)
            <a href="{{ route('admin.customers.show', $customer) }}" class="block bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-foreground">{{ $customer->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                        @if($customer->email)
                        <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                        @endif
                    </div>
                    <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full font-medium">
                        {{ $customer->orders_count }} order
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $customer->address }}</p>
                @if($customer->city)
                <span class="text-xs text-gray-500">{{ $customer->city }}</span>
                @endif
            </a>
            @endforeach
        </div>

        <!-- Desktop: Table Layout -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 text-sm border-b border-gray-200">
                        <th class="pb-3 font-medium">Pelanggan</th>
                        <th class="pb-3 font-medium">Telepon</th>
                        <th class="pb-3 font-medium">Alamat</th>
                        <th class="pb-3 font-medium">Kota</th>
                        <th class="pb-3 font-medium text-center">Orders</th>
                        <th class="pb-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4">
                            <div>
                                <p class="font-medium text-foreground">{{ $customer->name }}</p>
                                @if($customer->email)
                                <p class="text-xs text-gray-500">{{ $customer->email }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 text-gray-600">{{ $customer->phone }}</td>
                        <td class="py-4 text-gray-600 max-w-xs">
                            <p class="truncate">{{ $customer->address }}</p>
                        </td>
                        <td class="py-4 text-gray-600">{{ $customer->city ?? '-' }}</td>
                        <td class="py-4 text-center">
                            <span class="font-bold text-foreground">{{ $customer->orders_count }}</span>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    <span class="hidden lg:inline ml-1">Detail</span>
                                </a>
                                @if($customer->orders_count === 0)
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Yakin hapus pelanggan {{ $customer->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline text-red-500 border-red-300 hover:bg-red-50" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
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
            {{ $customers->withQueryString()->links() }}
        </div>

        @else
        <x-empty-state 
            icon="users" 
            title="Belum ada pelanggan" 
            description="Data pelanggan akan muncul setelah ada order masuk."
        />
        @endif
    </x-cards.card>
</div>
@endsection
