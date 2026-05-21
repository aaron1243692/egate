@extends('layouts.clean')

@section('clean')

    @include('layouts.header')

    @yield('content')

    @include('layouts.admin-shortcuts')

@endsection
