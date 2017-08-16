{{--<header class="app-header navbar">--}}
{{--<button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">☰</button>--}}
{{--<a class="navbar-brand" href="#"></a>--}}
{{--<ul class="nav navbar-nav d-md-down-none">--}}
{{--<li class="nav-item">--}}
{{--<a class="nav-link navbar-toggler sidebar-toggler" href="#">☰</a>--}}
{{--</li>--}}
{{--</ul>--}}
{{--</header>--}}
<header class="app-header navbar">
    <button type="button" class="navbar-toggler mobile-sidebar-toggler hidden-md-up">☰</button>
    <a href="#" class="navbar-brand"></a>
    <ul class="nav navbar-nav hidden-md-down">
        <li class="nav-item"><a href="#" class="nav-link navbar-toggler sidebar-toggler">☰</a></li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a role="button" class="dropdown-toggle nav-link">
                <span>
                    <img src="https://scontent-vie1-1.xx.fbcdn.net/v/t1.0-9/13244724_10208295874827525_7511406720245766894_n.jpg?oh=e2e824a75303d406e2c6f0817b55dc6b&oe=59F9FFA7" alt="admin@bootstrapmaster.com" class="img-avatar">
                    <span class="hidden-md-down">{{ Auth::check() ? Auth::user()->full_name : 'Karina Ráchelová' }}</span>
                </span>
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center"><strong>Account</strong></div>
                <a href="{{ route('admin/profile/edit') }}" class="dropdown-item"><i class="fa fa-user"></i> Profile</a>
                <a href="{{ route('admin/password/edit') }}" class="dropdown-item"><i class="fa fa-key"></i> Password</a>
                <a href="#" class="dropdown-item"><i class="fa fa-wrench"></i> Settings</a>
                <a href="{{ route('brackets/admin-auth:admin/logout') }}" class="dropdown-item"><i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
    </ul>
</header>