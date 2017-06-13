<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AdminInterface</title>

    <!-- Latest compiled and minified CSS -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />

</head>
<body>

    <div class="container" id="app">
        @yield('content')
    </div>

    <script src="{{ mix('/js/admin/admin.js') }}"></script>

</body>
</html>
