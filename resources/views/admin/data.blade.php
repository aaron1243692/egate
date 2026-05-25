@extends('layouts.app')
@section('title', 'Student Data')
@section('content')

<main class="eg-report-page">
    <header class="eg-report-header">
        <div>
            <p class="eg-report-kicker">Records Management</p>
            <h1 class="eg-report-title">Student Data</h1>
            <p class="eg-report-subtitle">Manage registered students, RFID records, departments, courses, and grade levels.</p>
        </div>
    </header>

    <section class="eg-report-card">
        <div class="eg-report-card-inner">
            <div class="eg-report-toolbar-panel">
                <div class="eg-report-toolbar">
                    <div class="eg-report-search">
                        <label for="search-data" class="sr-only">Search student data</label>
                        <input
                            id="search-data"
                            type="text"
                            placeholder="Search name, ID, RFID, email, department"
                            class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div class="eg-report-actions">
                        @can('data.print')
                        <button
                            type="button"
                            id="print-data-button"
                            class="eg-report-button eg-report-button--ghost"
                            aria-label="Print student data"
                        >
                            <img src="{{ asset('icons/print.png') }}" alt="">
                            <span>Print</span>
                        </button>
                        @endcan
                        @can('data.export')
                        <button
                            type="button"
                            id="export-data-button"
                            class="eg-report-button eg-report-button--success"
                            aria-label="Export student data"
                        >
                            <img src="{{ asset('icons/export.png') }}" alt="">
                            <span>Export</span>
                        </button>
                        @endcan
                        @can('data.create')
                        <button
                            type="button"
                            id="open-add-data-modal"
                            class="eg-report-button eg-report-button--primary"
                        >
                            Add Data
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="eg-report-filters eg-report-filters--4">
                    <div class="eg-report-field">
                        <label for="filter-name-sort" class="text-sm font-medium text-slate-700">Name Sort</label>
                        <select id="filter-name-sort" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
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
                        <label for="filter-year-level" class="text-sm font-medium text-slate-700">Grade Level</label>
                        <select id="filter-year-level" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All grade levels</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel }}">{{ $yearLevel }}</option>
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
                            <th class="px-3 py-2.5">ID No</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="px-3 py-2.5">Department</th>
                            <th class="px-3 py-2.5">Course</th>
                            <th class="px-3 py-2.5 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="data-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="eg-report-footer">
                <p id="table-summary" class="text-sm text-slate-600">Preparing data list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="data-modal" class="eg-report-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-report-modal-panel w-full max-w-4xl max-h-[90vh] rounded-xl bg-white shadow-2xl flex flex-col overflow-hidden">
        <form id="data-form" class="flex flex-col min-h-0">
            <input type="hidden" id="data-id">

            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                <h4 id="data-modal-title" class="text-lg font-bold text-gray-900">Add Data</h4>
                <button type="button" data-close-modal="data-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div class="overflow-y-auto px-4 py-3">
                <div class="grid gap-3 md:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <label for="form-student-number">ST No / LRN</label>
                        <input id="form-student-number" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none" required>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-rfid">RFID</label>
                        <input id="form-rfid" type="number" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-name">Name (FN MN, LN)</label>
                        <input id="form-name" type="text" placeholder="Juan Santos, Dela Cruz" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none" required>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-role">Role</label>
                        <select id="form-role" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-white">
                            <option value="">Select role</option>
                            <option value="1">Student</option>
                            <option value="2">Employee</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-email">Email</label>
                        <input id="form-email" type="email" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-contact">Contact</label>
                        <input id="form-contact" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-department">Department</label>
                        <input id="form-department" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-course">Course</label>
                        <input id="form-course" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-school-level">School Level</label>
                        <input id="form-school-level" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="form-grade-level">Grade Level</label>
                        <input id="form-grade-level" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none">
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 border-t border-slate-200 flex justify-center gap-2">
                <button type="button" data-close-modal="data-modal" class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                    Cancel
                </button>
                <button type="submit" id="data-submit-button" class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600 hover:scale-105">
                    Save Data
                </button>
            </div>
        </form>
    </div>
</div>

<div id="details-modal" class="eg-report-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-report-modal-panel w-full max-w-2xl max-h-[90vh] rounded-xl bg-white shadow-2xl flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <h4 class="text-lg font-bold text-gray-900">Student Details</h4>
            <button type="button" data-close-modal="details-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
        </div>

        <div class="overflow-y-auto px-4 py-3">
            <form class="grid gap-3 md:grid-cols-2">
                <div class="flex flex-col gap-1">
                    <label>ST No / LRN</label>
                    <input id="detail-stno-lrn" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>RFID</label>
                    <input id="detail-rfid" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Name</label>
                    <input id="detail-name" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Role</label>
                    <input id="detail-role" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Email</label>
                    <input id="detail-email" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Contact</label>
                    <input id="detail-contact" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Department</label>
                    <input id="detail-department" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Course</label>
                    <input id="detail-course" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>School Level</label>
                    <input id="detail-school-level" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                    <label>Grade Level</label>
                    <input id="detail-grade-level" type="text" readonly class="w-full rounded-full border border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>
            </form>
        </div>

        <div class="px-4 py-3 border-t border-slate-200 flex justify-center">
            <button type="button" data-close-modal="details-modal" class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                Close
            </button>
        </div>
    </div>
</div>

<div id="delete-modal" class="eg-report-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-report-modal-panel w-full max-w-sm rounded-xl bg-white p-4 shadow-2xl flex flex-col items-center text-center">
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="space-y-2">
            <h4 class="text-xl font-semibold text-gray-900">Delete Data</h4>
            <p id="delete-modal-text" class="text-sm text-gray-500 leading-relaxed">Are you sure you want to delete this record?</p>
        </div>
        <div class="mt-6 w-full grid grid-cols-2 gap-2 justify-items-center">
            <button type="button" data-close-modal="delete-modal" class="w-full rounded-full bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                Cancel
            </button>
            <button type="button" id="confirm-delete-data" class="w-full rounded-full bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-red-700 active:scale-[0.98]">
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
    const dataRoutes = {
        fetch: @json(route('admin.data.fetch')),
        store: @json(route('admin.data.store')),
        base: @json(url('admin/data')),
        print: @json(route('admin.data.print')),
        export: @json(route('admin.data.export')),
    };
    const canPrintData = @json(auth()->user()?->can('data.print'));
    const canUpdateData = @json(auth()->user()?->can('data.update'));
    const canDeleteData = @json(auth()->user()?->can('data.delete'));

    const searchDataInput = document.getElementById('search-data');
    const filterNameSort = document.getElementById('filter-name-sort');
    const filterDepartment = document.getElementById('filter-department');
    const filterCourse = document.getElementById('filter-course');
    const filterYearLevel = document.getElementById('filter-year-level');
    const dataTableBody = document.getElementById('data-table-body');
    const dataTableSummary = document.getElementById('table-summary');
    const dataPagination = document.getElementById('pagination');
    const detailsModal = document.getElementById('details-modal');
    const dataModal = document.getElementById('data-modal');
    const deleteModal = document.getElementById('delete-modal');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');
    const dataForm = document.getElementById('data-form');
    const dataIdInput = document.getElementById('data-id');
    const dataModalTitle = document.getElementById('data-modal-title');
    const dataSubmitButton = document.getElementById('data-submit-button');
    const deleteModalText = document.getElementById('delete-modal-text');
    const openAddDataModalButton = document.getElementById('open-add-data-modal');
    const printDataButton = document.getElementById('print-data-button');
    const exportDataButton = document.getElementById('export-data-button');
    const confirmDeleteDataButton = document.getElementById('confirm-delete-data');

    let dataCurrentPage = 1;
    let searchTimer = null;
    let messageHideTimer = null;
    let dataToDelete = null;

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

    function formatNameCell(record) {
        return record.name || '';
    }

    function formatRole(value) {
        const roles = {
            1: 'Student',
            2: 'Employee',
        };

        return roles[Number(value)] || value || '';
    }

    function renderRows(records, from) {
        if (!records.length) {
            dataTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-5 text-center text-slate-500">No records found.</td>
                </tr>
            `;
            return;
        }

        dataTableBody.innerHTML = records.map((record, index) => {
            const actionButtons = [
                canPrintData ? `
                        <button type="button" data-action="print" data-id="${record.id}" class="transition duration-200 hover:scale-110">
                            <img src="{{ asset('icons/print.png') }}" class="w-7 h-7" alt="print data">
                        </button>
                ` : '',
                canUpdateData ? `
                        <button type="button" data-action="edit" data-id="${record.id}" class="transition duration-200 hover:scale-110">
                            <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="edit data">
                        </button>
                ` : '',
                canDeleteData ? `
                        <button type="button" data-action="delete" data-id="${record.id}" data-name="${escapeHtml(formatNameCell(record))}" class="transition duration-200 hover:scale-110">
                            <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete data">
                        </button>
                ` : '',
            ].join('');

            return `
            <tr class="border-b border-black hover:bg-gray-50 transition">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(record.student_number || record.lrn || 'N/A')}</td>
                <td class="px-3 py-2.5">${escapeHtml(formatNameCell(record))}</td>
                <td class="px-3 py-2.5">${escapeHtml(record.department || 'N/A')}</td>
                <td class="px-3 py-2.5">${escapeHtml(record.course || 'N/A')}</td>
                <td class="px-3 py-2.5">
                    <div class="flex justify-center items-center gap-4">
                        ${actionButtons || '<span class="text-sm text-slate-400">N/A</span>'}
                    </div>
                </td>
            </tr>
        `;
        }).join('');
    }

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            dataPagination.innerHTML = '';
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

        dataPagination.innerHTML = buttons.join('');
    }

    async function fetchData(page = 1) {
        dataCurrentPage = page;
        dataTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading data...</td>
            </tr>
        `;

        const url = new URL(dataRoutes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        url.searchParams.set('name_sort', filterNameSort.value);
        if (searchDataInput.value.trim() !== '') {
            url.searchParams.set('search', searchDataInput.value.trim());
        }
        if (filterDepartment.value !== '') {
            url.searchParams.set('department', filterDepartment.value);
        }
        if (filterCourse.value !== '') {
            url.searchParams.set('course', filterCourse.value);
        }
        if (filterYearLevel.value !== '') {
            url.searchParams.set('year_level', filterYearLevel.value);
        }

        try {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            const payload = await response.json();

            renderRows(payload.data || [], payload.from || 1);
            renderPagination(payload);
            dataTableSummary.textContent = payload.total
                ? `Showing ${payload.from} to ${payload.to} of ${payload.total} records`
                : 'No records available';
        } catch (error) {
            dataTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-5 text-center text-rose-600">Unable to load data right now.</td>
                </tr>
            `;
            dataTableSummary.textContent = 'Data list unavailable';
            showMessage('Unable to load data right now.', 'error');
        }
    }

    function buildDataFilterUrl(baseUrl) {
        const url = new URL(baseUrl, window.location.origin);

        url.searchParams.set('name_sort', filterNameSort.value);
        if (searchDataInput.value.trim() !== '') {
            url.searchParams.set('search', searchDataInput.value.trim());
        }
        if (filterDepartment.value !== '') {
            url.searchParams.set('department', filterDepartment.value);
        }
        if (filterCourse.value !== '') {
            url.searchParams.set('course', filterCourse.value);
        }
        if (filterYearLevel.value !== '') {
            url.searchParams.set('year_level', filterYearLevel.value);
        }

        return url;
    }

    function buildSingleDataPrintUrl(id) {
        const url = buildDataFilterUrl(dataRoutes.print);
        url.searchParams.set('record_id', id);

        return url;
    }

    function fillDetails(record) {
        document.getElementById('detail-stno-lrn').value = record.student_number || record.lrn || 'N/A';
        document.getElementById('detail-rfid').value = record.rfid || '';
        document.getElementById('detail-name').value = record.name || '';
        document.getElementById('detail-role').value = formatRole(record.role);
        document.getElementById('detail-email').value = record.email || '';
        document.getElementById('detail-contact').value = record.contact || '';
        document.getElementById('detail-department').value = record.department || '';
        document.getElementById('detail-course').value = record.course || '';
        document.getElementById('detail-school-level').value = record.school_level || '';
        document.getElementById('detail-grade-level').value = record.grade_level || '';
    }

    function resetDataForm(isEdit = false) {
        dataForm.reset();
        dataIdInput.value = '';
        dataModalTitle.textContent = isEdit ? 'Edit Data' : 'Add Data';
        dataSubmitButton.textContent = isEdit ? 'Update Data' : 'Save Data';
    }

    function fillDataForm(record) {
        dataIdInput.value = record.id || '';
        document.getElementById('form-student-number').value = record.student_number || record.lrn || '';
        document.getElementById('form-rfid').value = record.rfid || '';
        document.getElementById('form-name').value = record.name || '';
        document.getElementById('form-role').value = record.role || '';
        document.getElementById('form-email').value = record.email || '';
        document.getElementById('form-contact').value = record.contact || '';
        document.getElementById('form-department').value = record.department || '';
        document.getElementById('form-course').value = record.course || '';
        document.getElementById('form-school-level').value = record.school_level || '';
        document.getElementById('form-grade-level').value = record.grade_level || '';
    }

    function openAddDataModal() {
        hideMessage();
        resetDataForm(false);
        openModal(dataModal);
    }

    async function openEditDataModal(id) {
        hideMessage();
        resetDataForm(true);

        const response = await fetch(`${dataRoutes.base}/${id}`, {
            headers: { 'Accept': 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            showMessage(payload.message || 'Unable to load record details.', 'error');
            return;
        }

        fillDataForm(payload.record);
        openModal(dataModal);
    }

    function openDeleteModal(id, name) {
        hideMessage();
        dataToDelete = id;
        deleteModalText.textContent = `Are you sure you want to delete ${name}?`;
        openModal(deleteModal);
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

    openAddDataModalButton?.addEventListener('click', () => {
        openAddDataModal();
    });

    printDataButton?.addEventListener('click', () => {
        window.location.href = buildDataFilterUrl(dataRoutes.print).toString();
    });

    exportDataButton?.addEventListener('click', () => {
        window.location.href = buildDataFilterUrl(dataRoutes.export).toString();
    });

    searchDataInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => fetchData(1), 350);
    });

    [filterNameSort, filterDepartment, filterCourse, filterYearLevel].forEach((select) => {
        select.addEventListener('change', () => fetchData(1));
    });

    dataPagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchData(Number(button.dataset.page));
    });

    dataTableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) {
            return;
        }

        const { action, id, name } = button.dataset;

        try {
            if (action === 'edit') {
                await openEditDataModal(id);
            }

            if (action === 'print') {
                window.location.href = buildSingleDataPrintUrl(id).toString();
            }

            if (action === 'delete') {
                openDeleteModal(id, name);
            }
        } catch (error) {
            showMessage(error.message || 'Action failed.', 'error');
        }
    });

    dataForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const recordId = dataIdInput.value;
        const formData = new FormData();
        formData.set('student_number', document.getElementById('form-student-number').value);
        formData.set('rfid', document.getElementById('form-rfid').value);
        formData.set('name', document.getElementById('form-name').value);
        formData.set('role', document.getElementById('form-role').value);
        formData.set('email', document.getElementById('form-email').value);
        formData.set('contact', document.getElementById('form-contact').value);
        formData.set('department', document.getElementById('form-department').value);
        formData.set('course', document.getElementById('form-course').value);
        formData.set('school_level', document.getElementById('form-school-level').value);
        formData.set('grade_level', document.getElementById('form-grade-level').value);

        if (recordId) {
            formData.set('_method', 'PUT');
        }

        try {
            const payload = await sendRequest(
                recordId ? `${dataRoutes.base}/${recordId}` : dataRoutes.store,
                'POST',
                formData
            );

            closeModal(dataModal);
            showMessage(payload.message || 'Student data saved successfully.', 'success');
            fetchData(dataCurrentPage);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    confirmDeleteDataButton.addEventListener('click', async () => {
        hideMessage();

        const formData = new FormData();
        formData.set('_method', 'DELETE');

        try {
            const payload = await sendRequest(`${dataRoutes.base}/${dataToDelete}`, 'POST', formData);
            closeModal(deleteModal);
            showMessage(payload.message || 'Student data deleted successfully.', 'success');
            fetchData(1);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(document.getElementById(button.dataset.closeModal));
        });
    });

    [detailsModal, dataModal, deleteModal, messageModal].forEach((modal) => {
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

    fetchData();
</script>

@endsection
