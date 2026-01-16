@props([
    'title' => 'Konfirmasi Hapus',
    'itemLabel' => 'item',
])

{{-- 
    Delete Modal Component
    Usage: 
    Parent needs x-data with: showDeleteModal, deleteUrl, itemName, openDeleteModal(url, name) function
    
    Example:
    x-data="{
        showDeleteModal: false,
        deleteUrl: '',
        itemName: '',
        openDeleteModal(url, name) {
            this.deleteUrl = url;
            this.itemName = name;
            this.showDeleteModal = true;
        }
    }"
--}}

<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showDeleteModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-6 py-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">{{ $title }}</h3>
                        <p class="mt-2 text-gray-600">
                            Apakah Anda yakin ingin menghapus {{ $itemLabel }} <strong x-text="itemName"></strong>? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" @click="showDeleteModal = false" class="btn btn-outline w-full sm:w-auto justify-center">
                    Batal
                </button>
                <form :action="deleteUrl" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error w-full justify-center">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
