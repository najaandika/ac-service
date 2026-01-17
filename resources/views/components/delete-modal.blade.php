@props([
    'title' => 'Konfirmasi Hapus',
    'itemLabel' => 'item',
])

{{-- Delete Modal Component --}}

<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/75 transition-opacity" @click="showDeleteModal = false"></div>

    <!-- Modal Panel - Compact -->
    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative z-10 w-full max-w-sm transform overflow-hidden rounded-xl bg-white shadow-xl transition-all">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Hapus {{ $itemLabel }} <strong x-text="itemName"></strong>?
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 flex justify-end gap-2">
            <button type="button" @click="showDeleteModal = false" class="btn btn-sm btn-outline">
                Batal
            </button>
            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-error">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

