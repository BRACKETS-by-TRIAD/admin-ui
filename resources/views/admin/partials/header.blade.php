<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
	@if(View::exists('admin.layout.logo'))
        @include('admin.layout.logo')
	@endif
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a role="button" class="dropdown-toggle nav-link">
                <span>
                    @if(Auth::check() && Auth::user()->avatar_thumb_url)
                        <img src="{{ Auth::user()->avatar_thumb_url }}" class="avatar-photo">
                    @elseif(Auth::check() && Auth::user()->first_name && Auth::user()->last_name)
                        <span class="avatar-initials">{{ mb_substr(Auth::user()->first_name, 0, 1) }}{{ mb_substr(Auth::user()->last_name, 0, 1) }}</span>
                    @elseif(Auth::check() && Auth::user()->name)
                        <span class="avatar-initials">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                    @elseif(Auth::guard(config('admin-auth.defaults.guard'))->check() && Auth::guard(config('admin-auth.defaults.guard'))->user()->first_name && Auth::guard(config('admin-auth.defaults.guard'))->user()->last_name)
                        <span class="avatar-initials">{{ mb_substr(Auth::guard(config('admin-auth.defaults.guard'))->user()->first_name, 0, 1) }}{{ mb_substr(Auth::guard(config('admin-auth.defaults.guard'))->user()->last_name, 0, 1) }}</span>
                    @else
                        <span class="avatar-initials"><i class="fa fa-user"></i></span>
                    @endif

                    @if(!is_null(config('admin-auth.defaults.guard')))
                        <span class="hidden-md-down">{{ Auth::guard(config('admin-auth.defaults.guard'))->check() ? Auth::guard(config('admin-auth.defaults.guard'))->user()->full_name : 'Anonymous' }}</span>
                    @else
                        <span class="hidden-md-down">{{ Auth::check() ? Auth::user()->full_name : 'Anonymous' }}</span>
                    @endif

                </span>
                <span class="caret"></span>
            </a>
            @if(View::exists('admin.layout.profile-dropdown'))
                @include('admin.layout.profile-dropdown')
            @endif
        </li>
    </ul>
</header>