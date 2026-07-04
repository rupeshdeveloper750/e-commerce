@props(['type' => 'text', 'name', 'value' => '', 'placeholder' => ''])

<div class="inp-wrap">
    <i class="ti ti-mail ico-l"></i>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => '']) }}
    />
</div>