<div class="profile-menu">
    @php($roleLabel = auth()->user()->role === 'worker' ? 'Community Mobilizer' : ucfirst(auth()->user()->role))
    <button class="user-chip profile-toggle" type="button" data-profile-toggle aria-expanded="false">
        <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        @if (! empty($showMeta))
            <span class="user-meta">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ $roleLabel }}</span>
            </span>
        @endif
        <span class="profile-caret">▾</span>
    </button>
    <div class="profile-dropdown" data-profile-dropdown>
        <div class="profile-dropdown-header">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ $roleLabel }}</span>
        </div>
        @if (! empty($showMeta))
            <div class="profile-dropdown-note">
                Signed in as {{ auth()->user()->email }}
            </div>
        @endif
        <a href="{{ route('profile.edit') }}">
            Edit Profile
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
