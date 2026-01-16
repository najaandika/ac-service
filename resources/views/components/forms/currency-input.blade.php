@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    
    <div class="relative flex" x-data="{ 
        displayValue: '{{ $value ? number_format((float)$value, 0, ',', '.') : '' }}',
        updateValue(e) {
            let val = e.target.value.replace(/\D/g, '');
            this.displayValue = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $refs.hiddenInput.value = val;
        }
    }">
        <span class="inline-flex items-center px-3 text-sm text-gray-600 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">Rp</span>
        <input 
            type="text" 
            x-model="displayValue"
            @input="updateValue"
            id="{{ $name }}_display" 
            class="form-input w-full rounded-l-none @error($name) border-red-500 @enderror" 
            placeholder="{{ $placeholder }}" 
            {{ $required ? 'required' : '' }}
            autocomplete="off"
        >
        <input 
            type="hidden" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            x-ref="hiddenInput"
            value="{{ $value }}"
        >
    </div>
    
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
