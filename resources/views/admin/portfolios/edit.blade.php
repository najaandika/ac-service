@extends('layouts.app')

@section('title', 'Edit Portfolio - Admin')
@section('page-title', 'Edit Portfolio')

@section('content')
<div class="space-y-6">
    <x-page-header title="Edit Portfolio" subtitle="Ubah foto atau informasi portfolio" />

    <x-cards.card>
        <form action="{{ route('admin.portfolios.update', $portfolio) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-forms.input 
                        name="title" 
                        label="Judul Portfolio" 
                        placeholder="Contoh: Cuci AC Rumah Bpk Ahmad"
                        :value="old('title', $portfolio->title)"
                        required 
                    />

                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan (Opsional)</label>
                        <select name="service_id" id="service_id" class="form-input">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $portfolio->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" class="form-input" placeholder="Ceritakan sedikit tentang pekerjaan ini...">{{ old('description', $portfolio->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-forms.input 
                            type="number"
                            name="sort_order" 
                            label="Urutan" 
                            :value="old('sort_order', $portfolio->sort_order)"
                        />
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $portfolio->is_published) ? 'checked' : '' }} class="form-checkbox">
                                <span class="text-sm font-medium text-gray-700">Publikasikan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Uploads -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Before</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $portfolio->before_image) }}" alt="Before" class="w-full h-40 object-cover rounded-lg">
                        </div>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-3 text-center hover:border-primary transition-colors">
                            <input type="file" name="before_image" id="before_image" accept="image/*" class="hidden">
                            <label for="before_image" class="cursor-pointer block text-sm text-gray-600">
                                <i data-lucide="upload-cloud" class="w-6 h-6 mx-auto text-gray-400 mb-1"></i>
                                Ganti foto Before
                            </label>
                        </div>
                        @error('before_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto After</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $portfolio->after_image) }}" alt="After" class="w-full h-40 object-cover rounded-lg">
                        </div>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-3 text-center hover:border-primary transition-colors">
                            <input type="file" name="after_image" id="after_image" accept="image/*" class="hidden">
                            <label for="after_image" class="cursor-pointer block text-sm text-gray-600">
                                <i data-lucide="upload-cloud" class="w-6 h-6 mx-auto text-gray-400 mb-1"></i>
                                Ganti foto After
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
                    Update Portfolio
                </button>
            </div>
        </form>
    </x-cards.card>
</div>
@endsection
