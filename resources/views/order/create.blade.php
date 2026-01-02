@extends('layouts.public')

@section('title', 'Order Service AC - AC Service')

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

            <form action="{{ route('order.store') }}" method="POST" class="space-y-6">
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
                            @php
                                $priceRecord = $selectedService->prices->where('capacity', $selectedCapacity)->first();
                                $unitPrice = $priceRecord ? $priceRecord->price : $selectedService->price;
                                $total = $unitPrice * $selectedQty;
                            @endphp
                            <p class="font-semibold text-green-600 text-lg">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="service_id" value="{{ $selectedService->id }}">
                    <input type="hidden" name="ac_capacity" value="{{ $selectedCapacity }}">
                    <input type="hidden" name="ac_quantity" value="{{ $selectedQty }}">
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
                            <input type="radio" name="service_id" value="{{ $service->id }}" class="peer sr-only" 
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
                            <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                            <select name="scheduled_time" id="scheduled_time" class="form-input">
                                <option value="pagi" {{ old('scheduled_time') == 'pagi' ? 'selected' : '' }}>Pagi (08:00 - 12:00)</option>
                                <option value="siang" {{ old('scheduled_time') == 'siang' ? 'selected' : '' }}>Siang (12:00 - 15:00)</option>
                                <option value="sore" {{ old('scheduled_time') == 'sore' ? 'selected' : '' }}>Sore (15:00 - 18:00)</option>
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

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary w-full justify-center text-lg py-4 shadow-lg shadow-primary/30 hover:scale-[1.02] active:scale-[0.98]">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        Konfirmasi Order
                    </button>
                    <p class="text-center text-xs text-gray-500 mt-4">
                        Dengan menekan tombol di atas, Anda setuju untuk dihubungi oleh teknisi kami via WhatsApp.
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
    @vite('resources/js/pages/order-create.js')
@endpush
@endsection
