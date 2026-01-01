@props(['number', 'title', 'description'])

<div class="text-center">
    <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-3xl font-bold">{{ $number }}</div>
    <h3 class="text-foreground text-xl font-bold mb-2">{{ $title }}</h3>
    <p class="text-gray-600">{{ $description }}</p>
</div>
