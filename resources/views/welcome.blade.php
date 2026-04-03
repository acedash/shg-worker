@extends('layouts.app')

@section('content')
    <div class="hero">
        <h2>Daily reporting for SHG workers</h2>
        <p>Register workers, record daily field activity based on the reporting format you shared, and generate monthly reports from the admin dashboard whenever needed.</p>
    </div>

    <div class="grid grid-3">
        <div class="card">
            <h3>Worker Registration</h3>
            <p class="muted">Each worker gets a separate login with district and ULB details so reports stay organized.</p>
        </div>
        <div class="card">
            <h3>Daily Activity Entry</h3>
            <p class="muted">Workers can submit one activity form per day and update it later if corrections are needed.</p>
        </div>
        <div class="card">
            <h3>Monthly Reports</h3>
            <p class="muted">Admins can see all registered workers and download monthly CSV reports for each worker.</p>
        </div>
    </div>

    <div style="margin-top: 24px;" class="card">
        <div class="spaced">
            <div>
                <h3>Get Started</h3>
                <p class="muted">Create a worker account or sign in as the admin to review submissions.</p>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a class="button button-secondary" href="{{ route('login') }}">Login</a>
                <a class="button button-primary" href="{{ route('register') }}">Register Worker</a>
            </div>
        </div>
    </div>
@endsection
