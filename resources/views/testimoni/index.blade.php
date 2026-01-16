@extends('layouts.public')

@section('title', 'Testimoni Pelanggan - AC Service')

@section('description', 'Lihat testimoni dan review pelanggan AC Service. Rating ' . ($stats['average'] ?? '5') . ' bintang dari ' . ($stats['total'] ?? '0') . ' pelanggan. Bukti nyata kualitas layanan kami.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary/10 via-white to-accent-teal/10 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-foreground text-4xl md:text-5xl font-extrabold mb-4 fade-up">
            Apa Kata <span class="text-primary">Pelanggan</span> Kami
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-8 fade-up delay-100">
            Testimoni dari pelanggan yang telah menggunakan layanan kami
        </p>
        
        <!-- Stats -->
        <div class="flex flex-wrap justify-center gap-8 fade-up delay-200">
            <div class="text-center">
                <div class="text-4xl font-bold text-primary">{{ $stats['total'] }}</div>
                <div class="text-gray-500 text-sm">Total Review</div>
            </div>
            <div class="text-center">
                <div class="flex items-center gap-1 justify-center">
                    <span class="text-4xl font-bold text-yellow-500">{{ $stats['average'] }}</span>
                    <i data-lucide="star" class="w-8 h-8 text-yellow-400 fill-yellow-400"></i>
                </div>
                <div class="text-gray-500 text-sm">Rating Rata-rata</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600">{{ $stats['five_star'] }}</div>
                <div class="text-gray-500 text-sm">Review Bintang 5</div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Grid -->
<section class="py-16 bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($reviews->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
            <div class="bg-white rounded-[var(--radius-card)] p-6 shadow-sm hover:shadow-md transition-shadow">
                <!-- Stars -->
                <div class="mb-3">
                    <x-star-rating :rating="$review->rating" />
                </div>
                
                <!-- Comment -->
                @if($review->comment)
                <p class="text-gray-700 mb-4 line-clamp-4">"{{ $review->comment }}"</p>
                @else
                <p class="text-gray-400 italic mb-4">Tidak ada komentar</p>
                @endif
                
                <!-- Customer Info -->
                <div class="flex items-center gap-3 pt-4 border-t">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <span class="font-bold text-primary">{{ strtoupper(substr($review->order->customer->name ?? 'C', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-foreground truncate">{{ $review->order->customer->name ?? 'Customer' }}</p>
                        <p class="text-xs text-gray-500">{{ $review->order->service->name ?? 'Layanan' }}</p>
                    </div>
                </div>
                
                <!-- Date -->
                <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i data-lucide="message-square" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <h3 class="text-xl font-bold text-gray-500 mb-2">Belum Ada Testimoni</h3>
            <p class="text-gray-400">Jadilah yang pertama memberikan review!</p>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-up">
        <h2 class="text-white text-3xl font-bold mb-4">Ingin AC Anda Dingin Maksimal?</h2>
        <p class="text-white/80 mb-8">Pesan layanan service AC sekarang dan rasakan perbedaannya!</p>
        <a href="/order" class="btn bg-white text-primary hover:bg-gray-100 text-lg px-8 py-4 justify-center inline-flex">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            Order Sekarang
        </a>
    </div>
</section>

<!-- AggregateRating Schema for SEO -->
@if($stats['total'] > 0)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $settings['company_name'] ?? 'AC Service',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => $stats['average'],
        'reviewCount' => $stats['total'],
        'bestRating' => '5',
        'worstRating' => '1',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endsection

