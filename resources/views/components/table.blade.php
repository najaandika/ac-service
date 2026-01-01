@props([
    'headers' => [],
    'striped' => true
])

<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="border-b border-border">
                @foreach($headers as $header)
                    <th class="text-left text-foreground text-sm font-semibold py-3 px-4 first:pl-0 last:pr-0">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="{{ $striped ? '[&>tr:nth-child(even)]:bg-gray-50' : '' }}">
            {{ $slot }}
        </tbody>
    </table>
</div>
