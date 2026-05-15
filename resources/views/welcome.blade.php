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
                <span id="system-status-dot" class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></span>
                <span id="system-status-text" class="text-lg font-medium text-yellow-300">Loading student data...</span>
            </div>

            <div class="text-left xl:text-right gap-1">
                <p id="ph-time" class="text-xl font-semibold">--:--:-- --</p>
                <p id="ph-date" class="text-md text-gray-400">Loading date...</p>
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-300">PH Time UTC+08:00</p>
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
                        <span class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-blue-500/20 text-green-300 border border-green-400/30">
                            Pending...
                        </span>
                        <p><span class="text-gray-400">Name: </span>Pending...</p>
                        <p><span class="text-gray-400">ID No: </span>Pending...</p>
                        <p><span class="text-gray-400">Grade Level: </span>Pending...</p>
                        <p><span class="text-gray-400">Department: </span>Pending...</p>
                        <p><span class="text-gray-400">Course: </span>Pending...</p>
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
                        <span class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30">
                            Pending...
                        </span>
                        <p><span class="text-gray-400">Name: </span>Pending...</p>
                        <p><span class="text-gray-400">ID No: </span>Pending...</p>
                        <p><span class="text-gray-400">Grade Level: </span>Pending...</p>
                        <p><span class="text-gray-400">Department: </span>Pending...</p>
                        <p><span class="text-gray-400">Course: </span>Pending...</p>
                    </div>
                </div>
            </div>

        </div>

        <script>
            const phTimeEl = document.getElementById('ph-time');
            const phDateEl = document.getElementById('ph-date');
            const phTimeZone = 'Asia/Manila';
            const statusDotEl = document.getElementById('system-status-dot');
            const statusTextEl = document.getElementById('system-status-text');

            const timeFormatter = new Intl.DateTimeFormat('en-PH', {
                timeZone: phTimeZone,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
            });

            const dateFormatter = new Intl.DateTimeFormat('en-PH', {
                timeZone: phTimeZone,
                month: 'long',
                day: 'numeric',
                year: 'numeric',
            });

            function updatePhilippineClock() {
                const now = new Date();
                phTimeEl.textContent = timeFormatter.format(now);
                phDateEl.textContent = dateFormatter.format(now);
            }

            function setSystemStatus(state, label) {
                statusTextEl.textContent = label;
                statusDotEl.className = 'w-3 h-3 rounded-full animate-pulse';

                if (state === 'online') {
                    statusDotEl.classList.add('bg-green-400');
                    statusTextEl.className = 'text-lg font-medium text-green-300';
                    return;
                }

                if (state === 'offline') {
                    statusDotEl.classList.add('bg-red-400');
                    statusTextEl.className = 'text-lg font-medium text-red-300';
                    return;
                }

                statusDotEl.classList.add('bg-yellow-400');
                statusTextEl.className = 'text-lg font-medium text-yellow-300';
            }

            async function loadStudents() {
                setSystemStatus('loading', 'Loading student data...');

                try {
                    const response = await fetch('/students');

                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    const payload = await response.json();
                    const students = Array.isArray(payload.students) ? payload.students : [];

                    if (students.length > 0) {
                        setSystemStatus('online', `System is Online - ${students.length} records loaded`);
                        return;
                    }

                    setSystemStatus('offline', 'No student data found');
                } catch (error) {
                    setSystemStatus('offline', 'Failed to load data');
                }
            }

            updatePhilippineClock();
            loadStudents();
            setInterval(updatePhilippineClock, 1000);
        </script>
    </body>
</html>
