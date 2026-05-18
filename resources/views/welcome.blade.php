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
    <body class="min-h-screen bg-gradient-to-br from-[#f5f0e6] via-[#eee7db] to-[#e6dece] text-stone-800 flex flex-col p-2 gap-2">

        <!-- HEADER -->
        <header class="w-full bg-[#f7f1e7]/90 backdrop-blur-md border border-[#d9cfbe] rounded-xl py-2 px-3 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3">

            <div>
                <h1 class="text-l font-bold tracking-wide text-stone-900">eGate Monitoring System</h1>
                <p class="text-md text-stone-600">Student Entry Tracking Dashboard</p>
            </div>

            <div class="flex items-center gap-2">
                <span id="system-status-dot" class="w-3 h-3 bg-amber-500 rounded-full animate-pulse"></span>
                <span id="system-status-text" class="text-lg font-medium text-amber-700">Loading student data...</span>
            </div>

            <div class="text-left xl:text-right gap-1">
                <p id="ph-time" class="text-xl font-semibold text-stone-900">--:--:-- --</p>
                <p id="ph-date" class="text-md text-stone-500">Loading date...</p>
                <p class="text-xs uppercase tracking-[0.3em] text-sky-700">PH Time UTC+08:00</p>
            </div>

        </header>

        <!-- GRID -->
        <div class="w-full flex-1 grid grid-cols-1 lg:grid-cols-2 gap-3">

            <!-- CURRENT -->
            <div class="relative bg-gradient-to-br from-[#f3ede1] to-[#ebe4d6] border border-[#d9cfbe] rounded-xl p-4 flex flex-col gap-4 shadow-lg">

                <!-- glow accent -->
                <!-- <div class="absolute top-0 left-0 w-full h-1 bg-green-400 rounded-t-xl"></div> -->

                <h2 class="text-sm font-semibold text-emerald-800 border-b border-[#d9cfbe] pb-2">
                    CURRENT ENTRY
                </h2>

                <div class="flex gap-4 items-center">
                    <img id="current-image" src="https://via.placeholder.com/300"
                        class="w-[300px] h-[300px] object-cover rounded-lg border border-[#cdbfaa] shadow-md" />

                    <div class="flex flex-col gap-1 text-lg text-stone-700">
                        <span id="current-status" class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-[#e7dece] text-stone-700 border border-[#d4c7b3]">
                            Pending...
                        </span>
                        <p><span class="text-stone-500">Name: </span><span id="current-name" class="text-stone-900">Pending...</span></p>
                        <p><span class="text-stone-500">ID No: </span><span id="current-id" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Grade Level: </span><span id="current-grade" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Department: </span><span id="current-department" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Time: </span><span id="current-course" class="text-stone-800">Pending...</span></p>
                    </div>
                </div>
            </div>

            <!-- PREVIOUS -->
            <div class="relative bg-gradient-to-br from-[#f3ede1] to-[#ebe4d6] border border-[#d9cfbe] rounded-xl p-4 flex flex-col gap-4 shadow-lg">

                <!-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-400 rounded-t-xl"></div> -->

                <h2 class="text-sm font-semibold text-sky-800 border-b border-[#d9cfbe] pb-2">
                    PREVIOUS ENTRY
                </h2>

                <div class="flex gap-4 items-center">
                    <img id="previous-image" src="https://via.placeholder.com/300"
                        class="w-[300px] h-[300px] object-cover rounded-lg border border-[#cdbfaa] shadow-md" />

                    <div class="flex flex-col gap-0 text-lg text-stone-700">
                        <span id="previous-status" class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-[#e7dece] text-stone-700 border border-[#d4c7b3]">
                            Pending...
                        </span>
                        <p><span class="text-stone-500">Name: </span><span id="previous-name" class="text-stone-900">Pending...</span></p>
                        <p><span class="text-stone-500">ID No: </span><span id="previous-id" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Grade Level: </span><span id="previous-grade" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Department: </span><span id="previous-department" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Course: </span><span id="previous-course" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Time: </span><span id="previous-course" class="text-stone-800">Pending...</span></p>
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
            let lastSystemStatusState = null;

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
                if (lastSystemStatusState === state) {
                    return;
                }

                lastSystemStatusState = state;
                statusTextEl.textContent = label;
                statusDotEl.className = 'w-3 h-3 rounded-full animate-pulse';

                if (state === 'online') {
                    statusDotEl.classList.add('bg-emerald-600');
                    statusTextEl.className = 'text-lg font-medium text-emerald-800';
                    return;
                }

                if (state === 'offline') {
                    statusDotEl.classList.add('bg-rose-500');
                    statusTextEl.className = 'text-lg font-medium text-rose-700';
                    return;
                }

                statusDotEl.classList.add('bg-amber-500');
                statusTextEl.className = 'text-lg font-medium text-amber-700';
            }

            function fillStudent(prefix, student) {
                const imageEl = document.getElementById(`${prefix}-image`);
                const statusEl = document.getElementById(`${prefix}-status`);
                const rawStatus = String(student?.status || '').trim().toLowerCase();
                const status = rawStatus === 'login'
                    ? 'Log In'
                    : rawStatus === 'logout'
                        ? 'Log Out'
                        : (student?.status || 'Pending...');

                imageEl.src = student?.image || 'https://via.placeholder.com/300';
                imageEl.alt = student?.name || 'Student image';
                statusEl.textContent = status;
                statusEl.className = 'px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full border';

                if (rawStatus === 'login' || status === 'Log In') {
                    statusEl.classList.add('bg-emerald-100', 'text-emerald-800', 'border-emerald-300');
                } else if (rawStatus === 'logout' || status === 'Log Out') {
                    statusEl.classList.add('bg-rose-100', 'text-rose-800', 'border-rose-300');
                } else {
                    statusEl.classList.add('bg-[#e7dece]', 'text-stone-700', 'border-[#d4c7b3]');
                }

                document.getElementById(`${prefix}-name`).textContent = student?.name || 'Pending...';
                document.getElementById(`${prefix}-id`).textContent = student?.student_number || 'Pending...';
                document.getElementById(`${prefix}-grade`).textContent = student?.grade_level || 'Pending...';
                document.getElementById(`${prefix}-department`).textContent = student?.department || 'Pending...';
                document.getElementById(`${prefix}-course`).textContent = student?.course || 'Pending...';
            }

            async function loadStudents() {
                try {
                    const response = await fetch('/students');

                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    const payload = await response.json();
                    const students = Array.isArray(payload.students) ? payload.students : [];

                    fillStudent('current', students[0] || null);
                    fillStudent('previous', students[1] || null);

                    if (students.length > 0) {
                        setSystemStatus('online', 'System is Online');
                        return;
                    }

                    fillStudent('current', null);
                    fillStudent('previous', null);
                    setSystemStatus('offline', 'Failed to load data');
                } catch (error) {
                    fillStudent('current', null);
                    fillStudent('previous', null);
                    setSystemStatus('offline', 'Failed to load data');
                }
            }

            updatePhilippineClock();
            loadStudents();
            setInterval(updatePhilippineClock, 1000);
            setInterval(loadStudents, 5000);
        </script>
    </body>
</html>
