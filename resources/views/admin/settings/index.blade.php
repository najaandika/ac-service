@extends('layouts.app')

@section('title', 'Pengaturan - Admin')
@section('page-title', 'Pengaturan')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'profile' }">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Pengaturan</h1>
            <p class="text-gray-600">Kelola pengaturan website dan informasi bisnis</p>
        </div>
    </x-cards.card>

    <!-- Alerts -->
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-200 overflow-x-auto">
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors">
                <i data-lucide="building-2" class="w-4 h-4"></i>
                Profil Bisnis
            </button>
            <button @click="activeTab = 'appearance'" :class="activeTab === 'appearance' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors">
                <i data-lucide="palette" class="w-4 h-4"></i>
                Tampilan
            </button>
            <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors">
                <i data-lucide="phone" class="w-4 h-4"></i>
                Kontak
            </button>
            <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors">
                <i data-lucide="share-2" class="w-4 h-4"></i>
                Media Sosial
            </button>
            <button @click="activeTab = 'notification'" :class="activeTab === 'notification' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors">
                <i data-lucide="bell" class="w-4 h-4"></i>
                Notifikasi
            </button>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bisnis <span class="text-red-500">*</span></label>
                        <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? 'AC Service' }}" class="form-input w-full" autocomplete="organization" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Tentang Kami</label>
                        <textarea name="site_description" id="site_description" rows="4" class="form-input w-full" placeholder="Deskripsi singkat tentang bisnis Anda...">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center gap-4">
                            @if(!empty($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="w-16 h-16 object-contain bg-gray-100 rounded-full">
                            @else
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="site_logo" id="site_logo" accept="image/jpeg,image/png,image/jpg,image/svg+xml" class="form-input w-full">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, SVG. Maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appearance Tab -->
            <div x-show="activeTab === 'appearance'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-1">Judul Hero</label>
                        <input type="text" name="hero_title" id="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="form-input w-full" placeholder="Service AC Profesional & Terpercaya" autocomplete="off">
                    </div>
                    
                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle Hero</label>
                        <textarea name="hero_subtitle" id="hero_subtitle" rows="2" class="form-input w-full" placeholder="Teknisi berpengalaman, harga transparan..." autocomplete="off">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Hero</label>
                        <div class="flex items-start gap-4">
                            @if(!empty($settings['hero_image']))
                            <img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="Hero" class="w-32 h-20 object-cover bg-gray-100 rounded-lg">
                            @else
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="hero_image" id="hero_image" accept="image/jpeg,image/png,image/jpg" class="form-input w-full">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 5MB. Ukuran rekomendasi: 800x600 px.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div x-show="activeTab === 'contact'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ $settings['phone'] ?? '' }}" class="form-input w-full" placeholder="08123456789" autocomplete="tel">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ $settings['email'] ?? '' }}" class="form-input w-full" placeholder="info@acservice.com" autocomplete="email">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" id="address" rows="2" class="form-input w-full" placeholder="Jl. Contoh No. 123, Jakarta" autocomplete="street-address">{{ $settings['address'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label for="operating_hours" class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                        <input type="text" name="operating_hours" id="operating_hours" value="{{ $settings['operating_hours'] ?? '' }}" class="form-input w-full" placeholder="Senin - Sabtu, 08:00 - 17:00" autocomplete="off">
                    </div>
                </div>
            </div>

            <!-- Social Tab -->
            <div x-show="activeTab === 'social'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <i data-lucide="message-circle" class="w-4 h-4 text-green-500"></i>
                                WhatsApp
                            </span>
                        </label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" class="form-input w-full" placeholder="08123456789" autocomplete="tel">
                        <p class="text-xs text-gray-500 mt-1">Nomor untuk tombol WhatsApp</p>
                    </div>
                    
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <i data-lucide="instagram" class="w-4 h-4 text-pink-500"></i>
                                Instagram
                            </span>
                        </label>
                        <input type="text" name="instagram" id="instagram" value="{{ $settings['instagram'] ?? '' }}" class="form-input w-full" placeholder="@username" autocomplete="off">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <i data-lucide="music" class="w-4 h-4 text-gray-800"></i>
                                TikTok
                            </span>
                        </label>
                        <input type="text" name="tiktok" id="tiktok" value="{{ $settings['tiktok'] ?? '' }}" class="form-input w-full" placeholder="@username atau https://tiktok.com/@username">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                Google Maps (Review)
                            </span>
                        </label>
                        <input type="url" name="google_maps_url" id="google_maps_url" value="{{ $settings['google_maps_url'] ?? '' }}" class="form-input w-full" placeholder="https://g.page/r/xxx/review">
                        <p class="text-xs text-gray-500 mt-1">Link untuk minta review di Google Maps. Dapatkan dari: Google Business → Minta Ulasan</p>
                    </div>
                </div>
            </div>

            <!-- Notification Tab -->
            <div x-show="activeTab === 'notification'" x-cloak class="space-y-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Catatan</p>
                            <p class="text-xs text-yellow-700 mt-1">Notifikasi audio akan berbunyi saat ada order baru masuk. Pastikan browser mengizinkan autoplay audio.</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div>
                            <label for="notification_enabled" class="block text-sm font-medium text-gray-700">Aktifkan Notifikasi Audio</label>
                            <p class="text-xs text-gray-500 mt-1">Putar suara saat ada order baru</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notification_enabled" id="notification_enabled" value="1" class="sr-only peer" {{ ($settings['notification_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div>
                        <label for="notification_audio" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center gap-2">
                                <i data-lucide="music" class="w-4 h-4 text-primary"></i>
                                Audio Notifikasi Custom
                            </span>
                        </label>
                        <div class="flex items-center gap-4">
                            @if(!empty($settings['notification_audio']))
                            <div class="flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg text-sm">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Audio tersimpan</span>
                                <button type="button" onclick="previewAudio('{{ asset('storage/' . $settings['notification_audio']) }}')" class="ml-2 px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                    🔊 Preview
                                </button>
                            </div>
                            @else
                            <div class="flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">
                                <i data-lucide="volume-2" class="w-4 h-4"></i>
                                <span>Menggunakan suara default</span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <input type="file" name="notification_audio" id="notification_audio" accept="audio/mpeg,audio/wav,audio/mp3" class="form-input w-full">
                            <p class="text-xs text-gray-500 mt-1">Format: MP3, WAV. Maksimal 1MB. Kosongkan jika ingin menggunakan suara default.</p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="notification_interval" class="block text-sm font-medium text-gray-700 mb-1">Interval Pengecekan (detik)</label>
                        <input type="number" name="notification_interval" id="notification_interval" value="{{ $settings['notification_interval'] ?? '30' }}" min="10" max="120" class="form-input w-32">
                        <p class="text-xs text-gray-500 mt-1">Seberapa sering mengecek order baru. Minimum 10 detik, maksimum 120 detik.</p>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewAudio(url) {
    const audio = new Audio(url);
    audio.play().catch(err => {
        alert('Tidak dapat memutar audio. Pastikan file valid.');
    });
}
</script>
@endpush
@endsection
