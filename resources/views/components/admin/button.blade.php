@props(['type' => 'button'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn-login']) }} id="login-btn">
    <i class="ti ti-login"></i>
    {{ $slot }}
</button>