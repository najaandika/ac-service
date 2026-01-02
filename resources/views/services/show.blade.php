@extends('layouts.public')

@section('title', $service->name . ' - AC Service')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="/" class="hover:text-primary">Beranda</a></li>
                <li>/</li>
                <li><a href="/#layanan" class="hover:text-primary">Layanan</a></li>
                <li>/</li>
                <li class="text-foreground font-medium">{{ $service->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: Service Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-6 md:p-8">
                    
                    <!-- Header -->
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-16 h-16 bg-accent-teal rounded-[var(--radius-icon)] flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $service->icon }}" class="w-8 h-8 text-foreground"></i>
                        </div>
                        <div>
                            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-2">{{ $service->name }}</h1>
                            <p class="text-gray-600">{{ $service->description }}</p>
                        </div>
                    </div>

                    <!-- Features List -->
                    @if($service->features && count($service->features) > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <i data-lucide="list-checks" class="w-5 h-5 text-primary"></i>
                            Yang Termasuk dalam Layanan
                        </h2>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($service->features as $feature)
                            <li class="flex items-start gap-3">
                                <div class="w-5 h-5 bg-success-light rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i data-lucide="check" class="w-3 h-3 text-success"></i>
                                </div>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Price Table -->
                    @if($service->prices->count() > 0)
                    <div>
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <i data-lucide="tag" class="w-5 h-5 text-primary"></i>
                            Daftar Harga per Kapasitas AC
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 rounded-l-lg">Kapasitas</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600 rounded-r-lg">Harga</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($service->prices->sortBy('capacity') as $price)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-700">{{ $price->capacity_label }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-green-600">{{ $price->formatted_price }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">* Harga dapat berubah sewaktu-waktu. Harga belum termasuk sparepart jika diperlukan.</p>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Right: Quick Order Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[var(--radius-card)] shadow-lg p-6 sticky top-24" 
                     x-data="quickOrder({
                        defaultCapacity: '{{ $service->prices->sortBy('capacity')->first()?->capacity ?? '1pk' }}',
                        defaultPrice: {{ $service->prices->sortBy('capacity')->first()?->price ?? $service->price }},
                        slug: '{{ $service->slug }}'
                     })">
                    <h3 class="text-lg font-bold text-foreground mb-4 text-center">Order Sekarang</h3>
                    
                    <!-- Capacity Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kapasitas AC</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($service->prices->sortBy('capacity') as $index => $price)
                            <button 
                                type="button"
                                @click="selectCapacity('{{ $price->capacity }}', {{ $price->price }})"
                                :class="selectedCapacity === '{{ $price->capacity }}' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-200 hover:border-primary'"
                                class="border rounded-lg py-2 px-3 text-center text-sm font-medium transition-all">
                                {{ $price->capacity_label }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Unit</label>
                        <div class="flex items-center justify-center">
                            <button type="button" @click="decrementQty()" class="w-10 h-10 bg-gray-100 rounded-l-lg hover:bg-gray-200 flex items-center justify-center" aria-label="Kurangi jumlah">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <input type="text" id="service-quantity" x-model="quantity" class="w-16 text-center border-y border-gray-200 py-2 font-bold text-lg" readonly aria-label="Jumlah unit AC">
                            <button type="button" @click="incrementQty()" class="w-10 h-10 bg-gray-100 rounded-r-lg hover:bg-gray-200 flex items-center justify-center" aria-label="Tambah jumlah">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 text-center">
                        <p class="text-sm text-gray-500 mb-1">Total Harga</p>
                        <p class="text-3xl font-semibold text-green-600" x-text="formattedTotal"></p>
                    </div>

                    <!-- Order Button -->
                    <a :href="orderUrl" class="btn btn-primary w-full justify-center text-lg py-4 shadow-lg shadow-primary/30">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        Lanjut Order
                    </a>

                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        @if(!empty($settings['whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}?text=Halo, saya mau tanya tentang layanan {{ $service->name }}" 
                           target="_blank"
                           class="text-sm text-gray-500 hover:text-primary inline-flex items-center gap-1">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            Tanya via WhatsApp
                        </a>
                        @endif
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i data-lucide="clock" class="w-4 h-4 text-primary"></i>
                            <span>Estimasi {{ $service->duration_minutes }} menit</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i data-lucide="shield-check" class="w-4 h-4 text-primary"></i>
                            <span>Garansi 30 hari</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Other Services -->
        @if($otherServices->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-foreground mb-6">Layanan Lainnya</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($otherServices as $other)
                <a href="/layanan/{{ $other->slug }}" class="bg-white rounded-[var(--radius-card)] p-6 hover:shadow-lg transition-shadow block">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-lucide="{{ $other->icon }}" class="w-6 h-6 text-primary"></i>
                    </div>
                    <h3 class="text-foreground font-bold mb-1">{{ $other->name }}</h3>
                    <p class="text-green-600 font-semibold text-sm">{{ $other->starting_price }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
