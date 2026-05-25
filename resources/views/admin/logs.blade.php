@extends('layouts.app')
@section('title', 'Student Logs')
@section('content')

<main class="eg-report-page">
    <header class="eg-report-header">
        <div>
            <p class="eg-report-kicker">Attendance Records</p>
            <h1 class="eg-report-title">Student Logs</h1>
            <p class="eg-report-subtitle">Monitor student time-in and time-out records with status and date filters.</p>
        </div>
    </header>

    <section class="eg-report-card">
        <div class="eg-report-card-inner">
            <div class="eg-report-toolbar-panel">
                <div class="eg-report-toolbar">
                    <div class="eg-report-search eg-report-search--button">
                        <label for="search-logs" class="sr-only">Search logs</label>
                        <input
                            id="search-logs"
                            type="text"
                            placeholder="Search by student ID or name"
                            class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                        <button
                            type="button"
                            id="search-logs-button"
                            class="eg-report-button eg-report-button--primary"
                        >
                            Search
                        </button>
                    </div>

                    <div class="eg-report-actions">
                        @can('logs.print')
                        <button
                            type="button"
                            id="print-logs-button"
                            class="eg-report-button eg-report-button--ghost"
                            aria-label="Print logs"
                        >
                            <img src="{{ asset('icons/print.png') }}" alt="">
                            <span>Print</span>
                        </button>
                        @endcan
                        @can('export.logs')
                        <button
                            type="button"
                            id="export-logs-button"
                            class="eg-report-button eg-report-button--success"
                            aria-label="Export logs"
                        >
                            <img src="{{ asset('icons/export.png') }}" alt="">
                            <span>Export</span>
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="eg-report-filters eg-report-filters--7">
                    <div class="eg-report-field">
                        <label for="filter-time-sort" class="text-sm font-medium text-slate-700">Time Sort</label>
                        <select id="filter-time-sort" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="desc">Descending</option>
                            <option value="asc">Ascending</option>
                        </select>
                    </div>

                    <div class="eg-report-field">
                        <label for="filter-status" class="text-sm font-medium text-slate-700">Status</label>
                        <select id="filter-status" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All Status</option>
                            <option value="1">Time In</option>
                            <option value="0">Time Out</option>
                            <option value="2">N/A</option>
                        </select>
                    </div>

                    <div class="eg-report-field">
                        <label for="filter-department" class="text-sm font-medium text-slate-700">Department</label>
                        <select id="filter-department" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="eg-report-field">
                        <label for="filter-course" class="text-sm font-medium text-slate-700">Course</label>
                        <select id="filter-course" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All courses</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="eg-report-field">
                        <label for="filter-grade-level" class="text-sm font-medium text-slate-700">Grade Level</label>
                        <select id="filter-grade-level" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All grade levels</option>
                            @foreach ($gradeLevels as $gradeLevel)
                                <option value="{{ $gradeLevel }}">{{ $gradeLevel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="eg-report-field">
                        <label for="filter-date-from" class="text-sm font-medium text-slate-700">From</label>
                        <input id="filter-date-from" type="datetime-local" step="1" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>

                    <div class="eg-report-field">
                        <label for="filter-date-to" class="text-sm font-medium text-slate-700">To</label>
                        <input id="filter-date-to" type="datetime-local" step="1" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                </div>
            </div>

            <div class="eg-report-table-wrap">
                <table class="eg-report-table">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="px-3 py-2.5">Status</th>
                            <th class="px-3 py-2.5">DateTime</th>
                            <th class="px-3 py-2.5 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="logs-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading logs...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="eg-report-footer">
                <p id="table-summary" class="text-sm text-slate-600">Preparing log list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="delete-log-modal" class="eg-report-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-report-modal-panel w-full max-w-sm rounded-xl bg-white p-4 shadow-2xl flex flex-col items-center text-center">
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="space-y-2">
            <h4 class="text-xl font-semibold text-gray-900">Delete Log</h4>
            <p id="delete-log-modal-text" class="text-sm text-gray-500 leading-relaxed">Are you sure you want to delete this log?</p>
        </div>
        <div class="mt-6 w-full grid grid-cols-2 gap-2 justify-items-center">
            <button type="button" data-close-modal="delete-log-modal" class="w-full rounded-full bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                Cancel
            </button>
            <button type="button" id="confirm-delete-log" class="w-full rounded-full bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-red-700 active:scale-[0.98]">
                Delete
            </button>
        </div>
    </div>
</div>

<div id="message-modal" class="eg-report-modal fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div id="message-modal-panel" class="eg-report-modal-panel w-full max-w-sm scale-95 rounded-xl bg-white p-4 text-center opacity-0 shadow-2xl transition duration-200">
        <div id="message-modal-icon" class="mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"></path>
            </svg>
        </div>
        <div class="space-y-2">
            <h4 id="message-modal-title" class="text-xl font-semibold text-gray-900">Notice</h4>
            <p id="message-modal-text" class="text-sm text-gray-500 leading-relaxed"></p>
        </div>
        <button type="button" id="close-message-modal" class="mt-6 w-full rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
            Close
        </button>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const logsRoutes = {
        fetch: @json(route('admin.logs.fetch')),
        base: @json(url('admin/logs')),
        print: @json(route('admin.logs.print')),
        export: @json(route('admin.logs.export')),
    };
    const canPrintLogs = @json(auth()->user()?->can('logs.print'));
    const canDeleteLogs = @json(auth()->user()?->can('logs.delete'));

    const searchLogsInput = document.getElementById('search-logs');
    const searchLogsButton = document.getElementById('search-logs-button');
    const printLogsButton = document.getElementById('print-logs-button');
    const exportLogsButton = document.getElementById('export-logs-button');
    const filterTimeSort = document.getElementById('filter-time-sort');
    const filterStatus = document.getElementById('filter-status');
    const filterDepartment = document.getElementById('filter-department');
    const filterCourse = document.getElementById('filter-course');
    const filterGradeLevel = document.getElementById('filter-grade-level');
    const filterDateFrom = document.getElementById('filter-date-from');
    const filterDateTo = document.getElementById('filter-date-to');
    const logsTableBody = document.getElementById('logs-table-body');
    const logsTableSummary = document.getElementById('table-summary');
    const logsPagination = document.getElementById('pagination');
    const deleteLogModal = document.getElementById('delete-log-modal');
    const deleteLogModalText = document.getElementById('delete-log-modal-text');
    const confirmDeleteLogButton = document.getElementById('confirm-delete-log');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');

    let logsCurrentPage = 1;
    let logsSearchTimer = null;
    let messageHideTimer = null;
    let logToDelete = null;

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function openModal(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function showMessage(message, tone = 'success') {
        if (messageHideTimer) {
            window.clearTimeout(messageHideTimer);
            messageHideTimer = null;
        }

        const tones = {
            success: {
                icon: 'bg-emerald-50 text-emerald-500',
                title: 'Success',
            },
            error: {
                icon: 'bg-rose-50 text-rose-500',
                title: 'Error',
            },
        };

        const config = tones[tone] || tones.success;
        messageModalIcon.className = `mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-full ${config.icon}`;
        messageModalTitle.textContent = config.title;
        messageModalText.textContent = message;

        openModal(messageModal);
        requestAnimationFrame(() => {
            messageModalPanel.classList.remove('scale-95', 'opacity-0');
            messageModalPanel.classList.add('scale-100', 'opacity-100');
        });

        messageHideTimer = window.setTimeout(() => {
            hideMessage();
        }, 2000);
    }

    function hideMessage() {
        if (messageHideTimer) {
            window.clearTimeout(messageHideTimer);
            messageHideTimer = null;
        }

        messageModalPanel.classList.remove('scale-100', 'opacity-100');
        messageModalPanel.classList.add('scale-95', 'opacity-0');

        window.setTimeout(() => {
            closeModal(messageModal);
        }, 150);
    }

    function formatTime(value) {
        if (!value) {
            return 'N/A';
        }

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        });
    }

    function formatDateTimeFilterValue(date) {
        const pad = (number) => String(number).padStart(2, '0');

        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
    }

    function setDefaultDateFilters() {
        const now = new Date();
        const startOfToday = new Date(now);
        startOfToday.setHours(0, 0, 0, 0);

        filterDateFrom.value = formatDateTimeFilterValue(startOfToday);
        filterDateTo.value = formatDateTimeFilterValue(now);
    }

    function renderLogRows(logs, from) {

        console.log(logs);

        if (!logs.length) {
            logsTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-5 text-center text-slate-500">No logs found.</td>
                </tr>
            `;
            return;
        }

        logsTableBody.innerHTML = logs.map((log, index) => {
            const actionButtons = [
                canPrintLogs ? `
                        <button type="button" data-action="print" data-student-id="${escapeHtml(log.student_id)}" class="transition duration-200 hover:scale-110">
                            <img src="{{ asset('icons/print.png') }}" class="w-7 h-7" alt="print student logs">
                        </button>
                ` : '',
                canDeleteLogs ? `
                        <button type="button" data-action="delete" data-id="${log.id}" data-name="${escapeHtml(log.student_id)}" class="transition duration-200 hover:scale-110">
                            <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete log">
                        </button>
                ` : '',
            ].join('');

            return `
            <tr class="border-b border-black hover:bg-gray-50 transition">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(log.student_id)}</td>
                <td class="px-3 py-2.5">${escapeHtml(log.name)}</td>
                <td class="px-3 py-2.5">${escapeHtml(log.status)}</td>
                <td class="px-3 py-2.5">${escapeHtml(formatTime(log.time))}</td>
                <td class="px-3 py-2.5">
                    <div class="flex justify-center items-center gap-4">
                        ${actionButtons || '<span class="text-sm text-slate-400">N/A</span>'}
                    </div>
                </td>
            </tr>
        `;
        }).join('');
    }

    function renderLogsPagination(meta) {
        if (meta.last_page <= 1) {
            logsPagination.innerHTML = '';
            return;
        }

        const maxVisiblePages = 7;
        const halfWindow = Math.floor(maxVisiblePages / 2);
        let startPage = Math.max(1, meta.current_page - halfWindow);
        let endPage = Math.min(meta.last_page, startPage + maxVisiblePages - 1);

        if ((endPage - startPage + 1) < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        const buttons = [];

        buttons.push(`
            <button type="button" class="rounded-full border px-3 py-1 text-sm ${meta.current_page === 1 ? 'cursor-not-allowed border-slate-200 text-slate-400' : 'border-slate-300 text-slate-700 hover:bg-slate-50'}" data-page="${meta.current_page - 1}" ${meta.current_page === 1 ? 'disabled' : ''}>
                Prev
            </button>
        `);

        for (let page = startPage; page <= endPage; page += 1) {
            buttons.push(`
                <button type="button" class="rounded-full px-3 py-1 text-sm ${page === meta.current_page ? 'bg-blue-600 text-white' : 'border border-slate-300 text-slate-700 hover:bg-slate-50'}" data-page="${page}">
                    ${page}
                </button>
            `);
        }

        buttons.push(`
            <button type="button" class="rounded-full border px-3 py-1 text-sm ${meta.current_page === meta.last_page ? 'cursor-not-allowed border-slate-200 text-slate-400' : 'border-slate-300 text-slate-700 hover:bg-slate-50'}" data-page="${meta.current_page + 1}" ${meta.current_page === meta.last_page ? 'disabled' : ''}>
                Next
            </button>
        `);

        logsPagination.innerHTML = buttons.join('');
    }

    async function fetchLogs(page = 1) {
        logsCurrentPage = page;
        logsTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading logs...</td>
            </tr>
        `;

        const url = new URL(logsRoutes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        url.searchParams.set('time_sort', filterTimeSort.value);
        if (searchLogsInput.value.trim() !== '') {
            url.searchParams.set('search', searchLogsInput.value.trim());
        }
        if (filterStatus.value !== '') {
            url.searchParams.set('status', filterStatus.value);
        }
        if (filterDepartment.value !== '') {
            url.searchParams.set('department', filterDepartment.value);
        }
        if (filterCourse.value !== '') {
            url.searchParams.set('course', filterCourse.value);
        }
        if (filterGradeLevel.value !== '') {
            url.searchParams.set('grade_level', filterGradeLevel.value);
        }
        if (filterDateFrom.value !== '') {
            url.searchParams.set('date_from', filterDateFrom.value);
        }
        if (filterDateTo.value !== '') {
            url.searchParams.set('date_to', filterDateTo.value);
        }

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                },
            });
            const payload = await response.json();

            renderLogRows(payload.data || [], payload.from || 1);
            renderLogsPagination(payload);
            logsTableSummary.textContent = payload.total
                ? `Showing ${payload.from} to ${payload.to} of ${payload.total} logs`
                : 'No logs available';
        } catch (error) {
            logsTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-5 text-center text-rose-600">Unable to load logs right now.</td>
                </tr>
            `;
            logsTableSummary.textContent = 'Log list unavailable';
            showMessage('Unable to load logs right now.', 'error');
        }
    }

    function buildLogsFilterUrl(baseUrl) {
        const url = new URL(baseUrl, window.location.origin);

        url.searchParams.set('time_sort', filterTimeSort.value);
        if (searchLogsInput.value.trim() !== '') {
            url.searchParams.set('search', searchLogsInput.value.trim());
        }
        if (filterStatus.value !== '') {
            url.searchParams.set('status', filterStatus.value);
        }
        if (filterDepartment.value !== '') {
            url.searchParams.set('department', filterDepartment.value);
        }
        if (filterCourse.value !== '') {
            url.searchParams.set('course', filterCourse.value);
        }
        if (filterGradeLevel.value !== '') {
            url.searchParams.set('grade_level', filterGradeLevel.value);
        }
        if (filterDateFrom.value !== '') {
            url.searchParams.set('date_from', filterDateFrom.value);
        }
        if (filterDateTo.value !== '') {
            url.searchParams.set('date_to', filterDateTo.value);
        }

        return url;
    }

    function buildStudentLogsPrintUrl(studentId) {
        const url = buildLogsFilterUrl(logsRoutes.print);
        url.searchParams.set('student_id', studentId);

        return url;
    }

    function openDeleteLogModal(id, studentId) {
        hideMessage();
        logToDelete = id;
        deleteLogModalText.textContent = `Are you sure you want to delete the log for ${studentId}?`;
        openModal(deleteLogModal);
    }

    async function sendRequest(url, method, body) {
        const response = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body,
        });

        const payload = await response.json();

        if (!response.ok) {
            throw new Error(payload.message || 'Request failed.');
        }

        return payload;
    }

    searchLogsButton.addEventListener('click', () => {
        fetchLogs(1);
    });

    printLogsButton?.addEventListener('click', () => {
        window.location.href = buildLogsFilterUrl(logsRoutes.print).toString();
    });

    exportLogsButton?.addEventListener('click', () => {
        window.location.href = buildLogsFilterUrl(logsRoutes.export).toString();
    });

    searchLogsInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            fetchLogs(1);
        }
    });

    searchLogsInput.addEventListener('input', () => {
        if (logsSearchTimer) {
            window.clearTimeout(logsSearchTimer);
        }

        logsSearchTimer = window.setTimeout(() => {
            fetchLogs(1);
        }, 300);
    });

    [filterTimeSort, filterStatus, filterDepartment, filterCourse, filterGradeLevel, filterDateFrom, filterDateTo].forEach((element) => {
        element.addEventListener('change', () => {
            fetchLogs(1);
        });
    });

    logsPagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchLogs(Number(button.dataset.page));
    });

    logsTableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) {
            return;
        }

        const { action, id, name, studentId } = button.dataset;

        try {
            if (action === 'print') {
                window.location.href = buildStudentLogsPrintUrl(studentId).toString();
            }

            if (action === 'delete') {
                openDeleteLogModal(id, name);
            }
        } catch (error) {
            showMessage(error.message || 'Action failed.', 'error');
        }
    });

    confirmDeleteLogButton.addEventListener('click', async () => {
        hideMessage();

        const formData = new FormData();
        formData.set('_method', 'DELETE');

        try {
            const payload = await sendRequest(`${logsRoutes.base}/${logToDelete}`, 'POST', formData);
            closeModal(deleteLogModal);
            showMessage(payload.message || 'Log deleted successfully.', 'success');
            fetchLogs(1);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(document.getElementById(button.dataset.closeModal));
        });
    });

    [deleteLogModal, messageModal].forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                if (modal === messageModal) {
                    hideMessage();
                    return;
                }

                closeModal(modal);
            }
        });
    });

    document.getElementById('close-message-modal').addEventListener('click', () => {
        hideMessage();
    });

    setDefaultDateFilters();
    fetchLogs();
</script>

@endsection
