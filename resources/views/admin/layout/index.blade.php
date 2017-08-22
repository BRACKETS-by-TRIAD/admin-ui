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
                <div class="modals">
                    <v-dialog/>
                </div>
                <div>
                    <notifications position="bottom right" :duration="1000" />
                </div>

                @yield('title')

                @yield('body')
            </div>

        </main>

    </div>
@endsection

@section('bottom-scripts')
    @parent
@endsection