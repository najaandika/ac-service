@extends('layouts.app')

@section('title', 'Tambah Teknisi - Admin')
@section('page-title', 'Tambah Teknisi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Tambah Teknisi Baru" subtitle="Isi form untuk menambahkan teknisi" />

    <form action="{{ route('admin.technicians.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Basic Info -->
        <x-cards.card title="Informasi Teknisi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-forms.input 
                        name="name" 
                        label="Nama Lengkap" 
                        placeholder="Contoh: Ahmad Sulaiman"
                        :required="true"
                        autocomplete="name"
                    />
                </div>
                
                <x-forms.input 
                    name="phone" 
                    label="Nomor Telepon" 
                    placeholder="08123456789"
                    :required="true"
                    autocomplete="tel"
                />
                
                <x-forms.input 
                    name="specialty" 
                    label="Spesialisasi" 
                    placeholder="Contoh: AC Split, AC Cassette"
                />
                
                <div class="md:col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" class="form-input w-full @error('photo') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                    @error('photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="is_active" class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm font-medium text-gray-700">Aktifkan teknisi ini</span>
                    </label>
                </div>
            </div>
        </x-cards.card>

        <!-- Actions -->
        <x-forms.form-actions 
            cancelUrl="{{ route('admin.technicians.index') }}" 
            submitText="Simpan Teknisi" 
        />
    </form>
</div>
@endsection
