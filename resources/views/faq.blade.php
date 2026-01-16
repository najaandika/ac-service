@extends('layouts.public')

@section('title', 'FAQ - Pertanyaan Umum')

@section('description', 'Pertanyaan yang sering diajukan tentang layanan service AC kami. Temukan jawaban tentang harga, area layanan, garansi, dan cara booking.')

@section('content')
<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium mb-3">
                FAQ
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-3">
                Pertanyaan Umum
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Temukan jawaban untuk pertanyaan yang sering diajukan tentang layanan kami
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="max-w-3xl mx-auto space-y-4">
            @foreach($faqs as $index => $faq)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden" 
                 x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-foreground">{{ $faq['question'] }}</span>
                    <i data-lucide="chevron-down" 
                       class="w-5 h-5 text-gray-400 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" 
                     x-collapse
                     class="px-6 pb-4">
                    <p class="text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA Section -->
        <div class="max-w-3xl mx-auto mt-12 text-center">
            <div class="bg-primary/5 rounded-2xl p-8">
                <h2 class="text-xl font-bold text-foreground mb-2">Masih ada pertanyaan?</h2>
                <p class="text-gray-600 mb-6">Hubungi kami langsung via WhatsApp untuk pertanyaan lebih lanjut</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? '') }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-full font-semibold transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Schema for SEO -->
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection
