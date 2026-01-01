@extends('layouts.public')

@section('title', 'Berikan Rating - ' . $order->order_code)

@section('content')
<section class="py-16 md:py-24 bg-gradient-to-br from-primary/5 via-white to-accent-teal/5 min-h-screen">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="star" class="w-8 h-8 text-primary"></i>
                </div>
                <h1 class="text-2xl font-bold text-foreground mb-2">Bagaimana Layanan Kami?</h1>
                <p class="text-gray-600">Berikan rating untuk order Anda</p>
            </div>

            <!-- Order Info -->
            <div class="bg-gray-50 rounded-xl p-4 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Order</span>
                    <span class="font-mono font-bold text-primary">{{ $order->order_code }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Layanan</span>
                    <span class="font-medium">{{ $order->service->name }}</span>
                </div>
                @if($order->technician)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Teknisi</span>
                    <span class="font-medium">{{ $order->technician->name }}</span>
                </div>
                @endif
            </div>

            <!-- Rating Form -->
            <form action="{{ route('order.rate.store', $order->order_code) }}" method="POST" x-data="{ rating: 0, hoverRating: 0 }">
                @csrf
                
                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
                        Pilih Rating
                    </label>
                    <div class="flex justify-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <button 
                            type="button"
                            @click="rating = {{ $i }}"
                            @mouseenter="hoverRating = {{ $i }}"
                            @mouseleave="hoverRating = 0"
                            class="text-4xl transition-transform hover:scale-110 focus:outline-none"
                            :class="(hoverRating >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400' : 'text-gray-300'"
                        >
                            <i data-lucide="star" class="w-10 h-10" :class="(hoverRating >= {{ $i }} || rating >= {{ $i }}) ? 'fill-yellow-400' : ''"></i>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" x-model="rating">
                    @error('rating')
                    <p class="text-red-500 text-sm text-center mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Komentar (opsional)
                    </label>
                    <textarea 
                        name="comment" 
                        id="comment" 
                        rows="4" 
                        placeholder="Ceritakan pengalaman Anda..."
                        class="form-input w-full"
                    ></textarea>
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    :disabled="rating === 0"
                    class="btn btn-primary w-full justify-center text-lg py-4 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i data-lucide="send" class="w-5 h-5"></i>
                    Kirim Rating
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
