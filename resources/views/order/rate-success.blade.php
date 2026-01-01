@extends('layouts.public')

@section('title', 'Terima Kasih - Rating Berhasil')

@section('content')
<section class="py-16 md:py-24 bg-gradient-to-br from-primary/5 via-white to-accent-teal/5 min-h-screen">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check-circle" class="w-10 h-10 text-green-500"></i>
            </div>

            <h1 class="text-2xl font-bold text-foreground mb-4">Terima Kasih! 🙏</h1>
            <p class="text-gray-600 mb-8">
                Rating Anda sangat berarti bagi kami untuk terus meningkatkan kualitas layanan.
            </p>

            <!-- Order Info -->
            <div class="bg-gray-50 rounded-xl p-4 mb-8 text-left">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Order</span>
                    <span class="font-mono font-bold text-primary">{{ $order->order_code }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Layanan</span>
                    <span class="font-medium">{{ $order->service->name }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <a href="/" class="btn btn-primary w-full justify-center">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    Kembali ke Beranda
                </a>
                
                <!-- Encourage Google Review -->
                <div class="pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-3">
                        Bantu kami tumbuh dengan memberikan review di Google Maps
                    </p>
                    <a href="https://g.page/r/YOUR_GOOGLE_PLACE_ID/review" target="_blank" class="btn btn-outline w-full justify-center text-blue-600 border-blue-600 hover:bg-blue-50">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Review di Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
