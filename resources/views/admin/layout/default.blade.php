@extends('brackets/admin-ui::admin.layout.master')

@section('header')
    @include('brackets/admin-ui::admin.partials.header')
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
                    <notifications position="bottom right" :duration="2000" />
                </div>

                @yield('body')
            </div>
        </main>
    </div>
@endsection

@section('footer')
    @include('brackets/admin-ui::admin.partials.footer')
@endsection

@section('bottom-scripts')
    @parent
@endsection