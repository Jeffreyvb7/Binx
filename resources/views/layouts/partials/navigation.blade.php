<div id="navUnderlay" class="darkUnderlay"></div>
<nav>
    <div class="nav-header">
        <a href="{{ route('home') }}" class="brand">
            <div class="logo"></div>
        </a>
        @auth
            <span>Welcome <a href="{{ route('profile.index') }}">{{ Auth::user()->fullName }}</a>,
                @role('student', 'web')
                Student
                @endrole
                @role('teacher', 'web')
                Teacher
                @endrole
                @role('admin', 'web')
                Admin
                @endrole
            </span>
        @endauth
    </div>
    <ul>
        <li class="{{ in_array(Route::currentRouteName(), ['home']) ? 'active' : '' }}">
            <a href="{{ route('home') }}">Dashboard</a>
        </li>
        <li class="{{ in_array(Route::currentRouteName(), ['studieroute.index', 'studieroute.show', 'studieroute.edit', 'studieroute.create', 'task.show', 'task.create', 'task.edit']) ? 'active' : '' }}">
            <a href="{{ route('studieroute.index') }}">Studieroutes</a>
        </li>
        <li>
            <a href="{{ route('chat') }}">Chat</a>
        </li>
        @role('admin')
        <li>
            <a href="{{ route('admin.index') }}">Admin</a>
        </li>
        @endrole
        @role('admin')
        <li>
            <a href="{{ route('group.index') }}">Groups</a>
        </li>
        @endrole
        <li class="{{ in_array(Route::currentRouteName(), ['profile.index']) ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}">Profile</a>
        </li>
    </ul>
    <div class="nav-footer">
        <a href="{{ route('logout') }}" class="red bold">Logout</a>
    </div>
</nav>
<div id="navTogglerHitbox">
    <div id="navToggler"></div>
</div>