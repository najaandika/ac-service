@props(['promos'])

@if($promos->count() > 0)
<section class="py-8 bg-gradient-to-r from-accent-lime/20 via-accent-teal/20 to-primary/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-accent-lime rounded-full flex items-center justify-center">
                <i data-lucide="ticket-percent" class="w-5 h-5 text-foreground"></i>
            </div>
            <div>
                <h2 class="text-foreground text-lg font-bold">Promo Spesial! 🎉</h2>
                <p class="text-gray-600 text-sm">Gunakan kode promo berikut saat checkout</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($promos as $promo)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-foreground truncate">{{ $promo->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                            @if($promo->type === 'percentage')
                                Diskon {{ $promo->value }}%
                                @if($promo->max_discount)
                                    (max Rp {{ number_format($promo->max_discount, 0, ',', '.') }})
                                @endif
                            @else
                                Potongan Rp {{ number_format($promo->value, 0, ',', '.') }}
                            @endif
                            @if($promo->min_order)
                                <br><span class="text-xs">Min. order Rp {{ number_format($promo->min_order, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                    <button 
                        onclick="copyPromoCode('{{ $promo->code }}')" 
                        class="flex-shrink-0 bg-primary/10 hover:bg-primary/20 text-primary font-mono font-bold text-sm px-3 py-2 rounded-lg transition-colors"
                        title="Klik untuk copy"
                    >
                        {{ $promo->code }}
                    </button>
                </div>
                @if($promo->end_date)
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    Berlaku sampai {{ $promo->end_date->translatedFormat('d M Y') }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
function copyPromoCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        showPromoToast('Kode promo "' + code + '" berhasil disalin!', 'success');
    });
}

function showPromoToast(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.getElementById('promo-toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.id = 'promo-toast';
    toast.className = `fixed bottom-24 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-full shadow-lg flex items-center gap-2 transform transition-all duration-300 translate-y-20 opacity-0 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-gray-800 text-white'}`;

    // Icon
    const icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';

    toast.innerHTML = `${icon}<span class="font-medium">${message}</span>`;

    // Add to DOM
    document.body.appendChild(toast);

    // Trigger animation (slide up + fade in)
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    });

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endif
