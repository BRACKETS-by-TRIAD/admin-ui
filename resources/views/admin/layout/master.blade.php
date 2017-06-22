<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Interface</title>

    <!-- Icons -->
    <link href="/coreui/css/font-awesome.min.css" rel="stylesheet">
    <link href="/coreui/css/simple-line-icons.css" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="/coreui/css/style.css" rel="stylesheet">
    <link href="{{ mix('css/admin/app.css') }}" rel="stylesheet">

</head>

<!-- BODY options, add following classes to body to change options

// Header options
1. '.header-fixed'					- Fixed Header

// Sidebar options
1. '.sidebar-fixed'					- Fixed Sidebar
2. '.sidebar-hidden'				- Hidden Sidebar
3. '.sidebar-off-canvas'		- Off Canvas Sidebar
4. '.sidebar-minimized'			- Minimized Sidebar (Only icons)
5. '.sidebar-compact'			  - Compact Sidebar

// Aside options
1. '.aside-menu-fixed'			- Fixed Aside Menu
2. '.aside-menu-hidden'			- Hidden Aside Menu
3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu

// Footer options
1. '.footer-fixed'						- Fixed footer

-->

<body class="app header-fixed sidebar-compact sidebar-fixed">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">☰</button>
        <a class="navbar-brand" href="#"></a>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item">
                <a class="nav-link navbar-toggler sidebar-toggler" href="#">☰</a>
            </li>

        </ul>
    </header>

    <div class="app-body">
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/post"><i class="icon-speedometer"></i> Posts</a>
                        <a class="nav-link" href="/admin/article"><i class="icon-speedometer"></i> Articles</a>
                        <a class="nav-link" href="/admin/vineyard"><i class="icon-speedometer"></i> Vineyards</a>
                    </li>

                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <main class="main">

            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                <!-- Breadcrumb Menu-->
                <li class="breadcrumb-menu">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>
                        <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                        <a class="btn btn-secondary" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                    </div>
                </li>
            </ol>

            <div class="container-fluid" id="app">
                @yield('content')
            </div>
            <!-- /.conainer-fluid -->

        </main>

    </div>

    <footer class="app-footer">
        SimpleWEB © 2017 BRACKETS by TRIAD s.r.o.
        <span class="float-right">Powered by <a href="https://www.brackets.sk/en">BRACKETS</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    {{--<script src="bower_components/jquery/dist/jquery.min.js"></script>--}}
    {{--<script src="bower_components/tether/dist/js/tether.min.js"></script>--}}
    {{--<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>--}}
    {{--<script src="bower_components/pace/pace.min.js"></script>--}}


    {{--<!-- Plugins and scripts required by all views -->--}}
    {{--<script src="bower_components/chart.js/dist/Chart.min.js"></script>--}}


    <!-- GenesisUI main scripts -->


    <!-- Plugins and scripts required by this views -->

    <!-- Custom scripts required by this view -->
    <script src="{{ mix('/js/admin/admin.js') }}"></script>

</body>

</html>