@extends('layouts.app')

@section('title', 'Kelola Portfolio - Admin')
@section('page-title', 'Portfolio')

@section('content')
<div class="space-y-6" x-data="deleteModal">
    <!-- Page Header -->
    <x-page-header title="Kelola Portfolio" subtitle="Foto hasil kerja Before/After">
        <x-slot:actions>
            <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Portfolio
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- Portfolio Grid -->
    <x-cards.card>
        @if($portfolios->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($portfolios as $portfolio)
            <div class="bg-gray-50 rounded-xl overflow-hidden group">
                <!-- Before/After Images -->
                <div class="relative aspect-[4/3] grid grid-cols-2">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $portfolio->before_image) }}" alt="Before" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded font-medium">Before</span>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('storage/' . $portfolio->after_image) }}" alt="After" class="w-full h-full object-cover">
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded font-medium">After</span>
                    </div>
                </div>
                <!-- Info -->
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-semibold text-foreground">{{ $portfolio->title }}</h3>
                            @if($portfolio->service)
                            <span class="text-xs text-gray-500">{{ $portfolio->service->name }}</span>
                            @endif
                        </div>
                        <span class="badge {{ $portfolio->is_published ? 'badge-success' : 'badge-gray' }}">
                            {{ $portfolio->is_published ? 'Aktif' : 'Draft' }}
                        </span>
                    </div>
                    @if($portfolio->description)
                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $portfolio->description }}</p>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <span class="text-xs text-gray-400">{{ $portfolio->created_at->diffForHumans() }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="btn btn-sm btn-outline">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <button type="button" @click="openDeleteModal('{{ route('admin.portfolios.destroy', $portfolio) }}', '{{ $portfolio->title }}')" class="btn btn-sm btn-error">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($portfolios->hasPages())
        <div class="mt-6 pt-4 border-t">
            {{ $portfolios->links() }}
        </div>
        @endif

        @else
        <x-empty-state 
            icon="image" 
            title="Belum ada portfolio" 
            description="Tambahkan foto hasil kerja untuk ditampilkan di galeri publik."
        >
            <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Portfolio
            </a>
        </x-empty-state>
        @endif
    </x-cards.card>

    <!-- Delete Confirmation Modal -->
    <x-delete-modal title="Hapus Portfolio" itemLabel="portfolio" />
</div>
@endsection
