@props(['service'])

<a href="/layanan/{{ $service->slug }}" class="bg-white rounded-[var(--radius-card)] p-6 hover:shadow-lg transition-all hover:-translate-y-1 block group">
    <div class="w-14 h-14 bg-accent-teal rounded-[var(--radius-icon)] flex items-center justify-center mb-4 group-hover:bg-primary transition-colors">
        <i data-lucide="{{ $service->icon }}" class="w-7 h-7 text-foreground group-hover:text-white transition-colors"></i>
    </div>
    <h3 class="text-foreground text-xl font-bold mb-2">{{ $service->name }}</h3>
    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $service->description }}</p>
    <div class="flex items-center justify-between">
        <span class="text-green-600 text-lg font-semibold">{{ $service->starting_price }}</span>
        <span class="text-gray-500 text-sm font-medium group-hover:text-primary group-hover:translate-x-1 transition-all">Lihat Detail →</span>
    </div>
</a>
