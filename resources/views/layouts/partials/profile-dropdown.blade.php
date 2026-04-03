<div class="profile-menu">
    <button class="user-chip profile-toggle" type="button" data-profile-toggle aria-expanded="false">
        <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        @if (! empty($showMeta))
            <span class="user-meta">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ ucfirst(auth()->user()->role) }}</span>
            </span>
        @endif
        <span class="profile-caret">▾</span>
    </button>
    <div class="profile-dropdown" data-profile-dropdown>
        <div class="profile-dropdown-header">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ ucfirst(auth()->user()->role) }}</span>
        </div>
        @if (! empty($showMeta))
            <div class="profile-dropdown-note">
                Signed in as {{ auth()->user()->email }}
            </div>
        @endif
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
