@extends('layouts.app')

@section('title', 'Kelola Order - Admin')
@section('page-title', 'Daftar Order')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Kelola Order</h1>
            <p class="text-gray-600">Kelola semua order yang masuk</p>
        </div>
    </x-cards.card>

    <!-- Filters -->
    <x-cards.card>
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-input" placeholder="Kode order, nama, HP..." autocomplete="off">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="form-input">
                    <option value="all">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
        
        {{-- Quick Filter: Tomorrow Orders (H-1 Reminder) --}}
        @if($tomorrowCount > 0 || request('tomorrow'))
        <div class="mt-4 pt-4 border-t flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index', ['tomorrow' => 1]) }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium transition-colors {{ request('tomorrow') ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}">
                <i data-lucide="bell" class="w-4 h-4"></i>
                Jadwal Besok
                @if($tomorrowCount > 0)
                <span class="bg-white/20 px-1.5 py-0.5 rounded-full text-xs">{{ $tomorrowCount }}</span>
                @endif
            </a>
        </div>
        @endif
    </x-cards.card>

    <!-- Orders -->
    <x-cards.card>
        @if($orders->count() > 0)
        
        <!-- Mobile: Card Layout -->
        <div class="md:hidden space-y-3">
            @foreach($orders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono font-bold text-primary">{{ $order->order_code }}</span>
                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
                <div class="font-medium text-foreground">{{ $order->customer->name }}</div>
                <div class="text-sm text-gray-600">{{ $order->customer->phone }}</div>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        <span>{{ $order->service->name }}</span>
                        <span class="mx-1">•</span>
                        <span>{{ $order->scheduled_date->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="font-semibold text-green-600">{{ $order->formatted_total }}</div>
                </div>
                @if($order->technician)
                <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                    <i data-lucide="user" class="w-3 h-3"></i>
                    <span>{{ $order->technician->name }}</span>
                </div>
                @endif
            </a>
            @endforeach
        </div>

        <!-- Desktop: Table Layout -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b">
                        <th class="pb-3 font-medium">Kode Order</th>
                        <th class="pb-3 font-medium">Pelanggan</th>
                        <th class="pb-3 font-medium">Layanan</th>
                        <th class="pb-3 font-medium">Jadwal</th>
                        <th class="pb-3 font-medium">Teknisi</th>
                        <th class="pb-3 font-medium">Total</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-primary hover:underline">
                                {{ $order->order_code }}
                            </a>
                            <div class="text-xs text-gray-500">{{ $order->created_at->translatedFormat('d/m/Y H:i') }}</div>
                        </td>
                        <td class="py-4">
                            <div class="font-medium">{{ $order->customer->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer->phone }}</div>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="{{ $order->service->icon }}" class="w-4 h-4 text-primary"></i>
                                <span>{{ $order->service->name }}</span>
                            </div>
                            <div class="text-xs text-gray-500">{{ $order->ac_quantity }} Unit - {{ strtoupper($order->ac_capacity) }}</div>
                        </td>
                        <td class="py-4">
                            <div>{{ $order->scheduled_date->translatedFormat('d F Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->scheduled_time_slot }}</div>
                        </td>
                        <td class="py-4">
                            @if($order->technician)
                                <div class="flex items-center gap-2">
                                    <img src="{{ $order->technician->photo_url }}" class="w-6 h-6 rounded-full">
                                    <span class="text-sm">{{ $order->technician->name }}</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="py-4 font-semibold text-green-600">{{ $order->formatted_total }}</td>
                        <td class="py-4">
                            <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </td>
                        <td class="py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 pt-4 border-t">
            {{ $orders->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-12 text-gray-500">
            <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
            <p class="text-lg font-medium">Tidak ada order ditemukan</p>
            <p class="text-sm">Coba ubah filter pencarian</p>
        </div>
        @endif
    </x-cards.card>
</div>
@endsection
