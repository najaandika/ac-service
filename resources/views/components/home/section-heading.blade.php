@props(['title', 'subtitle' => null])

<div class="text-center mb-12">
    <h2 class="text-foreground text-3xl md:text-4xl font-extrabold mb-4">{{ $title }}</h2>
    @if($subtitle)
    <p class="text-gray-600 text-lg max-w-2xl mx-auto">{{ $subtitle }}</p>
    @endif
</div>
