<header class="app-header navbar">
	<button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">☰</button>
    <a href="#" class="navbar-brand">
        {{--<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Ikea_logo.svg/640px-Ikea_logo.svg.png" alt="">--}}
        Simpleweb
    </a>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a role="button" class="dropdown-toggle nav-link">
                <span>
                    {{-- TODO ked bude hotova moznost pridania avataru, tak checknut, ci nema avatar a ak nie, tak potom az fallbacknut na tieto iniciale --}}
                    @if(false)
                        <img src="https://scontent-vie1-1.xx.fbcdn.net/v/t1.0-9/13244724_10208295874827525_7511406720245766894_n.jpg?oh=e2e824a75303d406e2c6f0817b55dc6b&oe=59F9FFA7" alt="admin@bootstrapmaster.com" class="img-avatar">
                    @elseif(Auth::check() && Auth::user()->first_name && Auth::user()->last_name)
                        <span class="avatar-initials">{{ mb_substr(Auth::user()->first_name, 0, 1) }}{{ mb_substr(Auth::user()->last_name, 0, 1) }}</span>
                    @else
                        <span class="avatar-initials"><i class="fa fa-user"></i></span>
                    @endif

                    <span class="hidden-md-down">{{ Auth::check() ? Auth::user()->full_name : 'Karina Ráchelová' }}</span>
                </span>
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center"><strong>Account</strong></div>
                <a href="{{ url('admin/profile') }}" class="dropdown-item"><i class="fa fa-user"></i> Profile</a>
                <a href="{{ url('admin/password') }}" class="dropdown-item"><i class="fa fa-key"></i> Password</a>
                <a href="#" class="dropdown-item"><i class="fa fa-wrench"></i> Settings</a>
                <a href="{{ url('admin/logout') }}" class="dropdown-item"><i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
    </ul>
</header>