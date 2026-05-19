@extends('layouts.app')
@section('title', 'Data')
@section('content')

<main class="w-full p-2 gap-2 flex flex-1 flex-col overflow-hidden">
    <h3 class="text-lg font-semibold text-slate-800">Student Data</h3>

    <section class="w-full flex flex-1 justify-center p-2 overflow-hidden">
        <div class="w-full bg-white rounded-md shadow-sm overflow-hidden border border-slate-200 flex flex-col min-h-0">
            <div class="w-full px-3 py-3 border-b border-slate-200 flex flex-col gap-2">
                <div class="w-full md:max-w-sm">
                    <label for="search-data" class="sr-only">Search student data</label>
                    <input
                        id="search-data"
                        type="text"
                        placeholder="Search name, ID, department, course"
                        class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <div class="grid gap-2 md:grid-cols-3">
                    <div class="flex flex-col gap-1">
                        <label for="filter-department" class="text-sm font-medium text-slate-700">Department</label>
                        <select id="filter-department" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="filter-course" class="text-sm font-medium text-slate-700">Course</label>
                        <select id="filter-course" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All courses</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="filter-year-level" class="text-sm font-medium text-slate-700">Year Level</label>
                        <select id="filter-year-level" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">All year levels</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel }}">{{ $yearLevel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden">
                <table class="w-full text-left">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-white">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
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

            <div class="px-3 py-2.5 border-t border-slate-200 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <p id="table-summary" class="text-sm text-slate-600">Preparing data list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="details-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-2xl max-h-[90vh] rounded-xl bg-white shadow-2xl flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <h4 class="text-lg font-bold text-gray-900">Student Details</h4>
            <button type="button" data-close-modal="details-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
        </div>

        <div class="overflow-y-auto px-4 py-3">
            <form class="grid gap-3 md:grid-cols-2">
                <div class="flex flex-col gap-1">
                <label>Student ID</label>
                <input id="detail-student-number" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Last Name</label>
                <input id="detail-last-name" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>First Name</label>
                <input id="detail-first-name" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Middle Name</label>
                <input id="detail-middle-name" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Sex</label>
                <input id="detail-sex" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Department</label>
                <input id="detail-department" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Course</label>
                <input id="detail-course" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Year Level</label>
                <input id="detail-year-level" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Grade Level</label>
                <input id="detail-grade-level" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Status</label>
                <input id="detail-status" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Logged At</label>
                <input id="detail-logged-at" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1">
                <label>Gate Name</label>
                <input id="detail-gate-name" type="text" readonly class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-slate-50">
                </div>

                <div class="flex flex-col gap-1 md:col-span-2">
                <label>Remarks</label>
                <textarea id="detail-remarks" rows="3" readonly class="w-full rounded-2xl border-1 border-black/70 px-3 py-2 outline-none bg-slate-50 resize-none"></textarea>
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

<script>
    const dataRoutes = {
        fetch: @json(route('admin.data.fetch')),
        base: @json(url('admin/data')),
    };

    const searchDataInput = document.getElementById('search-data');
    const filterDepartment = document.getElementById('filter-department');
    const filterCourse = document.getElementById('filter-course');
    const filterYearLevel = document.getElementById('filter-year-level');
    const dataTableBody = document.getElementById('data-table-body');
    const dataTableSummary = document.getElementById('table-summary');
    const dataPagination = document.getElementById('pagination');
    const detailsModal = document.getElementById('details-modal');

    let dataCurrentPage = 1;
    let searchTimer = null;

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function formatName(record) {
        return [record.last_name, record.first_name, record.middle_name].filter(Boolean).join(', ').replace(', ,', ',');
    }

    function formatNameCell(record) {
        const lastName = record.last_name || '';
        const firstName = record.first_name || '';
        const middleName = record.middle_name || '';
        return `${lastName}, ${firstName}${middleName ? ` ${middleName}` : ''}`.trim();
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

        dataTableBody.innerHTML = records.map((record, index) => `
            <tr class="border-b border-black hover:bg-gray-50 transition">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(record.student_number)}</td>
                <td class="px-3 py-2.5">${escapeHtml(formatNameCell(record))}</td>
                <td class="px-3 py-2.5">${escapeHtml(record.department || 'N/A')}</td>
                <td class="px-3 py-2.5">${escapeHtml(record.course || 'N/A')}</td>
                <td class="px-3 py-2.5 text-center">
                    <button type="button" data-action="view" data-id="${record.id}" class="transition duration-200 hover:scale-110">
                        <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="view data">
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            dataPagination.innerHTML = '';
            return;
        }

        const buttons = [];
        buttons.push(`
            <button type="button" class="rounded-full border px-3 py-1 text-sm ${meta.current_page === 1 ? 'cursor-not-allowed border-slate-200 text-slate-400' : 'border-slate-300 text-slate-700 hover:bg-slate-50'}" data-page="${meta.current_page - 1}" ${meta.current_page === 1 ? 'disabled' : ''}>
                Prev
            </button>
        `);

        for (let page = 1; page <= meta.last_page; page += 1) {
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
        }
    }

    function openModal(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function fillDetails(record) {
        document.getElementById('detail-student-number').value = record.student_number || '';
        document.getElementById('detail-last-name').value = record.last_name || '';
        document.getElementById('detail-first-name').value = record.first_name || '';
        document.getElementById('detail-middle-name').value = record.middle_name || '';
        document.getElementById('detail-sex').value = record.sex || '';
        document.getElementById('detail-department').value = record.department || '';
        document.getElementById('detail-course').value = record.course || '';
        document.getElementById('detail-year-level').value = record.year_level || '';
        document.getElementById('detail-grade-level').value = record.grade_level || '';
        document.getElementById('detail-status').value = record.status || '';
        document.getElementById('detail-logged-at').value = record.logged_at || '';
        document.getElementById('detail-gate-name').value = record.gate_name || '';
        document.getElementById('detail-remarks').value = record.remarks || '';
    }

    async function openDetails(id) {
        const response = await fetch(`${dataRoutes.base}/${id}`, {
            headers: { 'Accept': 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            return;
        }

        fillDetails(payload.record);
        openModal(detailsModal);
    }

    searchDataInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => fetchData(1), 350);
    });

    [filterDepartment, filterCourse, filterYearLevel].forEach((select) => {
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
        const button = event.target.closest('[data-action="view"]');
        if (!button) {
            return;
        }

        await openDetails(button.dataset.id);
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(document.getElementById(button.dataset.closeModal));
        });
    });

    detailsModal.addEventListener('click', (event) => {
        if (event.target === detailsModal) {
            closeModal(detailsModal);
        }
    });

    fetchData();
</script>

@endsection
