<aside id="sidebar" class="w-64 bg-muted fixed inset-y-0 left-0 flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Logo Section -->
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if(!empty($settings['site_logo']))
                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'AC Service' }}" class="w-14 h-14 object-contain rounded-full">
                @else
                <div class="w-14 h-14 bg-primary rounded-full flex items-center justify-center">
                    <i data-lucide="wind" class="w-7 h-7 text-white"></i>
                </div>
                @endif
                <div class="min-w-0 flex-1">
                    <h1 class="text-foreground text-sm font-bold leading-tight line-clamp-2">{{ $settings['site_name'] ?? 'AC Service' }}</h1>
                    <p class="text-gray-500 text-xs">Admin Panel</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" aria-label="Close sidebar" class="lg:hidden p-2 rounded-lg hover:bg-gray-200 cursor-pointer transition-all duration-200">
                <i data-lucide="x" class="w-5 h-5 text-gray-600"></i>
            </button>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-6 py-4 space-y-6 overflow-y-auto">
        <!-- Main Menu -->
        <div>
            <h3 class="text-foreground text-xs font-semibold uppercase tracking-wider mb-3">Main</h3>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.orders.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        <span>Order Service</span>
                        <span data-pending-badge class="ml-auto w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center {{ $pendingOrderCount > 0 ? '' : 'hidden' }}">
                            {{ $pendingOrderCount > 9 ? '9+' : $pendingOrderCount }}
                        </span>
                    </div>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.reports.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                        <span>Laporan</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Data Master -->
        <div>
            <h3 class="text-foreground text-xs font-semibold uppercase tracking-wider mb-3">Data Master</h3>
            <div class="space-y-1">
                <a href="{{ route('admin.services.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.services.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                        <span>Layanan</span>
                    </div>
                </a>
                <a href="{{ route('admin.technicians.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.technicians.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="hard-hat" class="w-5 h-5"></i>
                        <span>Teknisi</span>
                    </div>
                </a>
                <a href="{{ route('admin.customers.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.customers.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Pelanggan</span>
                    </div>
                </a>
                <a href="{{ route('admin.promos.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.promos.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="ticket-percent" class="w-5 h-5"></i>
                        <span>Promo</span>
                    </div>
                </a>
                <a href="{{ route('admin.portfolios.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.portfolios.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="images" class="w-5 h-5"></i>
                        <span>Portfolio</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Pengaturan -->
        <div class="mb-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Pengaturan</p>
            <div class="space-y-1">
                <a href="{{ route('admin.settings.index') }}" class="block">
                    <div class="nav-item {{ request()->routeIs('admin.settings.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                        <span>Settings</span>
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- User Profile -->
    <div class="px-6 pb-6 mt-auto border-t border-gray-200 pt-4">
        @auth
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-foreground text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-gray-500 text-xs truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-error hover:bg-error/10 rounded-lg transition-colors">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span>Logout</span>
            </button>
        </form>
        @endauth
    </div>
</aside>
