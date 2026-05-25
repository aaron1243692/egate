@extends('layouts.clean')

@section('body-class', 'eg-admin-shell')

@section('clean')

    @include('layouts.header')

    <div class="eg-admin-main">
        @yield('content')
    </div>

    @include('layouts.admin-shortcuts')

@endsection
