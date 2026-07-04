@props(['field'])

@error($field)
    <div class="invalid-feedback">
        <i class="ti ti-alert-circle"></i>
        {{ $message }}
    </div>
@enderror