@props(['href' => '#']);
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'text-blue-400 hover:text-blue-300 text-sm']) }}>
    {{ $slot }}
</a>