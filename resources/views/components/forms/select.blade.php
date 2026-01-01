@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Pilih...',
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
    
    <select 
        id="{{ $name }}" 
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-input' . ($error ? ' border-error' : '')]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="text-error text-xs mt-1">{{ $error }}</p>
    @elseif($errors->has($name))
        <p class="text-error text-xs mt-1">{{ $errors->first($name) }}</p>
    @endif
</div>
