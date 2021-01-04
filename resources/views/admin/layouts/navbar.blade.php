<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav">
        <li>
            <a href="javascript:void(0)" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="ml-auto navbar-nav navbar-right">
        <li class="dropdown"><a href="javascript:void(0)" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('img/users/' . auth()->user()->profile_url) }}"
                    class="rounded-circle mr-1" width="30px" height="30px">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Logged in {{ auth()->user()->updated_at->diffForHumans() }}
                </div>
                <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{ route('admin.changePassword') }}" class="dropdown-item has-icon">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
