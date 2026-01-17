<!-- Header -->
<header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md z-30 border-b border-gray-100">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="{{ $settings['site_name'] ?? 'Tunggal Jaya Tehnik' }}" class="h-14 w-auto object-contain" width="56" height="56">
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="/#layanan" class="text-gray-600 hover:text-primary transition-colors">Layanan</a>
                <a href="/#kenapa-kami" class="text-gray-600 hover:text-primary transition-colors">Kenapa Kami</a>
                <a href="/testimoni" class="text-gray-600 hover:text-primary transition-colors">Testimoni</a>
                <a href="/gallery" class="text-gray-600 hover:text-primary transition-colors">Gallery</a>
                <a href="/faq" class="text-gray-600 hover:text-primary transition-colors">FAQ</a>
                <a href="/#kontak" class="text-gray-600 hover:text-primary transition-colors">Kontak</a>
            </div>
            
            <!-- Desktop CTA -->
            <div class="hidden md:flex items-center gap-3">
                <a href="/track" class="text-gray-600 hover:text-primary transition-colors">Lacak Order</a>
                <a href="/order" class="btn btn-primary">Order Sekarang</a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex md:hidden items-center">
                <button onclick="toggleMobileSidebar()" class="p-2 text-gray-600 hover:text-primary rounded-lg hover:bg-gray-100" aria-label="Open menu">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </nav>
</header>
