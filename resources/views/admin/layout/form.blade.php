@extends('brackets/admin::admin.layout.master')

@section('content')

    @yield('title')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-horizontal" action="{{ $action }}" method="post" @submit.prevent="onSubmit">

        {{ csrf_field() }}

        @yield('body')

    </form>

@stop