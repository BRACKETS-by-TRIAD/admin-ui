<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">

    <meta name="csrf-token" content="{{ csrf_token() }}">

	{{-- TODO dat suffix do prekladaca --}}
    <title>@yield('title', 'Simpleweb') - Simpleweb</title>

	@include('brackets/admin::admin.partials.main-styles')

    @yield('styles')

</head>

<body class="app header-fixed sidebar-fixed">
    @yield('header')

    @yield('content')

    @yield('footer')

    @include('brackets/admin::admin.partials.main-bottom-scripts')
    @yield('bottom-scripts')
</body>

</html>