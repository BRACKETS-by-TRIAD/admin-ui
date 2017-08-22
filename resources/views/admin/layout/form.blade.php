@extends('brackets/admin::admin.layout.master')

@section('header')
    @include('brackets/admin::admin.partials.header')
@endsection

@section('content')

    <div class="app-body">

        @if(View::exists('admin.layout.sidebar'))
            @include('admin.layout.sidebar')
        @endif

        <main class="main">

            <div class="container-fluid" id="app" :class="{'loading': loading}">
                <div>
                    <notifications position="bottom right" :duration="1000" />
                </div>
                @yield('title')

                @if (isset($errors) && count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('body')
            </div>

        </main>

    </div>
@stop

@section('bottom-scripts')
    @parent
@endsection