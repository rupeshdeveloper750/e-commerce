<x-admin.auth-layout>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="ti ti-alert-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.login.store') }}" method="POST" id="login-form">
        @csrf

        <div class="field">
            <x-admin.label for="email">Email Address</x-admin.label>
            <x-admin.input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="admin@shopme.com"
                autocomplete="email"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                required autofocus
            />
            <x-admin.error field="email" />
        </div>

        <div class="field">
            <x-admin.label for="password">Password</x-admin.label>
            <x-admin.password
                name="password"
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                oninput="updateStrength(this.value)"
                required
                placeholder="••••••••"
            />
            <x-admin.error field="password" />
        </div>

        <div class="opts">
            <label class="remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                <span>Remember me</span>
            </label>
            @if(Route::has('admin.password.request'))
                <a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot password?</a>
            @endif
        </div>

        <x-admin.button type="submit">Sign in</x-admin.button>

    </form>

</x-admin.auth-layout>