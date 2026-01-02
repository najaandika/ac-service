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

<script>
// Handle mobile nav active state based on current URL and hash
document.addEventListener('DOMContentLoaded', function() {
    updateMobileNavActive();
    window.addEventListener('hashchange', updateMobileNavActive);
});

function updateMobileNavActive() {
    const path = window.location.pathname;
    const hash = window.location.hash;
    const links = document.querySelectorAll('.mobile-nav-link');
    
    links.forEach(link => {
        const navItem = link.querySelector('.nav-item');
        const icon = link.querySelector('i');
        const span = link.querySelector('span');
        const navKey = link.dataset.nav;
        
        let isActive = false;
        
        // Check active state
        if (navKey === 'home' && path === '/' && !hash) {
            isActive = true;
        } else if (navKey === 'layanan' && (hash === '#layanan' || path.startsWith('/layanan/'))) {
            isActive = true;
        } else if (navKey === 'testimoni' && path.startsWith('/testimoni')) {
            isActive = true;
        } else if (navKey === 'track' && path === '/track') {
            isActive = true;
        } else if (navKey === 'order' && path === '/order') {
            isActive = true;
        }
        
        // Update classes
        if (isActive) {
            navItem.classList.remove('nav-item-inactive', 'group');
            navItem.classList.add('nav-item-active');
            icon.classList.remove('text-gray-600', 'group-hover:text-primary');
            span.classList.remove('group-hover:text-primary');
        } else {
            navItem.classList.remove('nav-item-active');
            navItem.classList.add('nav-item-inactive', 'group');
            icon.classList.add('text-gray-600', 'group-hover:text-primary');
            span.classList.add('group-hover:text-primary');
        }
    });
}

// Update when clicking nav link
document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', function() {
        setTimeout(() => {
            toggleMobileSidebar();
            updateMobileNavActive();
        }, 100);
    });
});
</script>
