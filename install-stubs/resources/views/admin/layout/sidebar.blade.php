<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">Content</li>
            {{-- Do not delete me :) I'm used for auto-generation menu items --}}

            {{--<li class="nav-title">E-shop</li>--}}

            {{--<li class="nav-item"><a class="nav-link" href="#"><i class="icon-basket"></i> Orders</a></li>--}}
            {{--<li class="nav-item"><a class="nav-link" href="#"><i class="icon-grid"></i> Products</a></li>--}}
            {{--<li class="nav-item"><a class="nav-link" href="#"><i class="icon-people"></i> Customers</a></li>--}}

            <li class="nav-title">Settings</li>

            <li class="nav-item"><a class="nav-link" href="{{ url('admin/user') }}"><i class="icon-user"></i> <span class="nav-link-text">{{ __('Manage access') }}</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/translation') }}"><i class="icon-location-pin"></i> <span class="nav-link-text">{{ __('Translations') }}</span></a></li>
            {{--<li class="nav-item"><a class="nav-link" href="{{ url('admin/configuration') }}"><i class="icon-settings"></i> <span class="nav-link-text">{{ __('Configuration') }}</span></a></li>--}}
        </ul>

        <div class="sidebar-collapse">
            <i class="fa fa-angle-double-left"></i>
        </div>
    </nav>
</div>