@extends('layouts.app')
@section('title', 'Employee Logs')
@section('content')

<main class="eg-report-page">
    <header class="eg-report-header">
        <div>
            <p class="eg-report-kicker">Attendance Reports</p>
            <h1 class="eg-report-title">Employee Logs</h1>
            <p class="eg-report-subtitle">Review employee attendance records, schedules, and monthly DTR summaries.</p>
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
                            placeholder="Search by Employee ID or name"
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
                        @can('emlog.print')
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
                    </div>
                </div>

                <div class="eg-report-filters eg-report-filters--3">

                    <div class="eg-report-field">
                        <label for="filter-department" class="text-sm font-medium text-slate-700">Department</label>
                        <select id="filter-department" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 10;
                    @endphp

                    <div class="eg-report-field">
                        <label for="filter-year" class="text-sm font-medium text-slate-700">
                            Year
                        </label>

                        <select id="filter-year"
                            class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm bg-white">
                            @for ($y = $currentYear; $y >= $startYear; $y--)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor

                        </select>
                    </div>

                    @php
                        $currentMonth = date('n'); // 1 - 12
                    @endphp

                    <div class="eg-report-field">
                        <label for="filter-month" class="text-sm font-medium text-slate-700">
                            Month
                        </label>
                        <select id="filter-month"
                            class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm bg-white">
                            @php
                                $months = [
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December',
                                ];
                            @endphp

                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach

                        </select>
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
                            <th class="px-3 py-2.5">Schedule</th>
                            <th class="px-3 py-2.5 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="logs-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="5" class="px-3 py-5 text-center text-slate-500">Loading logs...</td>
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

<div id="view-logs-modal" class="eg-report-modal fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-black/50 px-4 py-6 backdrop-blur-sm">
    <div class="eg-report-modal-panel flex max-h-[90vh] w-full max-w-4xl flex-col rounded-xl bg-white p-4 shadow-2xl">
        <div class="mb-3 flex items-start justify-between gap-3">
            <div>
                <h4 id="view-logs-title" class="text-lg font-semibold text-gray-900">Employee Logs</h4>
                <p id="view-logs-period" class="text-sm text-slate-500"></p>
            </div>
            <button type="button" data-close-modal="view-logs-modal" class="rounded-full border border-slate-300 px-3 py-1 text-sm text-slate-700 hover:bg-slate-50">
                Close
            </button>
        </div>

        <div id="view-logs-content" class="min-h-0 overflow-auto rounded-md border border-slate-200">
            <div class="px-3 py-5 text-center text-sm text-slate-500">No logs loaded.</div>
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
    fetch: @json(route('admin.employee_logs.fetch')),
    base: @json(url('admin/employee-logs')),
    print: @json(route('admin.employee_logs.print')),
};

const canPrintLogs = @json(auth()->user()?->can('emlog.print'));
const canDeleteLogs = @json(auth()->user()?->can('emlog.delete'));

const searchLogsInput = document.getElementById('search-logs');
const searchLogsButton = document.getElementById('search-logs-button');

const printLogsButton = document.getElementById('print-logs-button');

const filterDepartment = document.getElementById('filter-department');
const filterYear = document.getElementById('filter-year');
const filterMonth = document.getElementById('filter-month');

const logsTableBody = document.getElementById('logs-table-body');
const logsTableSummary = document.getElementById('table-summary');
const logsPagination = document.getElementById('pagination');

const deleteLogModal = document.getElementById('delete-log-modal');
const deleteLogModalText = document.getElementById('delete-log-modal-text');
const confirmDeleteLogButton = document.getElementById('confirm-delete-log');
const viewLogsModal = document.getElementById('view-logs-modal');
const viewLogsTitle = document.getElementById('view-logs-title');
const viewLogsPeriod = document.getElementById('view-logs-period');
const viewLogsContent = document.getElementById('view-logs-content');
const defaultEmployeeYear = filterYear.value;
const defaultEmployeeMonth = filterMonth.value;
const employeeFilterStorageKey = 'admin.employee_logs.filters';
const employeeFilterRestoreKey = 'admin.employee_logs.restore_filters';

let logsCurrentPage = 1;
let logsSearchTimer = null;
let logToDelete = null;

/* ---------------- helpers ---------------- */

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

function getEmployeeFilterFields() {
    return {
        search: searchLogsInput.value.trim(),
        department: filterDepartment.value,
        year: filterYear.value,
        month: filterMonth.value,
    };
}

function applyEmployeeFilterFields(filters) {
    searchLogsInput.value = filters.search ?? '';
    filterDepartment.value = filters.department ?? '';
    filterYear.value = filters.year || defaultEmployeeYear;
    filterMonth.value = filters.month || defaultEmployeeMonth;
}

function buildEmployeeFilterParams() {
    const filters = getEmployeeFilterFields();
    const params = new URLSearchParams();

    if (filters.search !== '') {
        params.set('search', filters.search);
    }

    if (filters.department !== '') {
        params.set('department', filters.department);
    }

    if (filters.year !== '') {
        params.set('year', filters.year);
    }

    if (filters.month !== '') {
        params.set('month', filters.month);
    }

    return params;
}

function resetEmployeeFilters() {
    applyEmployeeFilterFields({});
}

function markEmployeeFiltersForRestore() {
    try {
        window.sessionStorage.setItem(employeeFilterStorageKey, JSON.stringify(getEmployeeFilterFields()));
        window.sessionStorage.setItem(employeeFilterRestoreKey, '1');
    } catch (error) {
        // Browser storage can be disabled; the current page state still handles AJAX actions.
    }
}

function clearSavedEmployeeFilters() {
    try {
        window.sessionStorage.removeItem(employeeFilterStorageKey);
        window.sessionStorage.removeItem(employeeFilterRestoreKey);
    } catch (error) {
        // Nothing to clear when storage is unavailable.
    }
}

function initializeEmployeeFilters() {
    let shouldRestore = false;
    let savedFilters = {};

    try {
        shouldRestore = window.sessionStorage.getItem(employeeFilterRestoreKey) === '1';
        savedFilters = JSON.parse(window.sessionStorage.getItem(employeeFilterStorageKey) || '{}');
    } catch (error) {
        shouldRestore = false;
    }

    clearSavedEmployeeFilters();

    if (shouldRestore) {
        applyEmployeeFilterFields(savedFilters);
    } else {
        resetEmployeeFilters();
    }

    window.history.replaceState({}, '', window.location.pathname);
}

/* ---------------- render employees ---------------- */

function renderEmployeeRows(employees, from) {
    if (!employees.length) {
        logsTableBody.innerHTML = `
            <tr>
                <td colspan="5" class="px-3 py-5 text-center text-slate-500">
                    No employees found.
                </td>
            </tr>
        `;
        return;
    }

    logsTableBody.innerHTML = employees.map((emp, index) => {
        const employeeId = emp.student_number || emp.id;
        const actionButtons = `
                <button type="button" data-action="print" data-student-id="${escapeHtml(employeeId)}" class="transition duration-200 hover:scale-110">
                    <img src="{{ asset('icons/print.png') }}" class="w-7 h-7" alt="print data">
                </button>

                <button type="button" data-action="view" data-student-id="${escapeHtml(employeeId)}" class="transition duration-200 hover:scale-110">
                    <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="view logs">
                </button>

                <button type="button" data-action="delete" class="transition duration-200 hover:scale-110">
                    <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete data">
                </button>
            `;

        return `
            <tr class="border-b border-black hover:bg-gray-50 transition">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(emp.student_number ?? 'N/A')}</td>
                <td class="px-3 py-2.5">${escapeHtml(emp.name ?? 'N/A')}</td>
                <td class="px-3 py-2.5">${escapeHtml(emp.schedule_name ?? 'Default Schedule (8:00 AM - 5:00 PM)')}</td>
                <td class="px-3 py-2.5">
                    <div class="flex justify-center items-center gap-4">
                        ${actionButtons}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

/* ---------------- fetch employees ---------------- */

async function fetchLogs(page = 1) {
    logsCurrentPage = page;

    logsTableBody.innerHTML = `
        <tr>
            <td colspan="5" class="px-3 py-5 text-center text-slate-500">
                Loading employees...
            </td>
        </tr>
    `;

    const url = new URL(logsRoutes.fetch, window.location.origin);
    url.searchParams.set('page', page);

    buildEmployeeFilterParams().forEach((value, key) => {
        url.searchParams.set(key, value);
    });

    try {
        const response = await fetch(url, {
            headers: { Accept: 'application/json' },
        });

        const payload = await response.json();

        renderEmployeeRows(payload.data || [], payload.from || 1);

        logsTableSummary.textContent = payload.total
            ? `Showing ${payload.from} to ${payload.to} of ${payload.total} employees`
            : 'No employees found';

        renderLogsPagination(payload);

    } catch (error) {
        logsTableBody.innerHTML = `
            <tr>
                <td colspan="5" class="px-3 py-5 text-center text-rose-600">
                    Failed to load employees.
                </td>
            </tr>
        `;

        logsTableSummary.textContent = 'Error loading data';
    }
}

/* ---------------- pagination ---------------- */

function renderLogsPagination(meta) {
    if (meta.last_page <= 1) {
        logsPagination.innerHTML = '';
        return;
    }

    let buttons = [];

    buttons.push(`
        <button ${meta.current_page === 1 ? 'disabled' : ''} data-page="${meta.current_page - 1}">
            Prev
        </button>
    `);

    for (let i = 1; i <= meta.last_page; i++) {
        buttons.push(`
            <button data-page="${i}" class="${i === meta.current_page ? 'bg-blue-600 text-white' : ''}">
                ${i}
            </button>
        `);
    }

    buttons.push(`
        <button ${meta.current_page === meta.last_page ? 'disabled' : ''} data-page="${meta.current_page + 1}">
            Next
        </button>
    `);

    logsPagination.innerHTML = buttons.join('');
}

function buildEmployeeFilterUrl(baseUrl) {
    const url = new URL(baseUrl, window.location.origin);

    markEmployeeFiltersForRestore();
    buildEmployeeFilterParams().forEach((value, key) => {
        url.searchParams.set(key, value);
    });

    return url;
}

function buildEmployeeDtrPrintUrl(studentId) {
    const url = buildEmployeeFilterUrl(logsRoutes.print);
    url.searchParams.set('student_id', studentId);

    return url;
}

function buildEmployeeViewLogsUrl(studentId) {
    const url = new URL(`${logsRoutes.base}/${encodeURIComponent(studentId)}/logs`, window.location.origin);

    if (filterYear.value !== '') {
        url.searchParams.set('year', filterYear.value);
    }

    if (filterMonth.value !== '') {
        url.searchParams.set('month', filterMonth.value);
    }

    return url;
}

async function openEmployeeLogsModal(studentId) {
    viewLogsTitle.textContent = 'Employee Logs';
    viewLogsPeriod.textContent = '';
    viewLogsContent.innerHTML = '<div class="px-3 py-5 text-center text-sm text-slate-500">Loading logs...</div>';
    openModal(viewLogsModal);

    try {
        const response = await fetch(buildEmployeeViewLogsUrl(studentId), {
            headers: { Accept: 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok) {
            throw new Error(payload.message || 'Unable to load employee logs.');
        }

        viewLogsTitle.textContent = `${payload.employee.name} (${payload.employee.id})`;
        viewLogsPeriod.textContent = payload.period;

        const rows = payload.rows || [];
        const summary = payload.summary || {};

        viewLogsContent.innerHTML = `
            <div class="min-w-[760px] p-4 font-serif text-black">
                <div class="mb-3 text-xs leading-relaxed">
                    <div>${escapeHtml(payload.period)}</div>
                    <div>Name: ${escapeHtml(payload.employee.name)}</div>
                    <div>Contact: ${escapeHtml(payload.employee.contact || 'N/A')}</div>
                    <div>Email: ${escapeHtml(payload.employee.email || 'N/A')}</div>
                    <div>Printed at ${escapeHtml(payload.printed_at || '')}</div>
                </div>

                <table class="w-full table-fixed border-collapse text-[10px]">
                    <colgroup>
                        <col class="w-[10%]">
                        <col class="w-[10%]">
                        <col class="w-[11%]">
                        <col class="w-[11%]">
                        <col class="w-[11%]">
                        <col class="w-[11%]">
                        <col class="w-[13%]">
                        <col class="w-[12%]">
                        <col class="w-[11%]">
                    </colgroup>
                    <thead>
                        <tr>
                            <th colspan="2" rowspan="2" class="border border-black px-1 py-1 text-sm font-normal">Day</th>
                            <th colspan="2" class="border border-black px-1 py-1 font-normal">AM</th>
                            <th colspan="2" class="border border-black px-1 py-1 font-normal">PM</th>
                            <th rowspan="2" class="border border-black px-1 py-1 font-normal">Late</th>
                            <th rowspan="2" class="border border-black px-1 py-1 font-normal">Under time</th>
                            <th rowspan="2" class="border border-black px-1 py-1 font-normal">Abs</th>
                        </tr>
                        <tr>
                            <th class="border border-black px-1 py-1 font-normal">In</th>
                            <th class="border border-black px-1 py-1 font-normal">Out</th>
                            <th class="border border-black px-1 py-1 font-normal">In</th>
                            <th class="border border-black px-1 py-1 font-normal">Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows.map((row) => `
                            <tr>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.day)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.weekday)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.am_in)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.am_out)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.pm_in)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.pm_out)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.late)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.undertime)}</td>
                                <td class="h-[18px] border border-black px-1 py-px text-center">${escapeHtml(row.absence)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div class="mt-2 text-xs leading-relaxed">
                    <div>Total Time: ${escapeHtml(summary.total_time || '')}</div>
                    <div>Late: ${escapeHtml(summary.late || '')}</div>
                    <div>Undertime: ${escapeHtml(summary.undertime || '')}</div>
                    <div>Absents: ${escapeHtml(summary.absence || '')}</div>
                </div>
            </div>
        `;
    } catch (error) {
        viewLogsContent.innerHTML = `<div class="px-3 py-5 text-center text-sm text-rose-600">${escapeHtml(error.message || 'Unable to load employee logs.')}</div>`;
    }
}

/* ---------------- events ---------------- */
function onChange(el, cb) {
    if (el) el.addEventListener('change', cb);
}

// search
searchLogsButton?.addEventListener('click', () => fetchLogs(1));

searchLogsInput?.addEventListener('input', () => {
    clearTimeout(logsSearchTimer);
    logsSearchTimer = setTimeout(() => fetchLogs(1), 300);
});

// filters
onChange(filterDepartment, () => fetchLogs(1));
onChange(filterYear, () => fetchLogs(1));
onChange(filterMonth, () => fetchLogs(1));

// pagination
logsPagination?.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-page]');
    if (!btn || btn.disabled) return;
    fetchLogs(Number(btn.dataset.page));
});

printLogsButton?.addEventListener('click', () => {
    window.location.href = buildEmployeeFilterUrl(logsRoutes.print).toString();
});

logsTableBody?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-action]');
    if (!button) return;

    const { action, studentId } = button.dataset;

    if (action === 'print' && studentId) {
        window.location.href = buildEmployeeDtrPrintUrl(studentId).toString();
    }

    if (action === 'view' && studentId) {
        openEmployeeLogsModal(studentId);
    }
});

document.querySelectorAll('[data-close-modal]').forEach((button) => {
    button.addEventListener('click', () => {
        closeModal(document.getElementById(button.dataset.closeModal));
    });
});

[deleteLogModal, viewLogsModal].forEach((modal) => {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    });
});

/* ---------------- init ---------------- */

initializeEmployeeFilters();
fetchLogs();
</script>

@endsection
