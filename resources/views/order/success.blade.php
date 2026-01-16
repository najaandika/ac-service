@extends('layouts.public')

@section('title', 'Order Berhasil - AC Service')

@section('content')
<section class="py-20 bg-gray-50 flex items-center justify-center min-h-[80vh]">
    <div class="max-w-md w-full px-4">
        <div class="bg-white rounded-[var(--radius-card)] shadow-xl p-8 text-center fade-up">
            
            <!-- Animated Success Icon -->
            <div class="w-20 h-20 bg-success-light rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce-once">
                <svg class="w-10 h-10 text-success checkmark-animate" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M4 12l5 5L20 6" stroke-linecap="round" stroke-linejoin="round" class="checkmark-path"/>
                </svg>
            </div>
            
            <h1 class="text-foreground text-2xl font-bold mb-2">🎉 Order Berhasil!</h1>
            <p class="text-gray-600 mb-8">Terima kasih telah memesan layanan kami. Teknisi kami akan segera menghubungi Anda.</p>
            
            <div class="bg-gradient-to-r from-primary/5 to-accent-teal/5 rounded-xl p-6 mb-6 border border-primary/20">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Kode Order Anda</p>
                <div class="text-3xl font-mono font-bold text-primary tracking-widest cursor-pointer hover:text-primary-hover transition-colors" onclick="copyCode('{{ $order->order_code }}')" title="Klik untuk salin">
                    {{ $order->order_code }}
                </div>
                <p class="text-xs text-gray-400 mt-2">📋 Klik untuk menyalin | Simpan kode ini untuk melacak order</p>
            </div>

            {{-- WhatsApp Confirmation --}}
            @if(!empty($waUrl))
            <a href="{{ $waUrl }}" target="_blank" class="block w-full mb-6 py-4 px-6 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Konfirmasi via WhatsApp
            </a>
            @endif
            
            <div class="space-y-3">
                <a href="{{ route('invoice.download', $order) }}" class="btn btn-primary w-full justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download Invoice (PDF)
                </a>
                <a href="{{ route('order.track', ['query' => $order->order_code]) }}" class="btn btn-outline w-full justify-center">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Lacak Order
                </a>
                <a href="/" class="btn btn-outline w-full justify-center">
                    Kembali ke Beranda
                </a>
            </div>
            
        </div>
    </div>
</section>

@push('scripts')
    @vite('resources/js/pages/order-success.js')
@endpush
@endsection

