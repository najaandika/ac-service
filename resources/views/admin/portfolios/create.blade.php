@extends('layouts.app')

@section('title', 'Tambah Portfolio - Admin')
@section('page-title', 'Tambah Portfolio')

@section('content')
<div class="space-y-6">
    <x-page-header title="Tambah Portfolio" subtitle="Upload foto hasil kerja Before/After" />

    <x-cards.card>
        <form action="{{ route('admin.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-forms.input 
                        name="title" 
                        label="Judul Portfolio" 
                        placeholder="Contoh: Cuci AC Rumah Bpk Ahmad"
                        required 
                    />

                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan (Opsional)</label>
                        <select name="service_id" id="service_id" class="form-input">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" class="form-input" placeholder="Ceritakan sedikit tentang pekerjaan ini...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-forms.input 
                            type="number"
                            name="sort_order" 
                            label="Urutan" 
                            value="0"
                        />
                        <div class="flex items-end pb-1">
                            <label for="is_published" class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_published" id="is_published" value="1" checked class="form-checkbox">
                                <span class="text-sm font-medium text-gray-700">Publikasikan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Uploads -->
                <div class="space-y-4">
                    <div x-data="{ preview: null }">
                        <label for="before_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Before <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed rounded-xl p-4 text-center transition-colors"
                             :class="preview ? 'border-green-400 bg-green-50' : 'border-gray-300 hover:border-primary'">
                            <input type="file" name="before_image" id="before_image" accept="image/*" class="hidden" required
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <label for="before_image" class="cursor-pointer block">
                                <template x-if="!preview">
                                    <div>
                                        <i data-lucide="upload-cloud" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">Klik untuk upload foto <strong>BEFORE</strong></p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP (max 2MB)</p>
                                    </div>
                                </template>
                                <template x-if="preview">
                                    <div>
                                        <img :src="preview" class="w-full h-32 object-cover rounded-lg mb-2">
                                        <p class="text-sm text-green-600 font-medium">✓ Foto Before dipilih</p>
                                        <p class="text-xs text-gray-400">Klik untuk ganti</p>
                                    </div>
                                </template>
                            </label>
                        </div>
                        @error('before_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ preview: null }">
                        <label for="after_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto After <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed rounded-xl p-4 text-center transition-colors"
                             :class="preview ? 'border-green-400 bg-green-50' : 'border-gray-300 hover:border-primary'">
                            <input type="file" name="after_image" id="after_image" accept="image/*" class="hidden" required
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <label for="after_image" class="cursor-pointer block">
                                <template x-if="!preview">
                                    <div>
                                        <i data-lucide="upload-cloud" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">Klik untuk upload foto <strong>AFTER</strong></p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP (max 2MB)</p>
                                    </div>
                                </template>
                                <template x-if="preview">
                                    <div>
                                        <img :src="preview" class="w-full h-32 object-cover rounded-lg mb-2">
                                        <p class="text-sm text-green-600 font-medium">✓ Foto After dipilih</p>
                                        <p class="text-xs text-gray-400">Klik untuk ganti</p>
                                    </div>
                                </template>
                            </label>
                        </div>
                        @error('after_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.portfolios.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Portfolio
                </button>
            </div>
        </form>
    </x-cards.card>
</div>
@endsection
