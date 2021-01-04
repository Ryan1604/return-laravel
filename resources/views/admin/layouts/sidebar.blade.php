<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('public.index') }}" class="logo">
                <img src="{{ asset('img/npic-logo.svg') }}" alt="navbar brand">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('public.index') }}" class="logo">
                <img src="{{ asset('img/npic-pic.svg') }}" alt="navbar brand">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->path() == 'admin/dashboard' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">MANAGE</li>
            <li class="dropdown {{ request()->segment(2) == 'items' ? 'active' : '' }}">
                <a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-book"></i> <span>Items</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="{{ request()->segment(3) == 'books' ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.items.books.index') }}">Books</a></li>
                    <li class="{{ request()->segment(3) == 'ebooks' ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.items.ebooks.index') }}">E-Books</a></li>
                    <li class="{{ request()->segment(3) == 'lostBooks' ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.items.lostBooks.index') }}">Lost Books</a></li>
                </ul>
            </li>
            <li class="{{ request()->segment(2) == 'categories' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-hashtag"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'authors' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.authors.index') }}">
                    <i class="fas fa-marker"></i>
                    <span>Authors</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'publishers' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.publishers.index') }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Publishers</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'racks' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.racks.index') }}">
                    <i class="fas fa-columns"></i>
                    <span>Racks</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'roles' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-address-book"></i>
                    <span>Roles</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'users' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-user"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="{{ request()->segment(2) == 'feedback' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.feedback') }}">
                    <i class="fas fa-comments"></i>
                    <span>Feedback</span>
                </a>
            </li>
            <li class="dropdown {{ request()->segment(2) == 'issues' ? 'active' : '' }}">
                <a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-exchange-alt"></i> <span>Issues</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="{{ request()->segment(3) == 'borrows' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.issues.borrows.index') }}">Borrows</a>
                    </li>
                    <li class="{{ request()->path() == 'admin/issues/returns' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.issues.returns.index') }}">Returns</a>
                    </li>
                    <li class="{{ request()->path() == 'admin/issues/penaltySetting' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.issues.penaltySetting') }}">Penalty Setting</a>
                    </li>
                    <li class="{{ request()->path() == 'admin/issues/borrowSetting' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.issues.borrowSetting') }}">Borrow Setting</a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
    <br><br><br><br>
</div>
