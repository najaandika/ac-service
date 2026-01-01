<!-- Mobile Sidebar -->
<aside id="mobile-sidebar" class="w-64 bg-muted fixed inset-y-0 left-0 flex flex-col z-50 transform -translate-x-full transition-transform duration-300 md:hidden">
    <!-- Close Button -->
    <div class="flex justify-end px-4 py-3">
        <button onclick="toggleMobileSidebar()" class="p-2 rounded-lg hover:bg-gray-200 cursor-pointer transition-all duration-200">
            <i data-lucide="x" class="w-5 h-5 text-gray-600"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-6 py-4 overflow-y-auto">
        <div class="space-y-1">
            <a href="/" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="home" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Beranda</span>
                </div>
            </a>
            <a href="/#layanan" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="wrench" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Layanan</span>
                </div>
            </a>
            <a href="/#kenapa-kami" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="award" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Kenapa Kami</span>
                </div>
            </a>
            <a href="/testimoni" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="star" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Testimoni</span>
                </div>
            </a>
            <a href="/#cara-order" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="list-ordered" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Cara Order</span>
                </div>
            </a>
            <a href="/#kontak" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="phone" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Kontak</span>
                </div>
            </a>
            
            <!-- Separator -->
            <div class="border-t border-gray-200 my-3"></div>
            
            <a href="/track" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-inactive group">
                    <i data-lucide="search" class="w-5 h-5 text-gray-600 group-hover:text-primary"></i>
                    <span class="group-hover:text-primary">Lacak Order</span>
                </div>
            </a>
            <a href="/order" onclick="toggleMobileSidebar()" class="block">
                <div class="nav-item nav-item-active">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span>Order Sekarang</span>
                </div>
            </a>
        </div>
    </nav>

    <!-- WhatsApp CTA -->
    <div class="p-6 border-t border-gray-200">
        @if(!empty($settings['whatsapp']))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" target="_blank" class="flex items-center gap-3 text-gray-600 hover:text-primary transition-colors">
            <i data-lucide="message-circle" class="w-5 h-5"></i>
            <span class="text-sm">Chat via WhatsApp</span>
        </a>
        @endif
    </div>
</aside>

<!-- Overlay -->
<div id="mobile-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>
