@extends('layouts.app')

@section('title', 'Tambah Layanan - Admin')
@section('page-title', 'Tambah Layanan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Tambah Layanan Baru</h1>
            <p class="text-gray-600">Isi form untuk menambahkan layanan</p>
        </div>
    </x-cards.card>

    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Basic Info -->
        <x-cards.card title="Informasi Dasar">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full @error('name') border-red-500 @enderror" placeholder="Contoh: Cuci AC" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="form-input w-full @error('description') border-red-500 @enderror" placeholder="Jelaskan layanan ini..." required>{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 45) }}" min="15" class="form-input w-full @error('duration_minutes') border-red-500 @enderror" required>
                    @error('duration_minutes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon <span class="text-red-500">*</span></label>
                    <select name="icon" class="form-select w-full @error('icon') border-red-500 @enderror" required>
                        <option value="wind" {{ old('icon') === 'wind' ? 'selected' : '' }}>🌀 Wind (Angin)</option>
                        <option value="snowflake" {{ old('icon') === 'snowflake' ? 'selected' : '' }}>❄️ Snowflake (Salju)</option>
                        <option value="wrench" {{ old('icon') === 'wrench' ? 'selected' : '' }}>🔧 Wrench (Obeng)</option>
                        <option value="thermometer" {{ old('icon') === 'thermometer' ? 'selected' : '' }}>🌡️ Thermometer</option>
                        <option value="settings" {{ old('icon') === 'settings' ? 'selected' : '' }}>⚙️ Settings (Gear)</option>
                        <option value="zap" {{ old('icon') === 'zap' ? 'selected' : '' }}>⚡ Zap (Listrik)</option>
                        <option value="droplets" {{ old('icon') === 'droplets' ? 'selected' : '' }}>💧 Droplets (Tetes)</option>
                        <option value="fan" {{ old('icon') === 'fan' ? 'selected' : '' }}>🌪️ Fan (Kipas)</option>
                    </select>
                    @error('icon')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm font-medium text-gray-700">Aktifkan layanan ini</span>
                    </label>
                </div>
            </div>
        </x-cards.card>

        <!-- Features -->
        <x-cards.card title="Fitur Layanan">
            <p class="text-sm text-gray-500 mb-4">Tambahkan poin-poin yang termasuk dalam layanan ini</p>
            <div class="space-y-3" id="features-container">
                @for($i = 0; $i < 6; $i++)
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-success flex-shrink-0"></i>
                    <input type="text" name="features[]" value="{{ old('features.' . $i) }}" class="form-input flex-1" placeholder="Contoh: Pengecekan kebocoran pipa">
                </div>
                @endfor
            </div>
        </x-cards.card>

        <!-- Pricing -->
        <x-cards.card title="Harga per Kapasitas AC">
            <p class="text-sm text-gray-500 mb-4">Tentukan harga untuk setiap kapasitas AC</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($capacityLabels as $cap => $label)
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm z-10 pointer-events-none">Rp</span>
                        <input type="text" inputmode="numeric" name="prices[{{ $cap }}]" value="{{ old('prices.' . $cap, 0) }}" placeholder="0" class="form-input w-full !pl-12 @error('prices.' . $cap) border-red-500 @enderror">
                    </div>
                </div>
                @endforeach
            </div>
            @error('prices')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </x-cards.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Layanan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    @vite('resources/js/utils/price-formatter.js')
@endpush
