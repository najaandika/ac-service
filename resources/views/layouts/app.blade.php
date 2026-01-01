<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AC Service') - Dashboard</title>
    <meta name="description" content="AC Service dashboard for managing service orders, technicians, and customer data.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Chart.js (only load on pages that need it) -->
    @stack('head-scripts')
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-white min-h-screen overflow-x-hidden">

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<div class="flex min-h-screen">
    <!-- SIDEBAR -->
    @include('layouts.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 lg:ml-64 p-4 md:p-5 bg-white min-h-screen overflow-x-hidden">
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between mb-6 bg-muted rounded-[var(--radius-card)] p-4">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" aria-label="Open menu" class="p-2 rounded-lg hover:bg-gray-200 cursor-pointer transition-all duration-200">
                    <i data-lucide="menu" class="w-6 h-6 text-foreground"></i>
                </button>
                <h1 class="text-foreground font-semibold text-lg">@yield('page-title', 'Dashboard')</h1>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="relative p-2 rounded-lg hover:bg-gray-200 transition-all duration-200">
                <i data-lucide="bell" class="w-5 h-5 text-foreground"></i>
                @if($pendingOrderCount > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ $pendingOrderCount > 9 ? '9+' : $pendingOrderCount }}
                </span>
                @endif
            </a>
        </div>

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
