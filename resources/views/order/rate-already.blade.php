@extends('layouts.public')

@section('title', 'Sudah Direview - ' . $order->order_code)

@section('content')
<section class="py-16 md:py-24 bg-gradient-to-br from-primary/5 via-white to-accent-teal/5 min-h-screen">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-8 text-center">
            <!-- Info Icon -->
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="info" class="w-10 h-10 text-blue-500"></i>
            </div>

            <h1 class="text-2xl font-bold text-foreground mb-4">Order Sudah Direview</h1>
            <p class="text-gray-600 mb-8">
                Anda sudah memberikan rating untuk order ini sebelumnya.
            </p>

            <!-- Previous Rating -->
            @if($order->review)
            <div class="bg-gray-50 rounded-xl p-4 mb-8">
                <p class="text-sm text-gray-500 mb-2">Rating Anda:</p>
                <div class="flex justify-center mb-2">
                    <x-star-rating :rating="$order->review->rating" size="lg" />
                </div>
                @if($order->review->comment)
                <p class="text-gray-700 italic">"{{ $order->review->comment }}"</p>
                @endif
            </div>
            @endif

            <!-- Action -->
            <a href="/" class="btn btn-primary justify-center">
                <i data-lucide="home" class="w-5 h-5"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection
