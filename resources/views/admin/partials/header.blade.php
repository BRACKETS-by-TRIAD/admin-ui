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
                    <span class="hidden-md-down">{{ Auth::user()->full_name ?: 'Karina Ráchelová' }}</span>
                </span>
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-xs-center"><strong>Account</strong></div>
                <a href="#" class="dropdown-item"><i class="fa fa-bell-o"></i> Updates<span class="badge badge-info">42</span></a>
                <a href="#" class="dropdown-item"><i class="fa fa-envelope-o"></i> Messages<span class="badge badge-success">42</span></a>
                <a href="#" class="dropdown-item"><i class="fa fa-tasks"></i> Tasks<span class="badge badge-danger">42</span></a>
                <a href="#" class="dropdown-item"><i class="fa fa-comments"></i> Comments<span class="badge badge-warning">42</span></a>
                <div class="dropdown-header text-center"><strong>Settings</strong></div>
                <a href="#" class="dropdown-item"><i class="fa fa-user"></i> Profile</a>
                <a href="#" class="dropdown-item"><i class="fa fa-wrench"></i> Settings</a>
                <a href="#" class="dropdown-item"><i class="fa fa-usd"></i> Payments<span class="badge badge-default">42</span></a>
                <a href="#" class="dropdown-item"><i class="fa fa-file"></i> Projects<span class="badge badge-primary">42</span></a>
                <div class="divider"></div>
                <a href="#" class="dropdown-item"><i class="fa fa-shield"></i> Lock Account</a>
                <a href="#" class="dropdown-item"><i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
    </ul>
</header>