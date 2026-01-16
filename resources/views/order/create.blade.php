@extends('layouts.public')

@section('title', 'Order Service AC - AC Service')

@section('description', 'Pesan layanan service AC online. Cuci AC, isi freon, perbaikan, bongkar pasang. Pilih tanggal dan waktu sesuai jadwal Anda. Teknisi profesional, garansi layanan.')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-6 md:p-8">
            <div class="text-center mb-8">
                <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-2">Form Order Service</h1>
                <p class="text-gray-500">Lengkapi data di bawah ini untuk memesan layanan</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-700 font-semibold mb-2">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>Mohon perbaiki kesalahan berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('order.store') }}" method="POST" class="space-y-6" 
                x-data="orderSummaryForm({ serviceName: '{{ $selectedService->name ?? "-" }}', price: {{ $totalPrice ?? 0 }} })"
                @submit.prevent="getSummary()">
                @csrf
                
                @if($selectedService)
                {{-- SIMPLIFIED FLOW: Coming from service detail page --}}
                
                <!-- Order Summary (Read-only) -->
                <div class="bg-gradient-to-r from-primary/5 to-accent-teal/5 rounded-xl p-5 border border-primary/20">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-primary"></i>
                            Ringkasan Order
                        </h2>
                        <a href="/layanan/{{ $selectedService->slug }}" class="text-sm text-primary hover:underline">Ubah</a>
                    </div>
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i data-lucide="{{ $selectedService->icon }}" class="w-7 h-7 text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-foreground text-lg">{{ $selectedService->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ Str::limit($selectedService->description, 60) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Kapasitas</p>
                            <p class="font-bold text-foreground" id="display-capacity">
                                {{ match($selectedCapacity) {
                                    '0.5pk' => '½ PK',
                                    '0.75pk' => '¾ PK',
                                    '1pk' => '1 PK',
                                    '1.5pk' => '1½ PK',
                                    '2pk' => '2 PK',
                                    '2.5pk' => '2½ PK',
                                    '3pk' => '3 PK',
                                    default => $selectedCapacity
                                } }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Jumlah</p>
                            <p class="font-bold text-foreground">{{ $selectedQty }} Unit</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Total</p>
                            <p class="font-semibold text-green-600 text-lg">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="service_id" value="{{ $selectedService->id }}">
                    <input type="hidden" name="ac_capacity" value="{{ $selectedCapacity }}">
                    <input type="hidden" name="ac_quantity" id="ac_quantity" value="{{ $selectedQty }}">
                </div>

                <!-- AC Type (still needed) -->
                <div class="border-b border-gray-100 pb-6">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs">1</span>
                        Detail AC
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ac_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe AC</label>
                            <select name="ac_type" id="ac_type" class="form-input">
                                <option value="split" {{ old('ac_type') == 'split' ? 'selected' : '' }}>AC Split (Dinding)</option>
                                <option value="cassette" {{ old('ac_type') == 'cassette' ? 'selected' : '' }}>AC Cassette</option>
                                <option value="standing" {{ old('ac_type') == 'standing' ? 'selected' : '' }}>AC Standing</option>
                                <option value="window" {{ old('ac_type') == 'window' ? 'selected' : '' }}>AC Window</option>
                                <option value="central" {{ old('ac_type') == 'central' ? 'selected' : '' }}>AC Central</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Keluhan (Opsional)</label>
                            <input type="text" name="notes" id="notes" value="{{ old('notes') }}" class="form-input" placeholder="Contoh: AC bocor air" autocomplete="off">
                        </div>
                    </div>
                </div>

                @else
                {{-- FULL FLOW: Direct access to /order --}}
                
                <!-- 1. Layanan -->
                <div class="border-b border-gray-100 pb-6">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs">1</span>
                        Pilih Layanan
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($services as $service)
                        <label class="cursor-pointer group">
                            <input type="radio" name="service_id" value="{{ $service->id }}" class="peer sr-only service-radio" 
                                data-name="{{ $service->name }}"
                                data-price="{{ $service->prices->min('price') ?? $service->price }}"
                                data-prices='@json($service->prices->pluck("price", "capacity"))'
                                {{ old('service_id') == $service->id ? 'checked' : '' }}>
                            <div class="border border-border rounded-xl p-4 peer-checked:border-primary peer-checked:bg-primary/5 hover:border-primary transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="{{ $service->icon }}" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-foreground">{{ $service->name }}</div>
                                        <div class="text-green-600 font-semibold text-sm">{{ $service->starting_price }}</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Detail AC -->
                <div class="border-b border-gray-100 pb-6">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs">2</span>
                        Detail AC
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="ac_type_full" class="block text-sm font-medium text-gray-700 mb-1">Tipe AC</label>
                            <select name="ac_type" id="ac_type_full" class="form-input">
                                <option value="split" {{ old('ac_type') == 'split' ? 'selected' : '' }}>AC Split (Dinding)</option>
                                <option value="cassette" {{ old('ac_type') == 'cassette' ? 'selected' : '' }}>AC Cassette</option>
                                <option value="standing" {{ old('ac_type') == 'standing' ? 'selected' : '' }}>AC Standing</option>
                                <option value="window" {{ old('ac_type') == 'window' ? 'selected' : '' }}>AC Window</option>
                                <option value="central" {{ old('ac_type') == 'central' ? 'selected' : '' }}>AC Central</option>
                            </select>
                        </div>
                        <div>
                            <label for="ac_capacity" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas (PK)</label>
                            <select name="ac_capacity" id="ac_capacity" class="form-input">
                                <option value="0.5pk" {{ old('ac_capacity', $selectedCapacity) == '0.5pk' ? 'selected' : '' }}>1/2 PK</option>
                                <option value="0.75pk" {{ old('ac_capacity', $selectedCapacity) == '0.75pk' ? 'selected' : '' }}>3/4 PK</option>
                                <option value="1pk" {{ old('ac_capacity', $selectedCapacity) == '1pk' ? 'selected' : '' }}>1 PK</option>
                                <option value="1.5pk" {{ old('ac_capacity', $selectedCapacity) == '1.5pk' ? 'selected' : '' }}>1.5 PK</option>
                                <option value="2pk" {{ old('ac_capacity', $selectedCapacity) == '2pk' ? 'selected' : '' }}>2 PK</option>
                                <option value="2.5pk" {{ old('ac_capacity', $selectedCapacity) == '2.5pk' ? 'selected' : '' }}>2.5 PK</option>
                                <option value="3pk" {{ old('ac_capacity', $selectedCapacity) == '3pk' ? 'selected' : '' }}>3 PK+</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="ac_quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Unit</label>
                        <div class="flex items-center max-w-[150px]">
                            <button type="button" onclick="decrementQuantity()" class="w-10 h-10 bg-gray-100 rounded-l-lg hover:bg-gray-200 flex items-center justify-center" aria-label="Kurangi jumlah">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <input type="number" name="ac_quantity" id="ac_quantity" value="{{ old('ac_quantity', $selectedQty) }}" min="1" max="10" class="w-full text-center border-y border-gray-200 py-2 focus:outline-none" readonly>
                            <button type="button" onclick="incrementQuantity()" class="w-10 h-10 bg-gray-100 rounded-r-lg hover:bg-gray-200 flex items-center justify-center" aria-label="Tambah jumlah">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="notes_full" class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Catatan (Opsional)</label>
                        <textarea name="notes" id="notes_full" rows="2" class="form-input" placeholder="Contoh: AC bocor air, remote tidak berfungsi, dll">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Live Price Summary --}}
                    <div class="mt-4 bg-gradient-to-r from-primary/5 to-accent-teal/5 rounded-xl p-4 border border-primary/20" 
                         x-data="priceCalculator()" 
                         x-init="init()">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Estimasi Harga:</p>
                                <p class="text-sm text-gray-500" x-show="serviceName" x-text="serviceName + ' - ' + capacity + ' × ' + quantity + ' unit'"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-green-600" x-text="formattedTotal"></p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Jadwal & Data Diri (Same for both flows) -->
                <div class="border-b border-gray-100 pb-6">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs">{{ $selectedService ? '2' : '3' }}</span>
                        Jadwal & Data Diri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Layanan</label>
                            <input type="date" name="scheduled_date" id="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="form-input">
                        </div>
                        <div>
                            <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Layanan</label>
                            @php
                                $timeSlots = isset($settings['time_slots']) && !empty($settings['time_slots']) 
                                    ? array_map('trim', explode(',', $settings['time_slots']))
                                    : ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
                            @endphp
                            <select name="scheduled_time" id="scheduled_time" class="form-input" required>
                                @foreach($timeSlots as $slot)
                                    <option value="{{ $slot }}" {{ old('scheduled_time', '09:00') == $slot ? 'selected' : '' }}>{{ $slot }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" placeholder="Masukkan nama anda" autocomplete="name" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-input" placeholder="Contoh: 08123456789" pattern="[0-9]*" inputmode="numeric" autocomplete="tel" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input" placeholder="email@contoh.com" autocomplete="email">
                            </div>
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="address" id="address" rows="3" class="form-input" placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan" autocomplete="street-address" required>{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota / Area</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="form-input" placeholder="Contoh: Jakarta Selatan" autocomplete="address-level2">
                        </div>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="border-b border-gray-100 pb-6" 
                     x-data="promoCodeValidator" 
                     data-service-id="{{ $selectedService?->id ?? '' }}" 
                     data-subtotal="{{ $totalPrice ?? 0 }}"
                     data-unit-price="{{ $unitPrice ?? 0 }}">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs">{{ $selectedService ? '3' : '4' }}</span>
                        Kode Promo
                    </h2>
                    
                    @if(isset($activePromos) && $activePromos->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Promo Tersedia:</p>
                        <div class="space-y-2">
                            @foreach($activePromos as $promo)
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-lg hover:border-blue-300 transition-all group">
                                <div class="flex-1 min-w-0 mr-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-sm text-blue-800">{{ $promo->code }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium">
                                            @if($promo->type === 'percentage')
                                                Diskon {{ $promo->value }}%
                                            @else
                                                -{{ number_format($promo->value/1000) }}k
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 line-clamp-1">{{ $promo->name }}</p>
                                </div>
                                <button type="button" 
                                    @click="code = '{{ $promo->code }}'; resetPromo(); $nextTick(() => { applyPromo() })"
                                    class="text-xs bg-white text-blue-600 border border-blue-200 px-3 py-1.5 rounded-md hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all font-medium shadow-sm">
                                    Pakai
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <input type="text" 
                                id="promo_input"
                                x-model="code" 
                                @input="resetPromo()"
                                class="form-input uppercase" 
                                placeholder="Masukkan kode promo (opsional)"
                                :class="{ 'border-green-500 bg-green-50': applied, 'border-red-500 bg-red-50': error }"
                                autocomplete="off">
                        </div>
                        <button type="button" 
                            @click="applyPromo()"
                            :disabled="loading || !code || applied"
                            class="btn btn-outline px-4 disabled:opacity-50">
                            <svg x-show="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-show="!loading && !applied">Cek</span>
                            <span x-show="applied" class="text-green-600">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </span>
                        </button>
                    </div>
                    
                    <!-- Error message -->
                    <p x-show="error" x-text="message" class="text-red-500 text-sm mt-2"></p>
                    
                    <!-- Success: Discount Applied -->
                    <div x-show="applied" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-700 font-medium text-sm" x-text="message"></p>
                                <p class="text-green-600 text-xs">Potongan: <span class="font-bold" x-text="discountFormatted"></span></p>
                            </div>
                            <button type="button" @click="clearPromo()" class="text-green-600 hover:text-green-800">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="promo_code" x-model="appliedCode">
                    <input type="hidden" name="promo_discount" x-model="discount">
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="btn btn-primary w-full justify-center text-lg py-4 shadow-lg shadow-primary/30 hover:scale-[1.02] active:scale-[0.98] gap-2"
                    >
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        Konfirmasi Order
                    </button>
                    <p class="text-center text-xs text-gray-500 mt-4">
                        Dengan menekan tombol di atas, Anda setuju untuk dihubungi oleh teknisi kami via WhatsApp.
                    </p>
                </div>

                {{-- Order Summary Modal --}}
                <div x-show="showSummary" 
                     x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    {{-- Backdrop --}}
                    <div class="fixed inset-0 bg-black/50" @click="showSummary = false"></div>
                    
                    {{-- Modal Content --}}
                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto z-10"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-foreground">Konfirmasi Order</h3>
                                <button type="button" @click="showSummary = false" class="text-gray-400 hover:text-gray-600">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4">Pastikan data di bawah sudah benar:</p>
                            
                            <div class="space-y-3 text-sm">
                                {{-- Layanan --}}
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Layanan</span>
                                    <span class="font-medium text-foreground" x-text="summary.layanan"></span>
                                </div>
                                
                                {{-- Tanggal & Waktu --}}
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Jadwal</span>
                                    <span class="font-medium text-foreground" x-text="summary.jadwal"></span>
                                </div>
                                
                                {{-- Nama --}}
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Nama</span>
                                    <span class="font-medium text-foreground" x-text="summary.nama"></span>
                                </div>
                                
                                {{-- WhatsApp --}}
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">WhatsApp</span>
                                    <span class="font-medium text-foreground" x-text="summary.whatsapp"></span>
                                </div>
                                
                                {{-- Alamat --}}
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Alamat</span>
                                    <span class="font-medium text-foreground text-right max-w-[200px]" x-text="summary.alamat"></span>
                                </div>
                            </div>
                            
                            {{-- Pricing Section --}}
                            <div class="mt-4 pt-4 border-t-2 border-gray-200 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga</span>
                                    <span class="font-medium" x-text="formatRupiah(summary.harga)"></span>
                                </div>
                                <div class="flex justify-between" x-show="summary.diskon > 0">
                                    <span class="text-green-600">Diskon</span>
                                    <span class="font-medium text-green-600" x-text="'- ' + formatRupiah(summary.diskon)"></span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="font-bold text-foreground">Total</span>
                                    <span class="font-bold text-xl text-primary" x-text="formatRupiah(summary.total)"></span>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex gap-3">
                                <button type="button" 
                                    @click="showSummary = false" 
                                    class="flex-1 btn btn-outline justify-center">
                                    <i data-lucide="pencil" class="w-4 h-4 mr-2"></i>
                                    Ubah Data
                                </button>
                                <button type="button" 
                                    @click="confirmOrder()" 
                                    :disabled="submitting"
                                    class="flex-1 btn btn-primary justify-center gap-2">
                                    <i data-lucide="check" class="w-4 h-4" x-show="!submitting"></i>
                                    <svg x-show="submitting" x-cloak class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span x-text="submitting ? 'Memproses...' : 'Konfirmasi'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
    @vite('resources/js/pages/order-create.js')
@endpush
@endsection
