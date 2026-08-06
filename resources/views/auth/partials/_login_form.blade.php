<div class="form-header">
    <h2 class="form-title">Welcome back</h2>
    <p class="form-subtitle">Sign in to your DonateBazaar account</p>
</div>

@if (session('status'))
    <div class="session-status">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="fields">

        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                </svg>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="rahul@example.com"
                       required autofocus autocomplete="username">
            </div>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap has-toggle">
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="password" id="password" name="password"
                       placeholder="Enter your password"
                       required minlength="8" autocomplete="current-password">
                <button type="button" class="pwd-toggle" onclick="togglePwd('password',this)" aria-label="Show password" aria-pressed="false">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="meta-row">
            <label class="remember-label" for="remember_me">
                <input type="checkbox" id="remember_me" name="remember">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            @endif
        </div>

        <x-button variant="primary" type="submit" fullWidth class="btn-login">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Log In to Your Account
        </x-button>

        <div class="divider"><span>or</span></div>

        <x-button variant="primary" href="{{ route('auth.google') }}" fullWidth class="btn-google">
            <svg width="17" height="17" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continue with Google
        </x-button>

        <x-button variant="primary" href="{{ route('otp.login') }}" fullWidth class="btn-google" style="margin-top:10px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="6" y="2" width="12" height="20" rx="2"/>
                <line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
            Continue with Phone
        </x-button>

        <p class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Create one free</a>
        </p>

    </div>
</form>
