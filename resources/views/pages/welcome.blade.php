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
    <body class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 text-stone-800 flex flex-col p-2 gap-2 overflow-hidden">
        <!-- HEADER -->
        <header class="w-full bg-white/90 backdrop-blur-md border border-slate-200 rounded-xl py-2 px-3 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3">

            <div class="flex items-center gap-1">
                <img src="{{ asset('images/olpcc-logo.png') }}"
                    alt="Logo"
                    class="w-20 h-20 rounded-full object-cover">

                <h1 class="text-lg font-bold tracking-wide text-stone-900">
                    OSMIS-eGATE
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <span id="system-status-dot" class="w-3 h-3 bg-amber-500 rounded-full animate-pulse"></span>
                <span id="system-status-text" class="text-lg font-medium text-amber-700">Loading student data...</span>
            </div>

            <div class="text-left xl:text-right gap-1">
                <p id="ph-time" class="text-xl font-semibold text-stone-900">--:--:-- --</p>
                <p id="ph-date" class="text-md text-black/80">Loading date...</p>
            </div>

        </header>

        <!-- GRID -->
        <div class="w-full flex-1 grid grid-cols-1 lg:grid-cols-2 gap-3">

            <!-- CURRENT -->
            <div class="relative bg-gradient-to-br from-white to-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col gap-4 shadow-lg">

                <!-- glow accent -->
                <!-- <div class="absolute top-0 left-0 w-full h-1 bg-green-400 rounded-t-xl"></div> -->

                <h2 class="text-sm font-semibold text-emerald-800 border-b border-slate-200 pb-2">
                    CURRENT ENTRY
                </h2>

                <div class="flex gap-4 items-center">
                    <img id="current-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Crect width='300' height='300' fill='%23f1f5f9'/%3E%3Ccircle cx='150' cy='112' r='46' fill='%23cbd5e1'/%3E%3Cpath d='M72 244c16-42 52-68 78-68s62 26 78 68' fill='%23cbd5e1'/%3E%3C/svg%3E"
                        class="w-[300px] h-[300px] object-cover rounded-lg border border-slate-200 shadow-md" />

                    <div class="flex flex-col gap-0 text-md text-stone-700">
                        <span id="current-status" class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-slate-100 text-stone-700 border border-slate-200">
                            Pending...
                        </span>
                        <p><span class="text-stone-500">Name: </span><span id="current-name" class="text-stone-900">Pending...</span></p>
                        <p><span class="text-stone-500">ID No: </span><span id="current-id" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Grade Level: </span><span id="current-grade" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Department: </span><span id="current-department" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Course: </span><span id="current-course" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Time: </span><span id="current-time" class="text-stone-800">Pending...</span></p>
                    </div>

                </div>

                <div class="w-full flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    @if ($manualEntryEnabled)
                    <label
                    for="student_id"
                    class="sm:min-w-[120px] text-xs font-bold uppercase tracking-wider text-slate-500 sm:text-right"
                    >Student.
                    </label>

                    <input
                    autofocus
                    type="text"
                    name="student_id"
                    id="student_id"
                    placeholder="Enter student number"
                    class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800
                        placeholder-slate-400 outline-none shadow-sm transition duration-200
                        focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                    >
                    @endif

                    @if ($rfidLoginEnabled)
                    <input
                    type="text"
                    name="rfid"
                    id="rfid"
                    tabindex="-1"
                    autocomplete="off"
                    aria-hidden="true"
                    class="absolute h-0 w-0 opacity-0 pointer-events-none"
                    >
                    @endif

                    @if ($manualEntryEnabled || $rfidLoginEnabled)
                    <input
                    type="hidden"
                    name="status"
                    id="status"
                    value="1"
                    >
                    @endif

                </div>

                <p id="submit-feedback" class="text-sm text-slate-500">
                    @if ($manualEntryEnabled)
                    @elseif($rfidLoginEnabled)
                    Ready to accept RFID scan.
                    @else
                    Manual login and RFID login are currently disabled.
                    @endif
                </p>
            </div>

            <!-- PREVIOUS -->
            <div class="relative bg-gradient-to-br from-white to-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col gap-4 shadow-lg">

                <!-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-400 rounded-t-xl"></div> -->

                <h2 class="text-sm font-semibold text-sky-800 border-b border-slate-200 pb-2">
                    PREVIOUS ENTRY
                </h2>

                <div class="flex gap-4 items-center">
                    <img id="previous-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Crect width='300' height='300' fill='%23f1f5f9'/%3E%3Ccircle cx='150' cy='112' r='46' fill='%23cbd5e1'/%3E%3Cpath d='M72 244c16-42 52-68 78-68s62 26 78 68' fill='%23cbd5e1'/%3E%3C/svg%3E"
                        class="w-[300px] h-[300px] object-cover rounded-lg border border-slate-200 shadow-md" />

                    <div class="flex flex-col gap-0 text-md text-stone-700">
                        <span id="previous-status" class="px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full bg-slate-100 text-stone-700 border border-slate-200">
                            Pending...
                        </span>
                        <p><span class="text-stone-500">Name: </span><span id="previous-name" class="text-stone-900">Pending...</span></p>
                        <p><span class="text-stone-500">ID No: </span><span id="previous-id" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Grade Level: </span><span id="previous-grade" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Department: </span><span id="previous-department" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Course: </span><span id="previous-course" class="text-stone-800">Pending...</span></p>
                        <p><span class="text-stone-500">Time: </span><span id="previous-time" class="text-stone-800">Pending...</span></p>
                    </div>
                </div>
            </div>

        </div>

        <div id="message-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <div>
                    <div>
                        <h3 id="message-modal-title" class="text-lg font-bold text-stone-900">Notice</h3>
                        <p id="message-modal-text" class="mt-2 text-sm leading-relaxed text-stone-600"></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="shortcut-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/55 backdrop-blur-sm px-4">
                <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-3 shadow-2xl">
                <div class="mb-4 text-center">
                    <h3 class="text-xl font-bold text-stone-900">Quick Open</h3>
                    <p class="mt-2 text-sm text-slate-600">Use arrow keys, then press Enter.</p>
                </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a
                            href="{{ route('in') }}"
                        data-shortcut-option
                        class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-emerald-600 px-5 py-3 text-center text-white shadow-lg outline-none transition-all duration-200 hover:bg-emerald-700 focus:border-emerald-200 focus:ring-4 focus:ring-emerald-200/70"
                    >
                        <span class="text-base font-bold uppercase tracking-wide">In</span>
                    </a>

                    <a
                        href="{{ route('out') }}"
                        data-shortcut-option
                        class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-rose-600 px-5 py-3 text-center text-white shadow-lg outline-none transition-all duration-200 hover:bg-rose-700 focus:border-rose-200 focus:ring-4 focus:ring-rose-200/70"
                    >
                        <span class="text-base font-bold uppercase tracking-wide">Out</span>
                    </a>
                    </div>
            </div>
        </div>

        <script>
            window.onload = () => {
            const event = new KeyboardEvent("keydown", {
                key: "Enter",
                code: "Enter",
                keyCode: 13,
                which: 13,
                bubbles: true
            });

            document.dispatchEvent(event);
            };

            const phTimeEl = document.getElementById('ph-time');
            const phDateEl = document.getElementById('ph-date');
            const serverNowIso = @json(now()->toIso8601String());
            const serverNowMs = Date.parse(serverNowIso);
            const clockStartedAt = window.performance.now();
            const statusDotEl = document.getElementById('system-status-dot');
            const statusTextEl = document.getElementById('system-status-text');
            const placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Crect width='300' height='300' fill='%23f1f5f9'/%3E%3Ccircle cx='150' cy='112' r='46' fill='%23cbd5e1'/%3E%3Cpath d='M72 244c16-42 52-68 78-68s62 26 78 68' fill='%23cbd5e1'/%3E%3C/svg%3E";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const studentIdInput = document.getElementById('student_id');
            const rfidInput = document.getElementById('rfid');
            const submitFeedbackEl = document.getElementById('submit-feedback');
            const messageModal = document.getElementById('message-modal');
            const messageModalTitle = document.getElementById('message-modal-title');
            const messageModalText = document.getElementById('message-modal-text');
            const shortcutModal = document.getElementById('shortcut-modal');
            const shortcutOptions = shortcutModal ? Array.from(shortcutModal.querySelectorAll('[data-shortcut-option]')) : [];
            const manualEntryEnabled = @json($manualEntryEnabled);
            const rfidLoginEnabled = @json($rfidLoginEnabled);
            let lastSignature = null;
            let rfidBuffer = '';
            let lastKeyAt = 0;
            let scanTimer = null;
            let messageModalTimer = null;
            let activeShortcutIndex = 0;
            const shortcutBaseColors = ['bg-emerald-600', 'bg-rose-600'];
            const shortcutHoverColors = ['hover:bg-emerald-700', 'hover:bg-rose-700'];

            const timeFormatter = new Intl.DateTimeFormat('en-PH', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
            });

            const dateFormatter = new Intl.DateTimeFormat('en-PH', {
                month: 'long',
                day: 'numeric',
                year: 'numeric',
            });

            function updatePhilippineClock() {
                const elapsedMs = window.performance.now() - clockStartedAt;
                const now = new Date(serverNowMs + elapsedMs);
                phTimeEl.textContent = timeFormatter.format(now);
                phDateEl.textContent = dateFormatter.format(now);
            }

            function setSystemStatus(state, label) {
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

            function setSubmitFeedback(message, tone = 'idle') {
                if (!submitFeedbackEl) {
                    return;
                }

                submitFeedbackEl.textContent = message;
                submitFeedbackEl.className = 'text-sm';

                if (tone === 'success') {
                    submitFeedbackEl.classList.add('text-emerald-700');
                    return;
                }

                if (tone === 'error') {
                    submitFeedbackEl.classList.add('text-rose-700');
                    return;
                }

                if (tone === 'sending') {
                    submitFeedbackEl.classList.add('text-sky-700');
                    return;
                }

                submitFeedbackEl.classList.add('text-slate-500');
            }

            function showMessageModal(message, title = 'Notice') {
                if (!messageModal || !messageModalTitle || !messageModalText) {
                    return;
                }

                if (messageModalTimer) {
                    clearTimeout(messageModalTimer);
                }

                messageModalTitle.textContent = title;
                messageModalText.textContent = message;
                messageModal.classList.remove('hidden');
                messageModal.classList.add('flex');

                messageModalTimer = window.setTimeout(() => {
                    hideMessageModal();
                }, 1500);
            }

            function hideMessageModal() {
                if (!messageModal) {
                    return;
                }

                if (messageModalTimer) {
                    clearTimeout(messageModalTimer);
                    messageModalTimer = null;
                }

                messageModal.classList.add('hidden');
                messageModal.classList.remove('flex');
            }

            function showShortcutModal() {
                if (!shortcutModal) {
                    return;
                }

                shortcutModal.classList.remove('hidden');
                shortcutModal.classList.add('flex');
                activeShortcutIndex = 0;
                focusShortcutOption();
            }

            function hideShortcutModal() {
                if (!shortcutModal) {
                    return;
                }

                shortcutModal.classList.add('hidden');
                shortcutModal.classList.remove('flex');
                focusManualEntry();
            }

            function focusShortcutOption() {
                if (!shortcutOptions.length) {
                    return;
                }

                const safeIndex = ((activeShortcutIndex % shortcutOptions.length) + shortcutOptions.length) % shortcutOptions.length;
                activeShortcutIndex = safeIndex;
                shortcutOptions.forEach((option, index) => {
                    option.classList.remove('scale-105', '-translate-y-1', 'border-amber-300', 'ring-4', 'ring-amber-300/70', 'ring-offset-2', 'ring-offset-slate-900', 'shadow-2xl', 'brightness-110', 'bg-gray-700');
                    option.classList.add(shortcutBaseColors[index]);
                    option.classList.add(shortcutHoverColors[index]);

                    if (index === activeShortcutIndex) {
                        option.classList.remove(shortcutBaseColors[index]);
                        option.classList.remove(shortcutHoverColors[index]);
                        option.classList.add('scale-105', '-translate-y-1', 'border-amber-300', 'ring-4', 'ring-amber-300/70', 'ring-offset-2', 'ring-offset-slate-900', 'shadow-2xl', 'brightness-110', 'bg-gray-700');
                    }
                });
                shortcutOptions[activeShortcutIndex].focus();
            }

            function moveShortcutSelection(step) {
                if (!shortcutOptions.length) {
                    return;
                }

                activeShortcutIndex += step;
                focusShortcutOption();
            }

            function activateShortcutSelection() {
                if (!shortcutOptions.length) {
                    return;
                }

                shortcutOptions[activeShortcutIndex].click();
            }

            function fillPending(prefix) {
                const imageEl = document.getElementById(`${prefix}-image`);
                const statusEl = document.getElementById(`${prefix}-status`);
                imageEl.src = placeholderImage;
                imageEl.alt = 'Pending student image';
                statusEl.textContent = 'Pending...';
                statusEl.className = 'px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full border bg-slate-100 text-stone-700 border-slate-200';

                document.getElementById(`${prefix}-name`).textContent = 'Pending...';
                document.getElementById(`${prefix}-id`).textContent = 'Pending...';
                document.getElementById(`${prefix}-grade`).textContent = 'Pending...';
                document.getElementById(`${prefix}-department`).textContent = 'Pending...';
                document.getElementById(`${prefix}-course`).textContent = 'Pending...';
                document.getElementById(`${prefix}-time`).textContent = 'Pending...';
            }

            function fillStudent(prefix, student) {
                const imageEl = document.getElementById(`${prefix}-image`);
                const statusEl = document.getElementById(`${prefix}-status`);

                if (!student) {
                    fillPending(prefix);
                    return;
                }

                imageEl.src = student.image || student.photo || student.avatar || student.picture || student.profile_photo_url || placeholderImage;
                imageEl.alt = `${student.student_name || student.name || 'Student'} image`;
                imageEl.onerror = () => {
                    imageEl.onerror = null;
                    imageEl.src = placeholderImage;
                };

                const rawStatus = String(student.status ?? student.login ?? student.remarks ?? student.state ?? student.migration_status ?? '').trim();
                const normalizedStatus = rawStatus === '1' || rawStatus.toLowerCase() === 'login' || rawStatus.toLowerCase() === 'log in' || rawStatus.toLowerCase() === 'time in' || rawStatus.toLowerCase() === 'in'
                    ? '1'
                    : rawStatus === '0' || rawStatus.toLowerCase() === 'logout' || rawStatus.toLowerCase() === 'log out' || rawStatus.toLowerCase() === 'time out' || rawStatus.toLowerCase() === 'out'
                        ? '0'
                        : 'other';

                statusEl.textContent = 'N/A';
                statusEl.className = 'px-3 py-1 text-xs font-semibold tracking-widest uppercase rounded-full border';

                statusEl.classList.add('bg-slate-100', 'text-stone-700', 'border-slate-200');

                document.getElementById(`${prefix}-name`).textContent = student.student_name || student.name || 'Pending...';
                document.getElementById(`${prefix}-id`).textContent = student.student_id || student.student_number || student.lrn || 'Pending...';
                document.getElementById(`${prefix}-grade`).textContent = student.year_level || student.grade_level || 'Pending...';
                document.getElementById(`${prefix}-department`).textContent = student.department || 'Pending...';
                document.getElementById(`${prefix}-course`).textContent = student.course_name || student.course || 'Pending...';
                document.getElementById(`${prefix}-time`).textContent = formatLogTime(student.logged_at);
            }

            function formatLogTime(value) {
                if (!value) {
                    return 'Pending...';
                }

                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }

                return date.toLocaleString('en-PH', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                });
            }

            function normalizeStudentStatus(student) {
                const rawStatus = String(student?.status ?? student?.login ?? student?.remarks ?? student?.state ?? student?.migration_status ?? '').trim().toLowerCase();

                if (rawStatus === '1' || rawStatus === 'login' || rawStatus === 'log in' || rawStatus === 'time in' || rawStatus === 'in') {
                    return '1';
                }

                if (rawStatus === '0' || rawStatus === 'logout' || rawStatus === 'log out' || rawStatus === 'time out' || rawStatus === 'out') {
                    return '0';
                }

                return 'other';
            }

            function renderStudents(students) {
                const filteredStudents = students.filter((student) => normalizeStudentStatus(student) === 'other');
                const currentStudent = filteredStudents[0] || null;
                const previousStudent = filteredStudents[1] || null;

                fillStudent('current', currentStudent);
                fillStudent('previous', previousStudent);

                if (currentStudent || previousStudent) {
                    setSystemStatus('online', `Success to Load Records`);
                    return;
                }

                setSystemStatus('offline', 'No student data found');
            }

            async function checkUpdates() {
                try {
                    const response = await fetch('{{ route('get-students') }}', {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    const payload = await response.json();
                    const students = Array.isArray(payload) ? payload : [];
                    const signature = JSON.stringify(students);

                    if (signature !== lastSignature) {
                        lastSignature = signature;
                        renderStudents(students);
                        return;
                    }

                    setSystemStatus(students.length > 0 ? 'online' : 'offline', students.length > 0 ? 'System is Online' : 'No student data found');
                } catch (error) {
                    fillPending('current');
                    fillPending('previous');
                    setSystemStatus('offline', 'Failed to load data');
                }
            }

            async function submitGateEntry(payload) {
                setSubmitFeedback('Submitting entry...', 'sending');

                try {
                    if (!manualEntryEnabled && !rfidLoginEnabled) {
                        setSubmitFeedback('Manual login and RFID login are currently disabled.', 'error');
                        return;
                    }

                    const response = await fetch('{{ route("gate-entries.store") }}', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formDataFromPayload(payload),
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const result = contentType.includes('application/json')
                        ? await response.json()
                        : { message: await response.text() };

                    if (!response.ok) {
                        const errorMessage = result.message && !result.message.includes('<html')
                            ? result.message
                            : 'Failed to submit entry.';
                        clearEntryInputs();
                        setSubmitFeedback(errorMessage, 'error');
                        showMessageModal(errorMessage, 'Entry Failed');
                        focusManualEntry();
                        return;
                    }

                    clearEntryInputs();
                    setSubmitFeedback('Entry submitted successfully.', 'success');
                    checkUpdates();
                    focusManualEntry();
                    focusRfidListener();
                } catch (error) {
                    clearEntryInputs();
                    setSubmitFeedback('Unable to submit entry right now.', 'error');
                    showMessageModal('Unable to submit entry right now.', 'Entry Failed');
                    focusManualEntry();
                }
            }

            function clearEntryInputs() {
                if (studentIdInput) {
                    studentIdInput.value = '';
                }

                if (rfidInput) {
                    rfidInput.value = '';
                }

                rfidBuffer = '';
            }

            function formDataFromPayload(payload) {
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('student_id', payload.student_id || '');
                formData.append('rfid', payload.rfid || '');
                formData.append('status', '2');

                return formData;
            }

            function focusManualEntry() {
                if (!manualEntryEnabled || !studentIdInput) {
                    return;
                }

                studentIdInput.focus();
            }

            function focusRfidListener() {
                if (!rfidLoginEnabled || !rfidInput || manualEntryEnabled) {
                    return;
                }

                rfidInput.focus();
            }

            function maintainInputFocus() {
                if (messageModal && !messageModal.classList.contains('hidden')) {
                    return;
                }

                if (shortcutModal && !shortcutModal.classList.contains('hidden')) {
                    return;
                }

                const targetInput = manualEntryEnabled ? studentIdInput : rfidInput;

                if (!targetInput || document.activeElement === targetInput) {
                    return;
                }

                targetInput.focus();
            }

            function finalizeRfidScan() {
                const value = rfidBuffer.trim();
                rfidBuffer = '';

                if (value.length < 6) {
                    if (rfidInput) {
                        rfidInput.value = '';
                    }
                    return;
                }

                if (rfidInput) {
                    rfidInput.value = value;
                }

                if (studentIdInput) {
                    studentIdInput.value = '';
                }

                submitGateEntry({
                    student_id: null,
                    rfid: value,
                });
            }

            function listenForRfidInput() {
                if (!manualEntryEnabled && !rfidLoginEnabled) {
                    return;
                }

                focusRfidListener();

                if (manualEntryEnabled && studentIdInput) {
                    studentIdInput.addEventListener('keydown', (event) => {
                        if (event.ctrlKey && event.key === 'Enter') {
                            event.preventDefault();
                            event.stopPropagation();
                            showShortcutModal();
                            return;
                        }

                        if (event.key === 'Enter') {
                            event.preventDefault();

                            const value = studentIdInput.value.trim();
                            if (value === '') {
                                setSubmitFeedback('Enter a student ID first.', 'error');
                                return;
                            }

                            if (rfidInput) {
                                rfidInput.value = '';
                            }

                            submitGateEntry({
                                student_id: value,
                                rfid: null,
                            });
                        }
                    });

                }

                window.addEventListener('keydown', (event) => {
                    if (event.defaultPrevented) {
                        return;
                    }

                    if (event.ctrlKey && event.key === 'Enter') {
                        event.preventDefault();
                        showShortcutModal();
                        return;
                    }

                    if (shortcutModal && !shortcutModal.classList.contains('hidden')) {
                        if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                            event.preventDefault();
                            moveShortcutSelection(-1);
                            return;
                        }

                        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                            event.preventDefault();
                            moveShortcutSelection(1);
                            return;
                        }

                        if (event.key === 'Enter') {
                            event.preventDefault();
                            activateShortcutSelection();
                            return;
                        }

                        if (event.key === 'Escape') {
                            hideShortcutModal();
                        }
                        return;
                    }

                    if (manualEntryEnabled && document.activeElement === studentIdInput) {
                        return;
                    }

                    if (!rfidLoginEnabled || !rfidInput) {
                        return;
                    }

                    if (event.key === 'Shift' || event.key === 'Control' || event.key === 'Alt' || event.key === 'Meta') {
                        return;
                    }

                    if (event.key === 'Enter') {
                        event.preventDefault();
                        clearTimeout(scanTimer);
                        finalizeRfidScan();
                        return;
                    }

                    if (event.key.length !== 1) {
                        return;
                    }

                    const now = Date.now();
                    if (now - lastKeyAt > 120) {
                        rfidBuffer = '';
                    }

                    lastKeyAt = now;
                    rfidBuffer += event.key;
                    rfidInput.value = rfidBuffer;

                    clearTimeout(scanTimer);
                    scanTimer = window.setTimeout(finalizeRfidScan, 160);
                });
            }

            function isEditableTarget(target) {
                if (!target) {
                    return false;
                }

                if (target instanceof HTMLInputElement || target instanceof HTMLTextAreaElement) {
                    return !target.readOnly && !target.disabled;
                }

                return Boolean(target.isContentEditable);
            }

            function shouldBlockPageShortcut(event) {
                if (event.key === 'Escape') {
                    return false;
                }

                if (/^F\d{1,2}$/i.test(event.key)) {
                    return event.key.toUpperCase() !== 'F11';
                }

                if (event.key === 'ContextMenu') {
                    return true;
                }

                if (event.altKey && (event.key === 'ArrowLeft' || event.key === 'ArrowRight' || event.key === 'Home')) {
                    return true;
                }

                if ((event.ctrlKey || event.metaKey) && ['r', 'w', 't', 'n', 'p', 'l', 'u', 's', 'o', 'j', 'h', 'd', 'g', 'i', 'k'].includes(event.key.toLowerCase())) {
                    return true;
                }

                if ((event.ctrlKey || event.metaKey) && event.shiftKey && ['i', 'j', 'c', 'n', 'r', 't'].includes(event.key.toLowerCase())) {
                    return true;
                }

                if (event.key === 'Backspace' && !isEditableTarget(event.target)) {
                    return true;
                }

                return false;
            }

            function installFullscreenGuards() {
                window.addEventListener('keydown', (event) => {
                    if (shouldBlockPageShortcut(event)) {
                        event.preventDefault();
                        event.stopPropagation();
                        event.stopImmediatePropagation();
                        return;
                    }
                }, true);

                window.addEventListener('keyup', (event) => {
                    if (shouldBlockPageShortcut(event)) {
                        event.preventDefault();
                        event.stopPropagation();
                        event.stopImmediatePropagation();
                    }
                }, true);

                window.addEventListener('contextmenu', (event) => {
                    event.preventDefault();
                });
            }

            updatePhilippineClock();
            checkUpdates();
            focusManualEntry();
            listenForRfidInput();
            installFullscreenGuards();
            setInterval(updatePhilippineClock, 1000);
            setInterval(checkUpdates, 5000);
            setInterval(maintainInputFocus, 2000);

            messageModal?.addEventListener('click', (event) => {
                if (event.target === messageModal) {
                    hideMessageModal();
                    focusManualEntry();
                }
            });

            shortcutModal?.addEventListener('click', (event) => {
                if (event.target === shortcutModal) {
                    hideShortcutModal();
                }
            });
        </script>

    </body>
</html>
