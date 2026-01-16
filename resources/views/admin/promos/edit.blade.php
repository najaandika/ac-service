@extends('layouts.app')

@section('title', 'Edit Promo - Admin')
@section('page-title', 'Edit Promo')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header :title="'Edit Promo: ' . $promo->code" subtitle="Ubah detail promo" />

    <!-- Usage Stats -->
    @if($promo->usage_count > 0)
    <x-cards.card>
        <div class="flex items-center gap-4 text-sm">
            <div class="flex items-center gap-2 text-gray-600">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span>Sudah digunakan <strong class="text-foreground">{{ $promo->usage_count }} kali</strong></span>
            </div>
        </div>
    </x-cards.card>
    @endif

    <form action="{{ route('admin.promos.update', $promo) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Info -->
        <x-cards.card title="Informasi Promo">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-forms.input 
                        name="code" 
                        label="Kode Promo" 
                        placeholder="Contoh: HEMAT10"
                        :value="$promo->code"
                        :required="true"
                        class="uppercase"
                    />
                    <p class="text-xs text-gray-500 -mt-3">Hanya huruf, angka, dan underscore</p>
                </div>
                
                <x-forms.input 
                    name="name" 
                    label="Nama Promo" 
                    placeholder="Contoh: Promo Customer Baru"
                    :value="$promo->name"
                    :required="true"
                />
                
                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="description" 
                        label="Deskripsi" 
                        placeholder="Deskripsi singkat promo (opsional)"
                        :value="$promo->description"
                        :rows="2"
                    />
                </div>
            </div>
        </x-cards.card>

        <!-- Discount Settings -->
        <x-cards.card title="Pengaturan Diskon">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="promoDiscountForm" data-initial-type="{{ old('type', $promo->type) }}">
                <x-forms.select 
                    name="type" 
                    label="Tipe Diskon" 
                    :required="true"
                    :selected="$promo->type"
                    x-model="discountType"
                    :options="['percentage' => 'Persentase (%)', 'fixed' => 'Nominal (Rp)']"
                    placeholder="Pilih tipe..."
                />
                
                <div>
                    <label for="value" class="block text-foreground text-sm font-medium mb-2">
                        Nilai Diskon <span class="text-error">*</span>
                    </label>
                    
                    <template x-if="discountType === 'percentage'">
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-600 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">%</span>
                            <input type="number" name="value" id="value" value="{{ old('value', $promo->value) }}" min="1" max="100" step="any" class="form-input w-full rounded-l-none @error('value') border-red-500 @enderror" placeholder="10" required autocomplete="off">
                        </div>
                    </template>
                    
                    <template x-if="discountType === 'fixed'">
                        <x-forms.currency-input name="value" placeholder="25.000" :value="old('value', $promo->value)" />
                    </template>
                    
                    <p class="text-xs text-gray-500 mt-1" x-text="discountType === 'percentage' ? 'Masukkan angka persen, misal 10 untuk 10%' : 'Masukkan nominal, misal 25.000'"></p>
                    @error('value')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <x-forms.currency-input name="min_order" label="Minimum Order" placeholder="100.000" :value="old('min_order', $promo->min_order)" />
                    <p class="text-xs text-gray-500 -mt-3">Kosongkan jika tanpa minimum order</p>
                </div>
                
                <div x-show="discountType === 'percentage'">
                    <x-forms.currency-input name="max_discount" label="Maksimum Potongan" placeholder="50.000" :value="old('max_discount', $promo->max_discount)" />
                    <p class="text-xs text-gray-500 -mt-3">Batas maksimal potongan untuk diskon %</p>
                </div>
            </div>
        </x-cards.card>

        <!-- Restrictions -->
        <x-cards.card title="Batasan Penggunaan">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-forms.input 
                    name="start_date" 
                    type="date"
                    label="Tanggal Mulai" 
                    :value="$promo->start_date?->format('Y-m-d')"
                />
                
                <x-forms.input 
                    name="end_date" 
                    type="date"
                    label="Tanggal Berakhir" 
                    :value="$promo->end_date?->format('Y-m-d')"
                />
                
                <div>
                    <label for="service_id" class="block text-foreground text-sm font-medium mb-2">Khusus Layanan</label>
                    <select name="service_id" id="service_id" class="form-input w-full @error('service_id') border-error @enderror">
                        <option value="">Semua Layanan</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id', $promo->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <x-forms.input 
                        name="usage_limit" 
                        type="number"
                        label="Batas Penggunaan" 
                        placeholder="Kosongkan untuk unlimited"
                        :value="$promo->usage_limit"
                        min="1"
                    />
                    <p class="text-xs text-gray-500 -mt-3">Sudah digunakan: {{ $promo->usage_count }} kali</p>
                </div>
                
                <div class="md:col-span-2">
                    <label for="is_active" class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promo->is_active) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm font-medium text-gray-700">Aktifkan promo ini</span>
                    </label>
                </div>
            </div>
        </x-cards.card>

        <!-- Actions -->
        <x-forms.form-actions 
            cancelUrl="{{ route('admin.promos.index') }}" 
            submitText="Simpan Perubahan" 
        />
    </form>
</div>
@endsection
