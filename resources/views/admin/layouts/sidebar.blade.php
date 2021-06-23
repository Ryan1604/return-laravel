<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#" class="logo">
                PT Dharma Electrindo
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#" class="logo">
                <img src="{{ asset('img/logo.jpeg') }}" width="50" alt="navbar brand">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->path() == 'admin/dashboard' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Data Master</li>
            @if(auth()->user()->isAdmin())
            <li class="{{ request()->segment(2) == 'company' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.company.index') }}">
                    <i class="fas fa-industry"></i>
                    <span>Data Perusahaan</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'products' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-marker"></i>
                    <span>Data Claim Customer</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'users' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>
            @endif
            @if(auth()->user()->isManager())
            <li class="{{ request()->segment(2) == 'company' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.company.index') }}">
                    <i class="fas fa-industry"></i>
                    <span>Data Perusahaan</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'products' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-marker"></i>
                    <span>Data Claim Customer</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'users' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>
            @endif
            <li class="{{ request()->segment(2) == 'report' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.report.index') }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </aside>
    <br><br><br><br>
</div>
