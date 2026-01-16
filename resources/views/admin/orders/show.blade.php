@extends('layouts.app')

@section('title', 'Detail Order #' . $order->order_code . ' - Admin')
@section('page-title', 'Detail Order')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-foreground text-2xl font-bold hidden lg:flex items-center gap-3">
                    Order #{{ $order->order_code }}
                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </h1>
                <p class="text-gray-600">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>
    </x-cards.card>

    @if(session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Info -->
            <x-cards.card title="Detail Layanan">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-14 h-14 bg-accent-teal rounded-xl flex items-center justify-center">
                        <i data-lucide="{{ $order->service->icon }}" class="w-7 h-7 text-foreground"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-foreground">{{ $order->service->name }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-600">{{ $order->formatted_total }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tipe AC</p>
                        <p class="font-medium">{{ ucfirst($order->ac_type) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Kapasitas</p>
                        <p class="font-medium">{{ strtoupper($order->ac_capacity) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jumlah Unit</p>
                        <p class="font-medium">{{ $order->ac_quantity }} Unit</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Harga Satuan</p>
                        <p class="font-medium">Rp {{ number_format($order->service_price / $order->ac_quantity, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($order->notes)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-xs text-gray-500 mb-1">Catatan Pelanggan</p>
                    <p class="text-gray-700 bg-gray-50 rounded-lg p-3">{{ $order->notes }}</p>
                </div>
                @endif
            </x-cards.card>

            <!-- Schedule & Location -->
            <x-cards.card title="Jadwal & Lokasi">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                            {{ $order->scheduled_date->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Waktu</p>
                        <p class="font-medium flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-primary"></i>
                            {{ $order->scheduled_time_slot }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <p class="text-xs text-gray-500 mb-1">Alamat</p>
                    <p class="font-medium">{{ $order->customer->address }}</p>
                    @if($order->customer->city)
                    <p class="text-gray-500">{{ $order->customer->city }}</p>
                    @endif
                </div>
            </x-cards.card>

            <!-- Customer Info -->
            <x-cards.card title="Data Pelanggan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama</p>
                        <p class="font-medium">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">No. HP / WhatsApp</p>
                        <p class="font-medium">
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer->phone) }}" 
                               target="_blank" 
                               class="text-primary hover:underline flex items-center gap-1">
                                {{ $order->customer->phone }}
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                            </a>
                        </p>
                    </div>
                    @if($order->customer->email)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="font-medium">{{ $order->customer->email }}</p>
                    </div>
                    @endif
                </div>
            </x-cards.card>

            <!-- Customer Review -->
            @if($order->review)
            <x-cards.card title="Rating Pelanggan">
                <div class="flex items-center gap-4 mb-4">
                    <x-star-rating :rating="$order->review->rating" size="lg" />
                    <span class="text-2xl font-bold text-foreground">{{ $order->review->rating }}/5</span>
                </div>
                @if($order->review->comment)
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-700 italic">"{{ $order->review->comment }}"</p>
                </div>
                @endif
                <p class="text-xs text-gray-500 mt-3">
                    Diberikan pada {{ $order->review->created_at->translatedFormat('d F Y, H:i') }}
                </p>
            </x-cards.card>
            @elseif($order->status === 'completed')
            <x-cards.card title="Rating Pelanggan">
                <div class="text-center py-4 text-gray-500">
                    <i data-lucide="star-off" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                    <p>Belum ada rating dari pelanggan</p>
                    <p class="text-xs mt-1">Link rating: <span class="font-mono text-primary">{{ url("/order/{$order->order_code}/rate") }}</span></p>
                </div>
            </x-cards.card>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Update Status -->
            <x-cards.card title="Update Status">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div class="space-y-2">
                            @foreach(['pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'in_progress' => 'Dalam Proses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                            <label for="status_{{ $value }}" class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:border-primary transition-colors {{ $order->status === $value ? 'border-primary bg-primary/5' : '' }}">
                                <input type="radio" name="status" id="status_{{ $value }}" value="{{ $value }}" {{ $order->status === $value ? 'checked' : '' }} class="text-primary">
                                <span class="font-medium">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Update Status
                        </button>
                    </div>
                </form>
                
                <!-- WhatsApp Notification Button -->
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-outline w-full justify-center text-green-600 border-green-600 hover:bg-green-50">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Kirim Notifikasi WA
                    </a>
                </div>
            </x-cards.card>

            <!-- Assign Technician -->
            <x-cards.card title="Tugaskan Teknisi">
                <form action="{{ route('admin.orders.technician', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        @if($order->technician)
                        <div class="flex items-center gap-3 p-3 bg-success-light rounded-lg">
                            <img src="{{ $order->technician->photo_url }}" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium text-foreground">{{ $order->technician->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->technician->phone }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <label for="technician_id" class="sr-only">Pilih Teknisi</label>
                        <select name="technician_id" id="technician_id" class="form-input">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $order->technician_id == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }} ({{ $tech->specialty }})
                            </option>
                            @endforeach
                        </select>
                        
                        <button type="submit" class="btn btn-outline w-full justify-center">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            {{ $order->technician ? 'Ganti' : 'Tugaskan' }} Teknisi
                        </button>
                    </div>
                </form>
            </x-cards.card>

            <!-- Technician Departure -->
            @if($order->technician && !$order->departed_at && !in_array($order->status, ['completed', 'cancelled']))
            <x-cards.card title="Keberangkatan Teknisi">
                <form action="{{ route('admin.orders.departed', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn w-full justify-center bg-blue-500 hover:bg-blue-600 text-white border-0">
                        <i data-lucide="navigation" class="w-4 h-4"></i>
                        Teknisi Berangkat
                    </button>
                    <p class="text-xs text-gray-500 text-center mt-2">Klik saat teknisi berangkat ke lokasi</p>
                </form>
            </x-cards.card>
            @endif

            @if($order->departed_at)
            <x-cards.card title="Status Perjalanan">
                <div class="text-center py-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                        Teknisi Dalam Perjalanan
                    </div>
                    <p class="text-gray-600 mt-2">Berangkat: <strong>{{ $order->departed_at->format('H:i') }}</strong></p>
                </div>
            </x-cards.card>
            @endif

            <!-- Quick Actions -->
            <x-cards.card title="Aksi Cepat">
                <div class="space-y-3">
                    {{-- Reminder H-1 Button --}}
                    <a href="{{ $reminderUrl }}" 
                       target="_blank"
                       class="btn w-full justify-center bg-amber-500 hover:bg-amber-600 text-white border-0">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        Kirim Reminder H-1
                    </a>
                    
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer->phone) }}?text=Halo {{ $order->customer->name }}, order AC Service Anda dengan kode *{{ $order->order_code }}* sedang kami proses." 
                       target="_blank"
                       class="btn btn-outline w-full justify-center">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Hubungi Pelanggan
                    </a>
                    @if($order->technician)
                    <a href="https://wa.me/{{ $order->technician->phone }}?text=Halo {{ $order->technician->name }}, ada order baru untuk Anda. Kode: *{{ $order->order_code }}*" 
                       target="_blank"
                       class="btn btn-outline w-full justify-center">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Hubungi Teknisi
                    </a>
                    @endif
                </div>
            </x-cards.card>
        </div>
    </div>
</div>
@endsection
