@props([
    'active' => false,
    'route' => null,
    'disabled' => false
])

<div x-data="{ isActive: {{ $active ? 'true' : 'false' }}, loading: false }">
    <button 
        type="button"
        @if($route)
        @click="
            loading = true;
            fetch('{{ $route }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                isActive = data.is_active;
                loading = false;
            })
            .catch(() => { loading = false; });
        "
        @endif
        :class="isActive ? 'toggle-switch-active' : 'toggle-switch-inactive'"
        class="toggle-switch"
        :disabled="loading || {{ $disabled ? 'true' : 'false' }}"
        {{ $attributes }}
    >
        <span 
            :class="isActive ? 'toggle-switch-dot-active' : 'toggle-switch-dot-inactive'"
            class="toggle-switch-dot"
        ></span>
    </button>
</div>
