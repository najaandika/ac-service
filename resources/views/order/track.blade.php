@extends('layouts.public')

@section('title', 'Lacak Status Order - AC Service')

@section('content')
<section class="py-16 bg-gray-50 min-h-[calc(100vh-64px)]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search Form -->
        <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-8 mb-8 text-center">
            <h1 class="text-foreground text-2xl font-bold mb-2">Lacak Status Order</h1>
            <p class="text-gray-600 mb-6">Masukkan Kode Order atau Nomor WhatsApp Anda</p>
            
            <form action="{{ route('order.track') }}" method="GET" class="max-w-md mx-auto relative">
                <label for="track-query" class="sr-only">Kode Order atau Nomor WhatsApp</label>
                <input 
                    type="text" 
                    name="query"
                    id="track-query"
                    value="{{ $query ?? '' }}"
                    placeholder="Contoh: AC123XYZ atau 0812345xxxxx" 
                    class="form-input pl-5 pr-12 py-3.5 text-lg"
                    autocomplete="off"
                    required
                >
                <button type="submit" class="absolute right-2 top-2 p-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors" aria-label="Cari Order">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
            </form>
        </div>

        <!-- Tracking Results -->
        @if(isset($orders))
            @if($orders->count() > 0)
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-700 ml-2">Ditemukan {{ $orders->count() }} Order:</h2>
                    
                    @foreach($orders as $order)
                    <div class="bg-white rounded-[var(--radius-card)] shadow p-6 border-l-4 border-{{ $order->status_color }}">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="font-mono font-bold text-lg cursor-pointer hover:underline" onclick="navigator.clipboard.writeText('{{ $order->order_code }}')" title="Salin Kode">#{{ $order->order_code }}</span>
                                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-semibold text-green-600">{{ $order->formatted_total }}</div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Layanan</p>
                                <p class="font-medium flex items-center gap-2">
                                    <i data-lucide="{{ $order->service->icon }}" class="w-4 h-4 text-primary"></i>
                                    {{ $order->service->name }} ({{ $order->ac_quantity }} Unit)
                                </p>
                                <p class="text-sm text-gray-600 mt-1">{{ ucfirst($order->ac_type) }} - {{ $order->ac_capacity }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Jadwal</p>
                                <p class="font-medium flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                                    {{ $order->scheduled_date->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    {{ $order->scheduled_time_slot }}
                                </p>
                            </div>
                        </div>

                        @if($order->technician)
                        <div class="mt-4 bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                            <img src="{{ $order->technician->photo_url }}" alt="{{ $order->technician->name }}" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Teknisi</p>
                                <p class="font-semibold">{{ $order->technician->name }}</p>
                            </div>
                            <div class="ml-auto">
                                <a href="https://wa.me/{{ $order->technician->phone }}" target="_blank" class="btn btn-sm btn-outline gap-1">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    Chat
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="mt-4 bg-yellow-50 text-yellow-800 text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4"></i>
                            Teknisi sedang dijadwalkan oleh admin.
                        </div>
                        @endif

                        <!-- Rating Button for Completed Orders -->
                        @if($order->status === 'completed')
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            @if($order->hasReview())
                            <div class="bg-green-50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <p class="text-sm font-medium text-green-800">Rating Anda:</p>
                                    <x-star-rating :rating="$order->review->rating" size="sm" />
                                </div>
                                @if($order->review->comment)
                                <p class="text-sm text-gray-600 italic">"{{ $order->review->comment }}"</p>
                                @endif
                            </div>
                            
                            {{-- Google Maps Review CTA for satisfied customers (4-5 stars) --}}
                            @if($order->review->rating >= 4 && !empty($settings['google_maps_url']))
                            <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800 mb-1">Puas dengan layanan kami? 🙏</p>
                                        <p class="text-xs text-gray-600 mb-3">Bantu kami dengan memberi ulasan di Google Maps agar lebih banyak orang mengenal layanan kami.</p>
                                        <a href="{{ $settings['google_maps_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-50 transition-colors">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21.35 11.1h-9.17v2.73h6.51c-.33 3.81-3.5 5.44-6.5 5.44C8.36 19.27 5 16.25 5 12c0-4.1 3.2-7.27 7.2-7.27 3.09 0 4.9 1.97 4.9 1.97L19 4.72S16.56 2 12.1 2C6.42 2 2.03 6.8 2.03 12c0 5.05 4.13 10 10.22 10 5.35 0 9.25-3.67 9.25-9.09 0-1.15-.15-1.81-.15-1.81z"/>
                                            </svg>
                                            Beri Ulasan di Google
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @else
                            <a href="{{ route('order.rate.show', $order->order_code) }}" class="btn btn-primary w-full justify-center">
                                <i data-lucide="star" class="w-5 h-5"></i>
                                Berikan Rating
                            </a>
                            <p class="text-xs text-gray-500 text-center mt-2">Bantu kami meningkatkan layanan dengan rating Anda</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-[var(--radius-card)] p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search-x" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Order Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">
                        Maaf, kami tidak dapat menemukan order dengan kata kunci <strong>"{{ $query }}"</strong>.
                        Silakan periksa kembali kode order atau nomor HP Anda.
                    </p>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
