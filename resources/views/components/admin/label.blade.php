@props(['for' => ''])

<label for="{{ $for }}" class="field">
    {{ $slot }}
</label>