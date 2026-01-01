@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'error' => null
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-foreground text-sm font-medium mb-2">
            {{ $label }}
            @if($required)<span class="text-error">*</span>@endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <i data-lucide="{{ $icon }}" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
        @endif
        
        <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'form-input' . ($icon ? ' form-input-icon' : '') . ($error ? ' border-error' : '')]) }}
        >
    </div>
    
    @if($error)
        <p class="text-error text-xs mt-1">{{ $error }}</p>
    @elseif($errors->has($name))
        <p class="text-error text-xs mt-1">{{ $errors->first($name) }}</p>
    @endif
</div>
