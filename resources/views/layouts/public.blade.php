<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AC Service - Jasa Service AC Profesional')</title>
    <meta name="description" content="@yield('description', 'Jasa service AC profesional, cuci AC, isi freon, perbaikan, dan instalasi. Teknisi berpengalaman dengan harga terjangkau.')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Vite Assets (includes Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-white">

@include('layouts.partials.public.mobile-nav')

@include('layouts.partials.public.header')

<!-- Main Content -->
<main class="pt-16">
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
