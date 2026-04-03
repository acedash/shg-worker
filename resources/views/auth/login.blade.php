@extends('layouts.app')

@section('content')
    <div class="auth-shell">
        <div class="card auth-card">
            <div class="text-center" style="margin-bottom: 24px;">
                <div style="display:inline-flex; width:56px; height:56px; border-radius:16px; align-items:center; justify-content:center; background:#eff6ff; color:#2563eb; font-size:1.4rem; font-weight:700; margin-bottom:16px;">
                    SHG
                </div>
                <h2 class="auth-title">Login</h2>
                <p class="auth-subtitle">Enter your email and password to continue.</p>
            </div>

            <form method="POST" action="{{ route('login.submit') }}" class="stack">
                @csrf
                <div>
                    <label for="email">Email</label>
                    <input id="email" class="@error('email') input-error @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="password">Password</label>
                    <div class="password-field">
                        <input id="password" class="password-input @error('password') input-error @enderror" type="password" name="password" required>
                        <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                            <svg data-eye-open viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                            <svg data-eye-closed viewBox="0 0 24 24" aria-hidden="true" style="display:none;"><path d="M3 3l18 18" /><path d="M10.6 10.7a3 3 0 004.2 4.2" /><path d="M9.9 5.1A10.9 10.9 0 0112 5c6.5 0 10 7 10 7a13.2 13.2 0 01-3.2 3.9" /><path d="M6.1 6.1A13.2 13.2 0 002 12s3.5 7 10 7a10.9 10.9 0 005.9-1.8" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <label class="muted" style="display:flex; align-items:center; gap:10px; font-weight:500; margin:0;">
                    <input type="checkbox" name="remember" value="1" style="width:auto;" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>

                <button class="button-primary button-block" type="submit">Login</button>
            </form>

            <div class="text-center" style="margin-top: 20px;">
                <p class="muted" style="margin: 0 0 12px;">New worker?</p>
                <a class="button button-secondary button-block" href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>
@endsection
