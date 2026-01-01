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
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bisnis <span class="text-red-500">*</span></label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'AC Service' }}" class="form-input w-full" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tentang Kami</label>
                        <textarea name="site_description" rows="4" class="form-input w-full" placeholder="Deskripsi singkat tentang bisnis Anda...">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center gap-4">
                            @if(!empty($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="w-16 h-16 object-contain bg-gray-100 rounded-full">
                            @else
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="site_logo" accept="image/jpeg,image/png,image/jpg,image/svg+xml" class="form-input w-full">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Hero</label>
                        <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="form-input w-full" placeholder="Service AC Profesional & Terpercaya">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle Hero</label>
                        <textarea name="hero_subtitle" rows="2" class="form-input w-full" placeholder="Teknisi berpengalaman, harga transparan...">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Hero</label>
                        <div class="flex items-start gap-4">
                            @if(!empty($settings['hero_image']))
                            <img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="Hero" class="w-32 h-20 object-cover bg-gray-100 rounded-lg">
                            @else
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="hero_image" accept="image/jpeg,image/png,image/jpg" class="form-input w-full">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="form-input w-full" placeholder="08123456789">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="form-input w-full" placeholder="info@acservice.com">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" rows="2" class="form-input w-full" placeholder="Jl. Contoh No. 123, Jakarta">{{ $settings['address'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                        <input type="text" name="operating_hours" value="{{ $settings['operating_hours'] ?? '' }}" class="form-input w-full" placeholder="Senin - Sabtu, 08:00 - 17:00">
                    </div>
                </div>
            </div>

            <!-- Social Tab -->
            <div x-show="activeTab === 'social'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <i data-lucide="message-circle" class="w-4 h-4 text-green-500"></i>
                                WhatsApp
                            </span>
                        </label>
                        <input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" class="form-input w-full" placeholder="08123456789">
                        <p class="text-xs text-gray-500 mt-1">Nomor untuk tombol WhatsApp</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <i data-lucide="instagram" class="w-4 h-4 text-pink-500"></i>
                                Instagram
                            </span>
                        </label>
                        <input type="text" name="instagram" value="{{ $settings['instagram'] ?? '' }}" class="form-input w-full" placeholder="@username">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center gap-2">
                                <i data-lucide="music" class="w-4 h-4 text-gray-800"></i>
                                TikTok
                            </span>
                        </label>
                        <input type="text" name="tiktok" value="{{ $settings['tiktok'] ?? '' }}" class="form-input w-full" placeholder="@username atau https://tiktok.com/@username">
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
@endsection
