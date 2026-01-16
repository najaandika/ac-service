@extends('layouts.app')

@section('title', 'Edit Teknisi - Admin')
@section('page-title', 'Edit Teknisi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Edit Teknisi" :subtitle="'Edit data teknisi: ' . $technician->name" />

    <form action="{{ route('admin.technicians.update', $technician) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Info -->
        <x-cards.card title="Informasi Teknisi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Photo Preview -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-4">
                        <img src="{{ $technician->photo_url }}" alt="{{ $technician->name }}" class="w-20 h-20 rounded-full object-cover">
                        <div>
                            <p class="font-medium text-foreground">{{ $technician->name }}</p>
                            <p class="text-sm text-gray-500">Foto saat ini</p>
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <x-forms.input 
                        name="name" 
                        label="Nama Lengkap" 
                        placeholder="Contoh: Ahmad Sulaiman"
                        :value="$technician->name"
                        :required="true"
                        autocomplete="name"
                    />
                </div>
                
                <x-forms.input 
                    name="phone" 
                    label="Nomor Telepon" 
                    placeholder="08123456789"
                    :value="$technician->phone"
                    :required="true"
                    autocomplete="tel"
                />
                
                <x-forms.input 
                    name="specialty" 
                    label="Spesialisasi" 
                    placeholder="Contoh: AC Split, AC Cassette"
                    :value="$technician->specialty"
                />
                
                <div class="md:col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto Profil</label>
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" class="form-input w-full @error('photo') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG. Maksimal 2MB.</p>
                    @error('photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="is_active" class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $technician->is_active) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm font-medium text-gray-700">Aktifkan teknisi ini</span>
                    </label>
                </div>
            </div>
        </x-cards.card>

        <!-- Stats (Read-only) -->
        <x-cards.card title="Statistik">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-foreground">{{ number_format($technician->rating, 1) }}</p>
                    <p class="text-sm text-gray-500 flex items-center justify-center gap-1">
                        <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                        Rating
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-foreground">{{ $technician->total_orders }}</p>
                    <p class="text-sm text-gray-500">Total Order</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-foreground">{{ $technician->created_at->diffForHumans() }}</p>
                    <p class="text-sm text-gray-500">Bergabung</p>
                </div>
            </div>
        </x-cards.card>

        <!-- Actions -->
        <x-forms.form-actions 
            cancelUrl="{{ route('admin.technicians.index') }}" 
            submitText="Update Teknisi" 
        />
    </form>
</div>
@endsection
