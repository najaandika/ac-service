@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'rows' => 4,
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
    
    <textarea 
        id="{{ $name }}" 
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-input resize-none' . ($error ? ' border-error' : '')]) }}
    >{{ old($name, $value) }}</textarea>
    
    @if($error)
        <p class="text-error text-xs mt-1">{{ $error }}</p>
    @elseif($errors->has($name))
        <p class="text-error text-xs mt-1">{{ $errors->first($name) }}</p>
    @endif
</div>
