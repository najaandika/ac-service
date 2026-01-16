<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($settings['site_name'] ?? 'AC Service') . ' - Jasa Service AC Profesional')</title>
    <meta name="description" content="@yield('description', $settings['site_description'] ?? 'Jasa service AC profesional, cuci AC, isi freon, perbaikan, dan instalasi. Teknisi berpengalaman dengan harga terjangkau.')">

    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', ($settings['site_name'] ?? 'AC Service') . ' - Jasa Service AC Profesional')">
    <meta property="og:description" content="@yield('description', $settings['site_description'] ?? 'Jasa service AC profesional, cuci AC, isi freon, perbaikan, dan instalasi.')">
    @if(!empty($settings['site_logo']))
    <meta property="og:image" content="{{ asset('storage/' . $settings['site_logo']) }}">
    @endif
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', ($settings['site_name'] ?? 'AC Service') . ' - Jasa Service AC Profesional')">
    <meta name="twitter:description" content="@yield('description', $settings['site_description'] ?? 'Jasa service AC profesional, cuci AC, isi freon, perbaikan, dan instalasi.')">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Favicon -->
    @if(!empty($settings['site_logo']))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings['site_logo']) }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/' . $settings['site_logo']) }}">
    @else
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>❄️</text></svg>">
    @endif
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#0891B2">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Vite Assets (includes Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('head')
</head>
<body class="font-sans bg-white min-h-screen flex flex-col w-full overflow-x-hidden">

@include('layouts.partials.public.mobile-nav')

@include('layouts.partials.public.header')

<!-- Main Content -->
<main class="flex-1 pt-16 w-full">
    @yield('content')
</main>

@include('layouts.partials.public.footer')

{{-- Floating WhatsApp Button --}}
<x-floating-whatsapp :settings="$settings ?? []" />

@push('scripts')
    @vite('resources/js/layouts/public.js')
@endpush

@stack('scripts')
</body>
</html>
