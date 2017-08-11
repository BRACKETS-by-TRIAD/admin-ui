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

            <li class="nav-item"><a class="nav-link" href="{{ url('admin/user') }}"><i class="icon-user"></i> {{ __('Manage access') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/translation') }}"><i class="icon-location-pin"></i> {{ __('Translations') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/configuration') }}"><i class="icon-settings"></i> {{ __('Configuration') }}</a></li>
        </ul>
    </nav>
</div>