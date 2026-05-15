<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
class="w-full h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EGATE') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex flex-col p-2 gap-2">

        <!-- HEADER -->
        <header class="w-full bg-white/10 backdrop-blur-md border border-white/10 rounded-xl py-2 px-3 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3">

            <div>
                <h1 class="text-l font-bold tracking-wide">eGate Monitoring System</h1>
                <p class="text-md text-gray-300">Student Entry Tracking Dashboard</p>
            </div>

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-lg font-medium text-green-300">System is Online</span>
            </div>

            <div class="text-left xl:text-right gap-1">
                <p class="text-xl font-semibold">12:00:00 PM</p>
                <p class="text-md text-gray-400">May 15, 2026</p>
            </div>

        </header>

        <!-- GRID -->
        <div class="w-full flex-1 grid grid-cols-1 lg:grid-cols-2 gap-3">

            <!-- CURRENT -->
            <div class="relative bg-gradient-to-br from-green-500/10 to-white/5 border border-green-400/20 rounded-xl p-4 flex flex-col gap-4 shadow-lg">

                <!-- glow accent -->
                <!-- <div class="absolute top-0 left-0 w-full h-1 bg-green-400 rounded-t-xl"></div> -->

                <h2 class="text-sm font-semibold text-green-300 border-b border-white/10 pb-2">
                    CURRENT ENTRY
                </h2>

                <div class="flex gap-4 items-center">
                    <img src="https://via.placeholder.com/300"
                        class="w-[300px] h-[300px] object-cover rounded-lg border border-green-400/30 shadow-md" />

                    <div class="flex flex-col gap-1 text-lg text-gray-200">
                        <span class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-green-500/20 text-green-300 border border-green-400/30">
                            Log In
                        </span>
                        <p><span class="text-gray-400">Name:</span> Doe, John A.</p>
                        <p><span class="text-gray-400">ID No:</span> 2026-0001</p>
                        <p><span class="text-gray-400">Grade Level:</span> 12</p>
                        <p><span class="text-gray-400">Department:</span> STEM</p>
                        <p><span class="text-gray-400">Course:</span> ICT</p>
                    </div>
                </div>
            </div>

            <!-- PREVIOUS -->
            <div class="relative bg-gradient-to-br from-blue-500/10 to-white/5 rounded-xl p-4 flex flex-col gap-4 shadow-lg">

                <!-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-400 rounded-t-xl"></div> -->

                <h2 class="text-sm font-semibold text-blue-300 border-b border-white/10 pb-2">
                    PREVIOUS ENTRY
                </h2>

                <div class="flex gap-4 items-center">
                    <img src="https://via.placeholder.com/300"
                        class="w-[300px] h-[300px] object-cover rounded-lg border border-blue-400/30 shadow-md" />

                    <div class="flex flex-col gap-1 text-lg text-gray-200">
                        <span class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-red-500/20 text-red-300 border border-red-400/30">
                            LOGOUT
                        </span>
                        <p><span class="text-gray-400">Name:</span> Smith, Jane B.</p>
                        <p><span class="text-gray-400">ID No:</span> 2026-0002</p>
                        <p><span class="text-gray-400">Grade Level:</span> 11</p>
                        <p><span class="text-gray-400">Department:</span> ABM</p>
                        <p><span class="text-gray-400">Course:</span> Business</p>
                    </div>
                </div>
            </div>

        </div>

    </body>
</html>
