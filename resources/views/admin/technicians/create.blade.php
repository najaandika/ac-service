@extends('layouts.app')

@section('title', 'Tambah Teknisi - Admin')
@section('page-title', 'Tambah Teknisi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-cards.card>
        <div>
            <h1 class="text-foreground text-2xl font-bold hidden lg:block">Tambah Teknisi Baru</h1>
            <p class="text-gray-600">Isi form untuk menambahkan teknisi</p>
        </div>
    </x-cards.card>

    <form action="{{ route('admin.technicians.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Basic Info -->
        <x-cards.card title="Informasi Teknisi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full @error('name') border-red-500 @enderror" placeholder="Contoh: Ahmad Sulaiman" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input w-full @error('phone') border-red-500 @enderror" placeholder="08123456789" required>
                    @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <input type="text" name="specialty" value="{{ old('specialty') }}" class="form-input w-full @error('specialty') border-red-500 @enderror" placeholder="Contoh: AC Split, AC Cassette">
                    @error('specialty')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg" class="form-input w-full @error('photo') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                    @error('photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm font-medium text-gray-700">Aktifkan teknisi ini</span>
                    </label>
                </div>
            </div>
        </x-cards.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.technicians.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Teknisi
            </button>
        </div>
    </form>
</div>
@endsection
