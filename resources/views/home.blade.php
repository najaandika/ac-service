@extends('layouts.public')

@section('title', 'Tunggal Jaya Tehnik - Jasa Service AC, Mesin Cuci, Kulkas & Elektronik')

@section('description', 'Jasa service AC, mesin cuci, kulkas, pemasangan water heater & pompa air. Teknisi profesional berpengalaman, garansi layanan, harga terjangkau. Hubungi sekarang!')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary/10 via-white to-accent-teal/10 py-20 md:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="fade-left">
                <h1 class="text-foreground text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6">
                    {!! $settings['hero_title'] ?? 'Service AC <span class="text-primary">Profesional</span> & Terpercaya' !!}
                </h1>
                <p class="text-gray-600 text-lg md:text-xl mb-8 max-w-lg">
                    {{ $settings['hero_subtitle'] ?? 'Teknisi berpengalaman, harga transparan, dan garansi layanan. Melayani cuci AC, isi freon, perbaikan, hingga instalasi.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/order" class="btn btn-primary text-lg px-8 py-4 justify-center">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span>Mulai Sekarang</span>
                    </a>
                </div>
                
                {{-- Social Proof Stats --}}
                @if(isset($stats) && $stats['completed_orders'] > 0)
                <div class="flex flex-wrap items-center gap-6 mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-success-light rounded-full flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
                        </div>
                        <div>
                            <p class="font-bold text-foreground">{{ number_format($stats['completed_orders']) }}+</p>
                            <p class="text-xs text-gray-500">Order Selesai</p>
                        </div>
                    </div>
                    @if($stats['average_rating'] > 0)
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i data-lucide="star" class="w-5 h-5 text-yellow-500 fill-yellow-500"></i>
                        </div>
                        <div>
                            <p class="font-bold text-foreground">{{ $stats['average_rating'] }}/5</p>
                            <p class="text-xs text-gray-500">Rating ({{ $stats['total_reviews'] }} ulasan)</p>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <p class="font-bold text-foreground">30 Hari</p>
                            <p class="text-xs text-gray-500">Garansi Layanan</p>
                        </div>
                    </div>
                    {{-- Google Maps Badge --}}
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#EA4335"/>
                                <circle cx="12" cy="9" r="2.5" fill="#fff"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-foreground flex items-center gap-1">
                                5.0 <i data-lucide="star" class="w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                            </p>
                            <p class="text-xs text-gray-500">Google Maps (3)</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="hidden lg:block fade-right">
                <div class="relative">
                    @if(!empty($settings['hero_image']))
                    <img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="{{ $settings['site_name'] ?? 'AC Service' }}" class="w-full h-96 object-cover rounded-[var(--radius-card)]">
                    @else
                    <div class="w-full h-96 bg-gradient-to-br from-primary to-primary-hover rounded-[var(--radius-card)] flex items-center justify-center">
                        <i data-lucide="wind" class="w-48 h-48 text-white/30"></i>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promo Banner -->
<x-promo-banner :promos="$activePromos ?? collect([])" />

<!-- Layanan Section -->
<section id="layanan" class="py-16 md:py-24 bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Layanan Kami" 
            subtitle="Berbagai layanan service elektronik rumah tangga dengan kualitas terbaik dan harga terjangkau" 
            class="fade-up"
        />
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-up delay-200">
            @foreach($services as $service)
                <x-home.service-card :service="$service" />
            @endforeach
        </div>
    </div>
</section>

<!-- Kenapa Kami Section -->
<section id="kenapa-kami" class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Kenapa Pilih Kami?" 
            subtitle="Alasan mengapa ribuan pelanggan mempercayakan service AC mereka kepada kami" 
            class="fade-up"
        />
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 fade-up delay-200">
            <x-home.feature-card 
                icon="award" 
                color="lime" 
                title="Teknisi Bersertifikat" 
                description="Semua teknisi kami telah tersertifikasi dan berpengalaman" 
            />
            <x-home.feature-card 
                icon="shield-check" 
                color="teal" 
                title="Garansi Layanan" 
                description="Garansi 30 hari untuk setiap layanan yang kami berikan" 
            />
            <x-home.feature-card 
                icon="clock" 
                color="peach" 
                title="Respon Cepat" 
                description="Teknisi datang dalam waktu 2-4 jam setelah konfirmasi" 
            />
            <x-home.feature-card 
                icon="banknote" 
                color="primary" 
                title="Harga Transparan" 
                description="Tanpa biaya tersembunyi, harga sudah termasuk sparepart" 
            />
        </div>
    </div>
</section>

<!-- Area Layanan Section -->
@if(!empty($settings['service_areas']))
<section id="area-layanan" class="py-16 md:py-24 bg-gradient-to-br from-primary/5 to-accent-teal/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 rounded-full mb-4">
                <i data-lucide="map-pin" class="w-8 h-8 text-primary"></i>
            </div>
            <h2 class="text-foreground text-3xl md:text-4xl font-bold mb-4">Area Layanan Kami</h2>
            <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                Kami melayani service AC di berbagai wilayah berikut
            </p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-3 fade-up delay-200">
            @foreach(explode(',', $settings['service_areas']) as $area)
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-foreground shadow-sm hover:shadow-md hover:border-primary/30 transition-all">
                    <i data-lucide="check" class="w-4 h-4 text-success"></i>
                    {{ trim($area) }}
                </span>
            @endforeach
        </div>
        
        <p class="text-center text-gray-500 text-sm mt-6 fade-up delay-300">
            <i data-lucide="info" class="w-4 h-4 inline-block mr-1"></i>
            Area tidak terdaftar? Silakan hubungi kami untuk konfirmasi ketersediaan.
        </p>
    </div>
</section>
@endif

<!-- Testimoni Section -->
@if(isset($latestReviews) && $latestReviews->count() > 0)
<section id="testimoni" class="py-16 md:py-24 bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Apa Kata Pelanggan" 
            subtitle="Testimoni dari pelanggan yang telah menggunakan layanan kami"
            class="fade-up"
        />
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-up delay-200">
            @foreach($latestReviews as $review)
            <div class="bg-white rounded-[var(--radius-card)] p-6 shadow-sm hover:shadow-md transition-shadow">
                <!-- Stars -->
                <div class="mb-3">
                    <x-star-rating :rating="$review->rating" />
                </div>
                
                <!-- Comment -->
                @if($review->comment)
                <p class="text-gray-700 mb-4 line-clamp-3">"{{ $review->comment }}"</p>
                @else
                <p class="text-gray-400 italic mb-4">Rating {{ $review->rating }} bintang</p>
                @endif
                
                <!-- Customer Info -->
                <div class="flex items-center gap-3 pt-4 border-t">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <span class="font-bold text-primary">{{ strtoupper(substr($review->order->customer->name ?? 'C', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-foreground truncate">{{ $review->order->customer->name ?? 'Customer' }}</p>
                        <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8 fade-up delay-300">
            <a href="{{ route('testimoni.index') }}" class="btn btn-outline">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                Lihat Semua Testimoni
            </a>
        </div>
    </div>
</section>
@endif

<!-- Gallery Preview Section -->
@if(isset($portfolios) && $portfolios->count() > 0)
<section id="gallery" class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Hasil Kerja Kami" 
            subtitle="Lihat transformasi AC sebelum dan sesudah ditangani tim profesional kami"
            class="fade-up"
        />
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 fade-up delay-200">
            @foreach($portfolios as $portfolio)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden group hover:shadow-lg transition-all duration-300" 
                 x-data="{ showAfter: false }">
                <!-- Before/After Toggle -->
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('storage/' . $portfolio->before_image) }}" 
                         alt="Before" 
                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                         :class="showAfter ? 'opacity-0' : 'opacity-100'">
                    <img src="{{ asset('storage/' . $portfolio->after_image) }}" 
                         alt="After" 
                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                         :class="showAfter ? 'opacity-100' : 'opacity-0'">
                    
                    <span class="absolute top-2 left-2 px-2 py-1 rounded text-xs font-bold shadow"
                          :class="showAfter ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
                          x-text="showAfter ? 'AFTER' : 'BEFORE'"></span>
                    
                    <button @click="showAfter = !showAfter"
                            class="absolute bottom-2 right-2 bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-full shadow-lg hover:bg-white transition-colors">
                        <i data-lucide="repeat" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-3">
                    <h3 class="font-medium text-foreground text-sm truncate">{{ $portfolio->title }}</h3>
                    @if($portfolio->service)
                    <span class="text-xs text-gray-500">{{ $portfolio->service->name }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8 fade-up delay-300">
            <a href="{{ route('gallery') }}" class="btn btn-outline">
                <i data-lucide="images" class="w-5 h-5"></i>
                Lihat Semua Gallery
            </a>
        </div>
    </div>
</section>
@endif

<!-- Cara Order Section -->
<section id="cara-order" class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Cara Order" 
            subtitle="3 langkah mudah untuk memesan layanan service AC" 
            class="fade-up"
        />
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-up delay-200">
            <x-home.step-card 
                number="1" 
                title="Pilih Layanan" 
                description="Pilih jenis layanan yang Anda butuhkan sesuai kondisi AC" 
            />
            <x-home.step-card 
                number="2" 
                title="Isi Form Order" 
                description="Lengkapi data diri, alamat, dan jadwal yang diinginkan" 
            />
            <x-home.step-card 
                number="3" 
                title="Teknisi Datang" 
                description="Teknisi akan menghubungi dan datang sesuai jadwal" 
            />
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 md:py-24 bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-up">
        <h2 class="text-white text-3xl md:text-4xl font-extrabold mb-4">Butuh Service Elektronik?</h2>
        <p class="text-white/80 text-lg mb-8">Hubungi kami sekarang untuk service AC, mesin cuci, kulkas, dan lainnya</p>
        <div class="flex justify-center">
            <a href="/order" class="btn bg-white text-primary hover:bg-gray-100 text-lg px-8 py-4 justify-center">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span>Order Sekarang</span>
            </a>
        </div>
    </div>
</section>

@push('head')
{{-- JSON-LD Structured Data for LocalBusiness --}}
@php
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $settings['site_name'] ?? 'Tunggal Jaya Tehnik',
    'description' => $settings['site_description'] ?? 'Jasa service AC, mesin cuci, kulkas, water heater, dan pompa air',
    '@id' => url('/'),
    'url' => url('/'),
    'priceRange' => 'Rp 50.000 - Rp 500.000',
    'areaServed' => [
        '@type' => 'City',
        'name' => 'Sidoarjo'
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name' => 'Layanan Service Elektronik',
        'itemListElement' => [
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Service AC']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Service Mesin Cuci']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Service Kulkas']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Pemasangan Water Heater']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Pemasangan Pompa Air']],
        ]
    ],
    'openingHoursSpecification' => [
        '@type' => 'OpeningHoursSpecification',
        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        'opens' => '08:00',
        'closes' => '21:00',
    ],
];

if (!empty($settings['site_logo'])) {
    $jsonLd['image'] = asset('storage/' . $settings['site_logo']);
}
if (!empty($settings['phone'])) {
    $jsonLd['telephone'] = $settings['phone'];
}
if (!empty($settings['email'])) {
    $jsonLd['email'] = $settings['email'];
}
if (!empty($settings['address'])) {
    $jsonLd['address'] = [
        '@type' => 'PostalAddress',
        'streetAddress' => $settings['address'],
    ];
}
if (isset($stats) && $stats['average_rating'] > 0) {
    $jsonLd['aggregateRating'] = [
        '@type' => 'AggregateRating',
        'ratingValue' => $stats['average_rating'],
        'reviewCount' => $stats['total_reviews'],
    ];
}
@endphp
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endpush
@endsection
