@extends('layouts.public')

@section('title', 'Order Berhasil - AC Service')

@section('content')
<section class="py-20 bg-gray-50 flex items-center justify-center min-h-[80vh]">
    <div class="max-w-md w-full px-4">
        <div class="bg-white rounded-[var(--radius-card)] shadow-xl p-8 text-center">
            
            <div class="w-20 h-20 bg-success-light rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check" class="w-10 h-10 text-success"></i>
            </div>
            
            <h1 class="text-foreground text-2xl font-bold mb-2">Order Berhasil!</h1>
            <p class="text-gray-600 mb-8">Terima kasih telah memesan layanan kami. Teknisi kami akan segera menghubungi Anda.</p>
            
            <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Kode Order Anda</p>
                <div class="text-3xl font-mono font-bold text-primary tracking-widest cursor-pointer hover:text-primary-hover" onclick="copyCode('{{ $order->order_code }}')" title="Klik untuk salin">
                    {{ $order->order_code }}
                </div>
                <p class="text-xs text-gray-400 mt-2">Simpan kode ini untuk melacak status order</p>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('order.track', ['query' => $order->order_code]) }}" class="btn btn-primary w-full justify-center">
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
