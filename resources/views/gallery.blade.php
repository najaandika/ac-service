@extends('layouts.public')

@section('title', 'Gallery Hasil Kerja | Before After Service AC Profesional')

@section('description', 'Lihat gallery hasil kerja jasa service AC profesional kami. Foto before after cuci AC, isi freon, bongkar pasang AC dengan hasil terjamin. Bukti nyata kualitas layanan.')

@section('content')
<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium mb-3">
                Gallery
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-3">
                Hasil Kerja Kami
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Lihat transformasi AC sebelum dan sesudah ditangani oleh tim profesional kami
            </p>
        </div>

        @if($portfolios->count() > 0)
        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($portfolios as $portfolio)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                <!-- Before/After Slider -->
                <div class="relative aspect-[4/3] overflow-hidden" 
                     x-data="{ showAfter: false }">
                    <!-- Before Image -->
                    <img src="{{ asset('storage/' . $portfolio->before_image) }}" 
                         alt="Before - {{ $portfolio->title }}" 
                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                         :class="showAfter ? 'opacity-0' : 'opacity-100'">
                    
                    <!-- After Image -->
                    <img src="{{ asset('storage/' . $portfolio->after_image) }}" 
                         alt="After - {{ $portfolio->title }}" 
                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                         :class="showAfter ? 'opacity-100' : 'opacity-0'">
                    
                    <!-- Labels -->
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold shadow-lg transition-all duration-300"
                          :class="showAfter ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
                          x-text="showAfter ? 'AFTER' : 'BEFORE'"></span>
                    
                    <!-- Toggle Button -->
                    <button @click="showAfter = !showAfter"
                            class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-gray-700 px-4 py-2 rounded-full text-sm font-medium shadow-lg hover:bg-white transition-colors flex items-center gap-2">
                        <i data-lucide="repeat" class="w-4 h-4"></i>
                        <span x-text="showAfter ? 'Lihat Before' : 'Lihat After'"></span>
                    </button>
                </div>
                
                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-semibold text-foreground text-lg mb-1">{{ $portfolio->title }}</h3>
                    @if($portfolio->service)
                    <span class="inline-block px-2 py-0.5 bg-primary/10 text-primary text-xs rounded-full mb-2">
                        {{ $portfolio->service->name }}
                    </span>
                    @endif
                    @if($portfolio->description)
                    <p class="text-gray-600 text-sm line-clamp-2">{{ $portfolio->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($portfolios->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $portfolios->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="image" class="w-10 h-10 text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Portfolio</h3>
            <p class="text-gray-500">Gallery hasil kerja kami akan segera hadir.</p>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 bg-primary">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
            Ingin AC Anda Seperti Baru Lagi?
        </h2>
        <p class="text-white/80 mb-6">
            Tim profesional kami siap membantu Anda
        </p>
        <a href="{{ route('order.create') }}" class="inline-flex items-center gap-2 bg-white text-primary px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors shadow-lg">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            Order Sekarang
        </a>
    </div>
</section>
@endsection
