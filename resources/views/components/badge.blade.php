@props(['variant' => 'default'])

@php
    $classes = match ($variant) {
        'outline' => 'border border-gray-400 text-gray-700',
        'default' => 'bg-blue-500 text-white',
        default => 'bg-gray-200 text-gray-800',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-block text-sm px-2 py-1 rounded $classes"]) }}>
    {{ $slot }}
</span>
<div>
</div>
