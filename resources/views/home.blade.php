@extends('layouts.public')

@section('title', 'AC Service - Jasa Service AC Profesional')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary/10 via-white to-accent-teal/10 py-20 md:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-foreground text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6">
                    {!! $settings['hero_title'] ?? 'Service AC <span class="text-primary">Profesional</span> & Terpercaya' !!}
                </h1>
                <p class="text-gray-600 text-lg md:text-xl mb-8 max-w-lg">
                    {{ $settings['hero_subtitle'] ?? 'Teknisi berpengalaman, harga transparan, dan garansi layanan. Melayani cuci AC, isi freon, perbaikan, hingga instalasi.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/order" class="btn btn-primary text-lg px-8 py-4 justify-center">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span>Order Sekarang</span>
                    </a>
                    @if(!empty($settings['whatsapp']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" class="btn btn-outline text-lg px-8 py-4 justify-center">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        <span>WhatsApp</span>
                    </a>
                    @endif
                </div>
            </div>
            <div class="hidden lg:block">
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

<!-- Layanan Section -->
<section id="layanan" class="py-16 md:py-24 bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Layanan Kami" 
            subtitle="Berbagai layanan service AC dengan kualitas terbaik dan harga terjangkau" 
        />
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
        />
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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

<!-- Cara Order Section -->
<section id="cara-order" class="py-16 md:py-24 bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-home.section-heading 
            title="Cara Order" 
            subtitle="3 langkah mudah untuk memesan layanan service AC" 
        />
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
        
        <div class="text-center mt-12">
            <a href="/order" class="btn btn-primary text-lg px-10 py-4">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span>Order Sekarang</span>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 md:py-24 bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-white text-3xl md:text-4xl font-extrabold mb-4">AC Bermasalah?</h2>
        <p class="text-white/80 text-lg mb-8">Hubungi kami sekarang dan dapatkan solusi terbaik untuk AC Anda</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/order" class="btn bg-white text-primary hover:bg-gray-100 text-lg px-8 py-4 justify-center">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span>Order Online</span>
            </a>
            <a href="https://wa.me/6281234567890" class="btn border-2 border-white text-white hover:bg-white/10 text-lg px-8 py-4 justify-center">
                <i data-lucide="phone" class="w-5 h-5"></i>
                <span>0812-3456-7890</span>
            </a>
        </div>
    </div>
</section>
@endsection
