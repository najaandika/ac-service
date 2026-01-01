@extends('layouts.app')

@section('title', 'Laporan - Admin')
@section('page-title', 'Laporan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Laporan</h1>
            <p class="text-gray-600">Analisis pendapatan dan performa</p>
        </div>
    </x-cards.card>

    <!-- Date Filter -->
    <x-cards.card>
        <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:gap-4 md:items-end">
            <div class="w-full md:flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-input w-full">
            </div>
            <div class="w-full md:flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-input w-full">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="btn btn-primary flex-1 md:flex-none justify-center">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline flex-1 md:flex-none justify-center">Reset</a>
            </div>
            <div class="flex gap-2 w-full md:w-auto border-l pl-4 ml-2">
                <a href="{{ route('admin.reports.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline flex-1 md:flex-none justify-center text-green-600 border-green-600 hover:bg-green-50">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    Excel
                </a>
                <a href="{{ route('admin.reports.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline flex-1 md:flex-none justify-center text-red-600 border-red-600 hover:bg-red-50">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    PDF
                </a>
            </div>
        </form>
    </x-cards.card>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-cards.stat-card 
            label="Total Order" 
            :value="$stats['total_orders']" 
            icon="clipboard-list"
            iconBg="teal"
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
        <x-cards.stat-card 
            label="Rata-rata Order" 
            :value="'Rp ' . number_format($stats['average_order'], 0, ',', '.')" 
            icon="trending-up"
            iconBg="teal"
        />
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <x-cards.card title="Trend Pendapatan">
            <div class="h-72">
                <canvas id="revenueChart" data-chart="{{ json_encode([
                    'labels' => $revenueChart['labels'],
                    'data' => $revenueChart['data']
                ]) }}"></canvas>
            </div>
        </x-cards.card>

        <!-- Service Chart -->
        <x-cards.card title="Order per Layanan">
            <div class="h-72">
                <canvas id="serviceChart" data-chart="{{ json_encode([
                    'labels' => $serviceChart['labels'],
                    'data' => $serviceChart['data']
                ]) }}"></canvas>
            </div>
        </x-cards.card>
    </div>

    <!-- Technician Performance -->
    <x-cards.card title="Performa Teknisi">
        @if($technicianPerformance->count() > 0)
        
        <!-- Mobile: Card Layout -->
        <div class="space-y-4 md:hidden">
            @foreach($technicianPerformance as $tech)
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $tech->photo_url }}" alt="{{ $tech->name }}" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h4 class="font-semibold text-foreground">{{ $tech->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $tech->specialty }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-white rounded-lg p-2">
                        <p class="text-xs text-gray-500">Order</p>
                        <p class="font-bold text-foreground">{{ $tech->orders_count }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-2">
                        <p class="text-xs text-gray-500">Pendapatan</p>
                        <p class="font-semibold text-green-600 text-sm">Rp {{ number_format(($tech->orders_sum_total_price ?? 0) / 1000, 0) }}K</p>
                    </div>
                    <div class="bg-white rounded-lg p-2">
                        <p class="text-xs text-gray-500">Rating</p>
                        <div class="flex items-center justify-center gap-1">
                            <i data-lucide="star" class="w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                            <span class="font-bold">{{ number_format($tech->rating, 1) }}</span>
                        </div>
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
                        <th class="pb-3 font-medium">Teknisi</th>
                        <th class="pb-3 font-medium">Spesialisasi</th>
                        <th class="pb-3 font-medium text-center">Total Order</th>
                        <th class="pb-3 font-medium text-right">Pendapatan</th>
                        <th class="pb-3 font-medium text-center">Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($technicianPerformance as $tech)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $tech->photo_url }}" alt="{{ $tech->name }}" class="w-10 h-10 rounded-full object-cover">
                                <span class="font-medium">{{ $tech->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-gray-600">{{ $tech->specialty }}</td>
                        <td class="py-4 text-center">
                            <span class="font-bold text-foreground">{{ $tech->orders_count }}</span>
                        </td>
                        <td class="py-4 text-right font-semibold text-green-600">
                            Rp {{ number_format($tech->orders_sum_total_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <i data-lucide="star" class="w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                                <span class="font-medium">{{ number_format($tech->rating, 1) }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @else
        <div class="text-center py-8 text-gray-500">
            <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
            <p>Belum ada data teknisi</p>
        </div>
        @endif
    </x-cards.card>
</div>

@push('scripts')
    @vite('resources/js/pages/reports.js')
@endpush
@endsection
