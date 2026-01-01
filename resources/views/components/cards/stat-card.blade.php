@props([
    'label',
    'value',
    'change' => null,
    'changeType' => 'success',
    'icon',
    'iconBg' => 'lime'
])

<div class="stat-card">
    <h3 class="stat-label">{{ $label }}</h3>
    <div class="stat-card-inner">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-value">{{ $value }}</p>
                @if($change)
                    <p class="{{ $changeType === 'success' ? 'text-green-600' : ($changeType === 'error' ? 'text-red-600' : 'text-blue-600') }} text-sm font-medium">
                        {{ $change }}
                    </p>
                @endif
            </div>
            <div class="icon-box icon-box-{{ $iconBg }}">
                <i data-lucide="{{ $icon }}" class="w-7 h-7 text-foreground"></i>
            </div>
        </div>
    </div>
</div>
