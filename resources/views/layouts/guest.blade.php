<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AC Service')</title>
    <meta name="description" content="@yield('description', 'AC Service admin dashboard.')">

    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gradient-to-br from-primary/10 via-white to-primary/5 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Tunggal Jaya Tehnik" class="h-24 w-auto object-contain mx-auto mb-4">
        <h1 class="text-foreground text-2xl font-bold">Tunggal Jaya Tehnik</h1>
        <p class="text-gray-500 text-sm">Admin Dashboard</p>
    </div>

    @yield('content')

    <!-- Footer -->
    <p class="text-center text-gray-500 text-sm mt-6">
        &copy; {{ date('Y') }} AC Service. All rights reserved.
    </p>
</div>

@stack('scripts')
</body>
</html>
