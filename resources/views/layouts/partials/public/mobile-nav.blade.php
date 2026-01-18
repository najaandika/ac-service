<!-- Mobile Sidebar -->
<aside id="mobile-sidebar" class="w-64 bg-muted fixed inset-y-0 left-0 flex flex-col z-50 transform -translate-x-full transition-transform duration-300 md:hidden">
    <!-- Close Button -->
    <div class="flex justify-end px-4 py-3">
        <button onclick="toggleMobileSidebar()" class="p-2 rounded-lg hover:bg-gray-200 cursor-pointer transition-all duration-200" aria-label="Tutup menu">
            <i data-lucide="x" class="w-5 h-5 text-gray-600"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-6 py-4 overflow-y-auto">
        <div class="space-y-1" id="mobile-nav-links">
            <a href="/" data-nav="home" class="block mobile-nav-link">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="home" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Beranda</span>
                </div>
            </a>
            <a href="/#layanan" data-nav="layanan" class="block mobile-nav-link">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="wrench" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Layanan</span>
                </div>
            </a>
            <a href="{{ route('testimoni.index') }}" data-nav="testimoni" class="block mobile-nav-link">
                <div class="nav-item {{ ($navActive['testimoni'] ?? false) ? 'nav-item-active' : 'nav-item-inactive group' }}">
                    <i data-lucide="star" class="w-5 h-5 {{ ($navActive['testimoni'] ?? false) ? '' : 'text-gray-600 group-hover:text-primary' }}"></i>
                    <span class="{{ ($navActive['testimoni'] ?? false) ? '' : 'group-hover:text-primary' }}">Testimoni</span>
                </div>
            </a>
            <a href="{{ route('gallery') }}" data-nav="gallery" class="block mobile-nav-link">
                <div class="nav-item {{ ($navActive['gallery'] ?? false) ? 'nav-item-active bg-primary/10 border-l-4 border-primary' : 'nav-item-inactive group' }}">
                    <i data-lucide="images" class="w-5 h-5 {{ ($navActive['gallery'] ?? false) ? 'text-primary' : 'text-gray-600 group-hover:text-primary' }}"></i>
                    <span class="{{ ($navActive['gallery'] ?? false) ? 'text-primary font-semibold' : 'group-hover:text-primary' }}">Gallery</span>
                </div>
            </a>
            <a href="{{ route('faq') }}" data-nav="faq" class="block mobile-nav-link">
                <div class="nav-item {{ ($navActive['faq'] ?? false) ? 'nav-item-active bg-primary/10 border-l-4 border-primary' : 'nav-item-inactive group' }}">
                    <i data-lucide="help-circle" class="w-5 h-5 {{ ($navActive['faq'] ?? false) ? 'text-primary' : 'text-gray-600 group-hover:text-primary' }}"></i>
                    <span class="{{ ($navActive['faq'] ?? false) ? 'text-primary font-semibold' : 'group-hover:text-primary' }}">FAQ</span>
                </div>
            </a>
            
            <!-- Separator -->
            <div class="border-t border-gray-200 my-3"></div>
            
            <a href="{{ route('order.track') }}" data-nav="track" class="block mobile-nav-link">
                <div class="nav-item {{ ($navActive['track'] ?? false) ? 'nav-item-active' : 'nav-item-inactive group' }}">
                    <i data-lucide="search" class="w-5 h-5 {{ ($navActive['track'] ?? false) ? '' : 'text-gray-600 group-hover:text-primary' }}"></i>
                    <span class="{{ ($navActive['track'] ?? false) ? '' : 'group-hover:text-primary' }}">Lacak Order</span>
                </div>
            </a>
            <a href="{{ route('order.create') }}" data-nav="order" class="block mobile-nav-link">
                <div class="nav-item {{ ($navActive['order'] ?? false) ? 'nav-item-active' : 'nav-item-inactive group' }}">
                    <i data-lucide="calendar" class="w-5 h-5 {{ ($navActive['order'] ?? false) ? '' : 'text-gray-600 group-hover:text-primary' }}"></i>
                    <span class="{{ ($navActive['order'] ?? false) ? '' : 'group-hover:text-primary' }}">Order Sekarang</span>
                </div>
            </a>
        </div>
    </nav>
</aside>

<!-- Overlay -->
<div id="mobile-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

