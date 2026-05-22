@extends('layouts.app')
@section('title', 'Logs')
@section('content')

<main class="w-full p-2 gap-2 flex flex-1 flex-col overflow-hidden">
    <h3 class="text-lg font-semibold text-slate-800">Employee Logs</h3>

    <section class="w-full flex flex-1 justify-center p-2 overflow-hidden">
        <div class="w-full bg-white rounded-md shadow-sm overflow-hidden border border-slate-200 flex flex-col min-h-0">
            <div class="w-full px-3 py-3 border-b border-slate-200 flex flex-col gap-2">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="w-full md:max-w-md flex gap-2">
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
                            class="rounded-full bg-blue-600 px-4 py-1.5 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 hover:scale-105"
                        >
                            Search
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @can('emlog.print')
                        <button
                            type="button"
                            id="print-logs-button"
                            class="rounded-full border border-slate-300 p-1.5 transition duration-200 hover:bg-slate-50 hover:scale-105"
                            aria-label="Print logs"
                        >
                            <img src="{{ asset('icons/print.png') }}" class="h-7 w-7" alt="">
                        </button>
                        @endcan
                        @can('emlog.export')
                        <button
                            type="button"
                            id="export-logs-button"
                            class="rounded-full border border-emerald-300 p-1.5 transition duration-200 hover:bg-emerald-50 hover:scale-105"
                            aria-label="Export logs"
                        >
                            <img src="{{ asset('icons/export.png') }}" class="h-7 w-7" alt="">
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="grid gap-2 md:grid-cols-3 xl:grid-cols-5">

                    <div class="flex flex-col gap-1">
                        <label for="filter-department" class="text-sm font-medium text-slate-700">Department</label>
                        <select id="filter-department" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="filter-course" class="text-sm font-medium text-slate-700">Course</label>
                        <select id="filter-course" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All courses</option>
                            @foreach ($courses as $cou)
                                <option value="{{ $cou }}">{{ $cou }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="filter-grade-level" class="text-sm font-medium text-slate-700">Grade Level</label>
                        <select id="filter-grade-level" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All grade levels</option>
                            @foreach ($gradeLevels as $gl)
                                <option value="{{ $gl }}">{{ $gl }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 10;
                    @endphp

                    <div class="flex flex-col gap-1">
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

                    <div class="flex flex-col gap-1">
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

            <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden">
                <table class="w-full text-left">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Name</th>
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

            <div class="px-3 py-2.5 border-t border-slate-200 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <p id="table-summary" class="text-sm text-slate-600">Preparing log list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="delete-log-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm rounded-xl bg-white p-4 shadow-2xl flex flex-col items-center text-center">
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

<div id="message-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div id="message-modal-panel" class="w-full max-w-sm scale-95 rounded-xl bg-white p-4 text-center opacity-0 shadow-2xl transition duration-200">
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
    export: @json(route('admin.employee_logs.export')),
};

const canPrintLogs = @json(auth()->user()?->can('emlog.print'));
const canDeleteLogs = @json(auth()->user()?->can('emlog.delete'));

const searchLogsInput = document.getElementById('search-logs');
const searchLogsButton = document.getElementById('search-logs-button');

const printLogsButton = document.getElementById('print-logs-button');
const exportLogsButton = document.getElementById('export-logs-button');

const filterDepartment = document.getElementById('filter-department');
const filterCourse = document.getElementById('filter-course');
const filterGradeLevel = document.getElementById('filter-grade-level');
const filterYear = document.getElementById('filter-year');
const filterMonth = document.getElementById('filter-month');

const logsTableBody = document.getElementById('logs-table-body');
const logsTableSummary = document.getElementById('table-summary');
const logsPagination = document.getElementById('pagination');

const deleteLogModal = document.getElementById('delete-log-modal');
const deleteLogModalText = document.getElementById('delete-log-modal-text');
const confirmDeleteLogButton = document.getElementById('confirm-delete-log');

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

/* ---------------- render employees ---------------- */

function renderEmployeeRows(employees, from) {
    if (!employees.length) {
        logsTableBody.innerHTML = `
            <tr>
                <td colspan="3" class="px-3 py-5 text-center text-slate-500">
                    No employees found.
                </td>
            </tr>
        `;
        return;
    }

    logsTableBody.innerHTML = employees.map((emp, index) => {
        const actionButtons = `
                <button type="button" data-action="print" class="transition duration-200 hover:scale-110">
                    <img src="{{ asset('icons/print.png') }}" class="w-7 h-7" alt="print data">
                </button>

                <button type="button" data-action="edit" class="transition duration-200 hover:scale-110">
                    <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="edit data">
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
            <td colspan="3" class="px-3 py-5 text-center text-slate-500">
                Loading employees...
            </td>
        </tr>
    `;

    const url = new URL(logsRoutes.fetch, window.location.origin);
    url.searchParams.set('page', page);

    if (searchLogsInput.value.trim() !== '') {
        url.searchParams.set('search', searchLogsInput.value.trim());
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
                <td colspan="3" class="px-3 py-5 text-center text-rose-600">
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
onChange(filterCourse, () => fetchLogs(1));
onChange(filterGradeLevel, () => fetchLogs(1));
onChange(filterYear, () => fetchLogs(1));
onChange(filterMonth, () => fetchLogs(1));

// pagination
logsPagination?.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-page]');
    if (!btn || btn.disabled) return;
    fetchLogs(Number(btn.dataset.page));
});

/* ---------------- init ---------------- */

fetchLogs();
</script>

@endsection
