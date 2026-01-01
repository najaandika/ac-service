@extends('layouts.app')

@section('title', 'Detail Pelanggan - Admin')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-foreground text-2xl font-bold hidden lg:block">{{ $customer->name }}</h1>
                <p class="text-gray-600">Detail pelanggan dan riwayat order</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
        </div>
    </x-cards.card>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="lg:col-span-1">
            <x-cards.card title="Informasi Pelanggan">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Nama</p>
                        <p class="text-foreground font-medium">{{ $customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Telepon</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->formatted_phone) }}" class="text-primary hover:underline flex items-center gap-1">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            {{ $customer->phone }}
                        </a>
                    </div>
                    @if($customer->email)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                        <a href="mailto:{{ $customer->email }}" class="text-primary hover:underline">{{ $customer->email }}</a>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Alamat</p>
                        <p class="text-foreground">{{ $customer->address }}</p>
                    </div>
                    @if($customer->city)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Kota</p>
                        <p class="text-foreground">{{ $customer->city }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Terdaftar</p>
                        <p class="text-foreground">{{ $customer->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </x-cards.card>
        </div>

        <!-- Order History -->
        <div class="lg:col-span-2">
            <x-cards.card title="Riwayat Order">
                @if($customer->orders->count() > 0)
                <div class="space-y-4">
                    @foreach($customer->orders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="block bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-foreground">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->service->name ?? 'Layanan' }}</p>
                            </div>
                            <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">{{ $order->created_at->translatedFormat('d F Y H:i') }}</span>
                            <span class="font-semibold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">Belum ada riwayat order</p>
                </div>
                @endif
            </x-cards.card>
        </div>
    </div>
</div>
@endsection
