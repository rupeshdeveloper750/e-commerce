@props(['name' => 'password'])

<div class="inp-wrap">
    <i class="ti ti-lock ico-l"></i>
    <input
        type="password"
        id="password"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => '']) }}
    />
    <button class="eye-btn" type="button" onclick="togglePassword()">
        <i class="ti ti-eye" id="eye-icon"></i>
    </button>
</div>

<div class="strength-wrap" id="strength-wrap">
    <div class="strength-bars">
        <div class="sb" id="s1"></div>
        <div class="sb" id="s2"></div>
        <div class="sb" id="s3"></div>
        <div class="sb" id="s4"></div>
    </div>
    <div class="strength-txt" id="strength-txt"></div>
</div>