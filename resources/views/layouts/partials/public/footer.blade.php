<!-- Footer -->
<footer id="kontak" class="bg-foreground text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    @if(!empty($settings['site_logo']))
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'AC Service' }}" class="w-10 h-10 object-contain rounded-full">
                    @else
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                        <i data-lucide="wind" class="w-5 h-5 text-white"></i>
                    </div>
                    @endif
                    <span class="text-white text-lg font-bold">{{ $settings['site_name'] ?? 'AC Service' }}</span>
                </div>
                <p class="text-gray-300 mb-4 max-w-sm">
                    {{ $settings['site_description'] ?? 'Jasa service AC profesional dengan teknisi berpengalaman. Melayani area Jabodetabek dan sekitarnya.' }}
                </p>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Layanan</h4>
                <ul class="space-y-2 text-gray-300">
                    <li><a href="/layanan/cuci-ac" class="hover:text-primary">Cuci AC</a></li>
                    <li><a href="/layanan/isi-freon" class="hover:text-primary">Isi Freon</a></li>
                    <li><a href="/layanan/perbaikan-ac" class="hover:text-primary">Perbaikan</a></li>
                    <li><a href="/layanan/instalasi-ac" class="hover:text-primary">Instalasi</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-gray-300">
                    @if(!empty($settings['phone']))
                    <li class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>{{ $settings['phone'] }}</span>
                    </li>
                    @endif
                    @if(!empty($settings['whatsapp']))
                    <li class="flex items-center gap-2">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" class="hover:text-primary">WhatsApp</a>
                    </li>
                    @endif
                    @if(!empty($settings['email']))
                    <li class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span>{{ $settings['email'] }}</span>
                    </li>
                    @endif
                    @if(!empty($settings['address']))
                    <li class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 mt-0.5"></i>
                        <span>{{ $settings['address'] }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'AC Service' }}. All rights reserved.
        </div>
    </div>
</footer>
