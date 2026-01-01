@extends('layouts.app')

@section('title', 'Dashboard - Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Dashboard</h1>
            <p class="text-gray-600">Selamat datang, <span class="font-semibold text-foreground">{{ Auth::user()->name }}</span></p>
        </div>
    </x-cards.card>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-cards.stat-card 
            label="Total Order" 
            :value="$stats['total_orders']" 
            icon="clipboard-list"
            iconBg="teal"
        />
        <x-cards.stat-card 
            label="Order Pending" 
            :value="$stats['pending_orders']" 
            icon="clock"
            iconBg="peach"
        />
        <x-cards.stat-card 
            label="Order Selesai" 
            :value="$stats['completed_orders']" 
            icon="check-circle"
            iconBg="lime"
        />
        <x-cards.stat-card 
            label="Total Pendapatan" 
            :value="'Rp ' . number_format($stats['total_revenue'], 0, ',', '.')" 
            icon="banknote"
            iconBg="lime"
        />
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <x-cards.card title="Pendapatan 7 Hari Terakhir">
            <div class="h-64">
                <canvas id="revenueChart" data-chart="{{ json_encode([
                    'labels' => $chartData['revenue']['labels'],
                    'data' => $chartData['revenue']['data']
                ]) }}"></canvas>
            </div>
        </x-cards.card>

        <!-- Order Status Chart -->
        <x-cards.card title="Status Order">
            <div class="h-64 flex items-center justify-center">
                <canvas id="statusChart" data-chart="{{ json_encode($chartData['status']) }}"></canvas>
            </div>
        </x-cards.card>
    </div>

    <!-- Recent Orders & Technicians -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <x-cards.card title="Order Terbaru">
                @if($recentOrders->count() > 0)
                
                <!-- Mobile: Card Layout -->
                <div class="md:hidden space-y-3">
                    @foreach($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono font-bold text-primary">{{ $order->order_code }}</span>
                            <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </div>
                        <div class="text-sm font-medium text-foreground">{{ $order->customer->name }}</div>
                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                            <span>{{ $order->service->name }}</span>
                            <span>•</span>
                            <span>{{ $order->scheduled_date->translatedFormat('d F Y') }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Desktop: Table Layout -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-gray-500 border-b">
                                <th class="pb-3 font-medium">Kode</th>
                                <th class="pb-3 font-medium">Pelanggan</th>
                                <th class="pb-3 font-medium">Layanan</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium">Jadwal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-primary hover:underline">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td class="py-3">
                                    <div class="font-medium">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->phone }}</div>
                                </td>
                                <td class="py-3">{{ $order->service->name }}</td>
                                <td class="py-3">
                                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                </td>
                                <td class="py-3 text-sm">{{ $order->scheduled_date->translatedFormat('d F Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-t text-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-primary hover:underline text-sm font-medium">Lihat Semua Order →</a>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                    <p>Belum ada order</p>
                </div>
                @endif
            </x-cards.card>
        </div>

        <!-- Technicians -->
        <div>
            <x-cards.card title="Teknisi">
                @if($technicians->count() > 0)
                <div class="space-y-4">
                    @foreach($technicians as $tech)
                    <div class="flex items-center gap-3">
                        <img src="{{ $tech->photo_url }}" alt="{{ $tech->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-foreground truncate">{{ $tech->name }}</div>
                            <div class="text-xs text-gray-500">{{ $tech->specialty }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-foreground">{{ $tech->orders_count }}</div>
                            <div class="text-xs text-gray-500">order</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-gray-500">
                    <p>Belum ada teknisi</p>
                </div>
                @endif
            </x-cards.card>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/pages/dashboard.js')
@endpush
@endsection
