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

        @include('admin.layout.sidebar')

        <main class="main">

            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{ url('admin/post') }}">Posts</a></li>
                <li class="breadcrumb-item active">New post</li>
            </ol>

            <div class="container-fluid" id="app">
                @yield('content')
            </div>

        </main>

    </div>

    <footer class="app-footer">
        SimpleWEB © 2017 BRACKETS by TRIAD s.r.o.
        <span class="float-right">Powered by <a href="https://www.brackets.sk/en">BRACKETS</a></span>
    </footer>

    <script src="{{ mix('/js/admin/admin.js') }}"></script>

</body>

</html>