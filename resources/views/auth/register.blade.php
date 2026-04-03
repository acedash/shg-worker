@extends('layouts.app')

@section('content')
    <div class="auth-shell">
        <div class="card auth-card" style="max-width: 560px;">
            <h2 class="auth-title">Register</h2>
            <p class="auth-subtitle">Create a clean worker account to start daily reporting.</p>

            <form method="POST" action="{{ route('register.submit') }}" class="stack">
                @csrf
                <div class="form-grid">
                    <div>
                        <label for="name">Worker Name</label>
                        <input id="name" class="@error('name') input-error @enderror" type="text" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="phone">Phone Number</label>
                        <input id="phone" class="@error('phone') input-error @enderror" type="text" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="district_name">District Name</label>
                        <select id="district_id" class="@error('district_id') input-error @enderror" name="district_id" required>
                            <option value="">Select district</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" {{ (string) old('district_id') === (string) $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="ulb_name">ULB Name</label>
                        <select id="ulb_id" class="@error('ulb_id') input-error @enderror" name="ulb_id" required>
                            <option value="">Select ULB</option>
                            @foreach ($districts as $district)
                                @foreach ($district->ulbs as $ulb)
                                    <option
                                        value="{{ $ulb->id }}"
                                        data-district-id="{{ $district->id }}"
                                        data-status="{{ $ulb->status }}"
                                        {{ (string) old('ulb_id') === (string) $ulb->id ? 'selected' : '' }}
                                    >
                                        {{ $ulb->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('ulb_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" class="@error('email') input-error @enderror" type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
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
                    <div>
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-field">
                            <input id="password_confirmation" class="password-input @error('password_confirmation') input-error @enderror" type="password" name="password_confirmation" required>
                            <button type="button" class="password-toggle" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                                <svg data-eye-open viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                <svg data-eye-closed viewBox="0 0 24 24" aria-hidden="true" style="display:none;"><path d="M3 3l18 18" /><path d="M10.6 10.7a3 3 0 004.2 4.2" /><path d="M9.9 5.1A10.9 10.9 0 0112 5c6.5 0 10 7 10 7a13.2 13.2 0 01-3.2 3.9" /><path d="M6.1 6.1A13.2 13.2 0 002 12s3.5 7 10 7a10.9 10.9 0 005.9-1.8" /></svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button class="button-primary button-block" type="submit">Create Account</button>
            </form>

            <div class="text-center" style="margin-top: 18px;">
                <a class="muted" href="{{ route('login') }}" style="text-decoration:none;">Already have an account? Login</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var districtSelect = document.getElementById('district_id');
            var ulbSelect = document.getElementById('ulb_id');

            if (!districtSelect || !ulbSelect) {
                return;
            }

            var placeholder = ulbSelect.querySelector('option[value=""]');
            var options = Array.from(ulbSelect.querySelectorAll('option[data-district-id]'));

            function syncUlbs() {
                var districtId = districtSelect.value;
                var currentValue = ulbSelect.value;
                var hasVisibleSelected = false;

                options.forEach(function (option) {
                    var visible = districtId && option.dataset.districtId === districtId;
                    option.hidden = !visible;

                    if (!visible && option.selected) {
                        option.selected = false;
                    }

                    if (visible && option.value === currentValue) {
                        hasVisibleSelected = true;
                    }
                });

                if (!hasVisibleSelected) {
                    ulbSelect.value = '';
                }

                placeholder.textContent = districtId ? 'Select ULB' : 'Select district first';
            }

            districtSelect.addEventListener('change', syncUlbs);
            syncUlbs();
        })();
    </script>
@endsection
