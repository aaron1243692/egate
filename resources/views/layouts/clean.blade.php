<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
class="w-full h-full m-0 p-0 gap-0">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'EGATE') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="w-full h-full m-0 p-0 gap-0
flex flex-col items-center">
    @yield('clean')
</body>
</html>
