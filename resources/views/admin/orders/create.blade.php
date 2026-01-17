@extends('layouts.app')

@section('title', 'Tambah Order - Admin')
@section('page-title', 'Tambah Order')

@section('content')
<div class="space-y-6" x-data="orderForm()">
    <x-page-header title="Tambah Order Baru" subtitle="Input order dari pelanggan via WA/Telepon" />

    <form action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Customer & Service -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Selection -->
                <x-cards.card title="Data Pelanggan">
                    <div class="space-y-4">
                        <!-- Customer Type Toggle -->
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="customer_type" value="existing" x-model="customerType" class="form-radio">
                                <span class="text-sm font-medium">Pelanggan Lama</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="customer_type" value="new" x-model="customerType" class="form-radio">
                                <span class="text-sm font-medium">Pelanggan Baru</span>
                            </label>
                        </div>

                        <!-- Existing Customer Select -->
                        <div x-show="customerType === 'existing'" x-cloak>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Pelanggan</label>
                            <select name="customer_id" id="customer_id" class="form-input" x-bind:required="customerType === 'existing'">
                                <option value="">-- Cari pelanggan --</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->phone }}
                                </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Customer Form -->
                        <div x-show="customerType === 'new'" x-cloak class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" placeholder="Nama pelanggan" x-bind:required="customerType === 'new'" autocomplete="off">
                                    @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xxxxxxxxxx" x-bind:required="customerType === 'new'" autocomplete="off">
                                    @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (opsional)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input" placeholder="email@example.com" autocomplete="off">
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="2" class="form-input" placeholder="Alamat lengkap" x-bind:required="customerType === 'new'" autocomplete="off">{{ old('address') }}</textarea>
                                @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota (opsional)</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" class="form-input" placeholder="Nama kota" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </x-cards.card>

                <!-- Service Selection -->
                <x-cards.card title="Detail Layanan">
                    <div class="space-y-4">
                        <div>
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan <span class="text-red-500">*</span></label>
                            <select name="service_id" id="service_id" class="form-input" required x-model="serviceId" @change="updatePrice()">
                                <option value="">-- Pilih layanan --</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" data-prices='@json($service->prices->pluck("price", "capacity"))'>
                                    {{ $service->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('service_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="ac_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe AC <span class="text-red-500">*</span></label>
                                <select name="ac_type" id="ac_type" class="form-input" required>
                                    <option value="split" {{ old('ac_type') == 'split' ? 'selected' : '' }}>Split</option>
                                    <option value="cassette" {{ old('ac_type') == 'cassette' ? 'selected' : '' }}>Cassette</option>
                                    <option value="standing" {{ old('ac_type') == 'standing' ? 'selected' : '' }}>Standing</option>
                                    <option value="central" {{ old('ac_type') == 'central' ? 'selected' : '' }}>Central</option>
                                </select>
                            </div>
                            <div>
                                <label for="ac_capacity" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas <span class="text-red-500">*</span></label>
                                <select name="ac_capacity" id="ac_capacity" class="form-input" required x-model="capacity" @change="updatePrice()">
                                    <option value="0.5pk">1/2 PK</option>
                                    <option value="0.75pk">3/4 PK</option>
                                    <option value="1pk" selected>1 PK</option>
                                    <option value="1.5pk">1.5 PK</option>
                                    <option value="2pk">2 PK</option>
                                    <option value="2.5pk">2.5 PK</option>
                                </select>
                            </div>
                            <div>
                                <label for="ac_quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Unit <span class="text-red-500">*</span></label>
                                <input type="number" name="ac_quantity" id="ac_quantity" value="{{ old('ac_quantity', 1) }}" min="1" max="10" class="form-input" required x-model="quantity" @input="updatePrice()">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="scheduled_date" id="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="form-input" required>
                                @error('scheduled_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu <span class="text-red-500">*</span></label>
                                <select name="scheduled_time" id="scheduled_time" class="form-input" required>
                                    @php
                                        $timeSlots = explode(',', $settings['time_slots'] ?? '08:00-10:00,10:00-12:00,13:00-15:00,15:00-17:00');
                                    @endphp
                                    @foreach($timeSlots as $slot)
                                    <option value="{{ trim($slot) }}" {{ old('scheduled_time') == trim($slot) ? 'selected' : '' }}>{{ trim($slot) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                            <textarea name="notes" id="notes" rows="2" class="form-input" placeholder="Catatan tambahan..." autocomplete="off">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </x-cards.card>
            </div>

            <!-- Right Column - Summary -->
            <div class="space-y-6">
                <!-- Technician & Promo -->
                <x-cards.card title="Teknisi & Promo">
                    <div class="space-y-4">
                        <div>
                            <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">Tugaskan Teknisi</label>
                            <select name="technician_id" id="technician_id" class="form-input">
                                <option value="">-- Pilih nanti --</option>
                                @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ old('technician_id') == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="promo_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Promo</label>
                            <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code') }}" class="form-input" placeholder="PROMO123" autocomplete="off">
                        </div>
                    </div>
                </x-cards.card>

                <!-- Price Summary -->
                <x-cards.card title="Ringkasan Harga">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga per unit</span>
                            <span class="font-medium" x-text="formatRupiah(unitPrice)">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumlah unit</span>
                            <span class="font-medium" x-text="quantity + ' unit'">1 unit</span>
                        </div>
                        <hr>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-primary" x-text="formatRupiah(totalPrice)">Rp 0</span>
                        </div>
                        <p class="text-xs text-gray-500">* Diskon promo akan dihitung setelah submit</p>
                    </div>
                </x-cards.card>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat Order
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function orderForm() {
    return {
        customerType: '{{ old('customer_type', 'new') }}',
        serviceId: '{{ old('service_id', '') }}',
        capacity: '{{ old('ac_capacity', '1pk') }}',
        quantity: {{ old('ac_quantity', 1) }},
        unitPrice: 0,
        totalPrice: 0,
        servicePrices: {},

        init() {
            this.updatePrice();
        },

        updatePrice() {
            const select = document.getElementById('service_id');
            const option = select.options[select.selectedIndex];
            
            if (option && option.dataset.prices) {
                try {
                    this.servicePrices = JSON.parse(option.dataset.prices);
                    this.unitPrice = this.servicePrices[this.capacity] || 0;
                    this.totalPrice = this.unitPrice * this.quantity;
                } catch (e) {
                    this.unitPrice = 0;
                    this.totalPrice = 0;
                }
            } else {
                this.unitPrice = 0;
                this.totalPrice = 0;
            }
        },

        formatRupiah(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush
@endsection
