@extends('layouts.app')

@section('title', 'Edit Layanan - Admin')
@section('page-title', 'Edit Layanan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header :title="'Edit: ' . $service->name" subtitle="Update informasi layanan" />

    <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Info -->
        <x-cards.card title="Informasi Dasar">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-forms.input 
                        name="name" 
                        label="Nama Layanan" 
                        placeholder="Contoh: Cuci AC"
                        :value="$service->name"
                        :required="true"
                    />
                </div>
                
                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="description" 
                        label="Deskripsi" 
                        placeholder="Jelaskan layanan ini..."
                        :value="$service->description"
                        :rows="3"
                        :required="true"
                    />
                </div>
                
                <x-forms.input 
                    name="duration_minutes" 
                    type="number"
                    label="Durasi (menit)" 
                    :value="$service->duration_minutes"
                    :required="true"
                    min="15"
                />
                
                <x-forms.select 
                    name="icon" 
                    label="Icon" 
                    :required="true"
                    :selected="$service->icon"
                    :options="[
                        'wind' => '🌀 Wind (Angin)',
                        'snowflake' => '❄️ Snowflake (Salju)',
                        'wrench' => '🔧 Wrench (Obeng)',
                        'thermometer' => '🌡️ Thermometer',
                        'settings' => '⚙️ Settings (Gear)',
                        'zap' => '⚡ Zap (Listrik)',
                        'droplets' => '💧 Droplets (Tetes)',
                        'fan' => '🌪️ Fan (Kipas)',
                    ]"
                    placeholder="Pilih icon..."
                />
                
                <div class="md:col-span-2">
                    <label for="is_active" class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm font-medium text-gray-700">Aktifkan layanan ini</span>
                    </label>
                </div>
            </div>
        </x-cards.card>

        <!-- Features -->
        <x-cards.card title="Fitur Layanan">
            <p class="text-sm text-gray-500 mb-4">Tambahkan poin-poin yang termasuk dalam layanan ini</p>
            @php
                $features = old('features', $service->features ?? []);
                $featureCount = max(6, count($features));
            @endphp
            <div class="space-y-3" id="features-container">
                @for($i = 0; $i < $featureCount; $i++)
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-success flex-shrink-0"></i>
                    <label for="feature_{{ $i }}" class="sr-only">Fitur {{ $i + 1 }}</label>
                    <input type="text" name="features[]" id="feature_{{ $i }}" value="{{ $features[$i] ?? '' }}" class="form-input flex-1" placeholder="Contoh: Pengecekan kebocoran pipa" aria-label="Fitur layanan {{ $i + 1 }}">
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
                    <x-forms.currency-input 
                        name="prices[{{ $cap }}]" 
                        :label="$label"
                        :value="$priceMap[$cap] ?? 0"
                    />
                </div>
                @endforeach
            </div>
            @error('prices')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </x-cards.card>

        <!-- Actions -->
        <x-forms.form-actions 
            cancelUrl="{{ route('admin.services.index') }}" 
            submitText="Update Layanan" 
        />
    </form>
</div>
@endsection
