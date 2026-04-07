<nav class="nav-links">
    <a class="nav-link {{ request()->routeIs('worker.dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
    @if (! auth()->user()->isAdmin())
        <a class="nav-link {{ request()->routeIs('worker.daily-activity.form') ? 'active' : '' }}" href="{{ route('worker.daily-activity.form') }}">Daily Form</a>
        <a class="nav-link {{ request()->routeIs('worker.submissions') || request()->routeIs('worker.reports.daily') ? 'active' : '' }}" href="{{ route('worker.submissions', ['month' => now()->format('Y-m')]) }}">Monthly Report</a>
    @endif
</nav>
