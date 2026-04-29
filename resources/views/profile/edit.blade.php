@extends('layouts.app')

@section('content')
    <div class="hero">
        <div class="spaced">
            <div>
                <span class="page-kicker">Account</span>
                <h2>Edit Profile</h2>
                <p>Update your basic details. Password is optional.</p>
            </div>
        </div>
    </div>

    <div class="form-layout-minimal">
        <div class="card">
            @if (session('status'))
                <div class="subtle-panel" style="margin-bottom: 16px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="stack">
                @csrf

                <div class="form-grid">
                    <div>
                        <label for="name">Community Mobilizer Name</label>
                        <input id="name" class="@error('name') input-error @enderror" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="phone">Phone Number</label>
                        <input id="phone" class="@error('phone') input-error @enderror" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="district_id">District Name</label>
                        <select id="district_id" class="@error('district_id') input-error @enderror" name="district_id">
                            <option value="">Select district</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" {{ (string) old('district_id', $user->district_id) === (string) $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="ulb_id">ULB Name</label>
                        <select id="ulb_id" class="@error('ulb_id') input-error @enderror" name="ulb_id">
                            <option value="">Select ULB</option>
                            @foreach ($districts as $district)
                                @foreach ($district->ulbs as $ulb)
                                    <option
                                        value="{{ $ulb->id }}"
                                        data-district-id="{{ $district->id }}"
                                        {{ (string) old('ulb_id', $user->ulb_id) === (string) $ulb->id ? 'selected' : '' }}
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
                        <label for="assigned_ward">Assigned Ward</label>
                        <input id="assigned_ward" class="@error('assigned_ward') input-error @enderror" type="text" name="assigned_ward" value="{{ old('assigned_ward', $user->assigned_ward) }}">
                        @error('assigned_ward')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="text" value="{{ $user->email }}" disabled>
                        <p class="muted" style="margin: 6px 0 0;">Email can’t be changed here.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div>
                        <label for="password">New Password</label>
                        <div class="password-field">
                            <input id="password" class="password-input @error('password') input-error @enderror" type="password" name="password">
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
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="password-field">
                            <input id="password_confirmation" class="password-input @error('password_confirmation') input-error @enderror" type="password" name="password_confirmation">
                            <button type="button" class="password-toggle" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                                <svg data-eye-open viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                <svg data-eye-closed viewBox="0 0 24 24" aria-hidden="true" style="display:none;"><path d="M3 3l18 18" /><path d="M10.6 10.7a3 3 0 004.2 4.2" /><path d="M9.9 5.1A10.9 10.9 0 0112 5c6.5 0 10 7 10 7a13.2 13.2 0 01-3.2 3.9" /><path d="M6.1 6.1A13.2 13.2 0 002 12s3.5 7 10 7a10.9 10.9 0 005.9-1.8" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="action-row-inline">
                    <button class="button button-primary" type="submit">Save Changes</button>
                    <a class="button button-secondary" href="{{ route('dashboard') }}">Back</a>
                </div>
            </form>
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

