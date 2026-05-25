@extends('layouts.app')

@section('title', 'Employee Schedule Assignment')

@section('content')

<main class="eg-setup-page">
    <section class="eg-setup-hero">
        <div>
            <p class="eg-setup-kicker">Setup Module</p>
            <h1 class="eg-setup-title">Employee Schedule Assignment</h1>
            <p class="eg-setup-subtitle">Assign attendance schedules to employees and update their active schedule.</p>
        </div>
    </section>

    <section class="eg-setup-table-card">
        <div class="eg-setup-table-inner">
            <div class="eg-setup-toolbar">
                <div class="eg-setup-search">
                        <label for="search-employee-schedules" class="sr-only">Search Employee</label>
                        <input
                            id="search-employee-schedules"
                            type="text"
                            placeholder="Search employee or schedule"
                            class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                    </div>
                </div>

            <div class="eg-setup-table-wrap">
                <table class="eg-setup-table">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="w-90 px-3 py-2.5">Schedule</th>
                        </tr>
                    </thead>

                    <tbody id="employee-schedules-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading employees...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="eg-setup-footer">
                <p id="table-summary" class="text-sm text-slate-600">Preparing employee list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="message-modal" class="eg-setup-modal eg-setup-message fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div id="message-modal-panel" class="eg-setup-modal-panel w-full max-w-sm scale-95 rounded-xl bg-white p-4 text-center opacity-0 shadow-2xl transition duration-200">
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
    const employeeScheduleRoutes = {
        fetch: @json(route('admin.setup.employee.fetch')),
        base: @json(url('admin/setup/employee')),
    };
    const canUpdateEmployeeSchedule = @json(auth()->user()?->can('setschedem.update'));

    const searchInput = document.getElementById('search-employee-schedules');
    const tableBody = document.getElementById('employee-schedules-table-body');
    const tableSummary = document.getElementById('table-summary');
    const pagination = document.getElementById('pagination');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');

    let currentPage = 1;
    let searchTimer = null;
    let messageHideTimer = null;
    let scheduleOptions = [];

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
        }

        const tones = {
            success: { icon: 'bg-emerald-50 text-emerald-500', title: 'Success' },
            error: { icon: 'bg-rose-50 text-rose-500', title: 'Error' },
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

        messageHideTimer = window.setTimeout(() => hideMessage(), 2000);
    }

    function hideMessage() {
        if (messageHideTimer) {
            window.clearTimeout(messageHideTimer);
        }

        messageModalPanel.classList.remove('scale-100', 'opacity-100');
        messageModalPanel.classList.add('scale-95', 'opacity-0');

        window.setTimeout(() => closeModal(messageModal), 150);
    }

    function renderScheduleOptions(selectedId) {
        const selectedValue = String(selectedId ?? '');
        const options = ['<option value="">Default Schedule (8:00 AM - 5:00 PM)</option>'];

        scheduleOptions.forEach((schedule) => {
            const value = String(schedule.id);
            options.push(`
                <option value="${escapeHtml(value)}" ${value === selectedValue ? 'selected' : ''}>
                    ${escapeHtml(schedule.name)}
                </option>
            `);
        });

        return options.join('');
    }

    function renderRows(employees, from) {
        if (!employees.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-slate-500">No employees found.</td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = employees.map((employee, index) => `
            <tr class="eg-setup-row">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(employee.student_number || employee.id)}</td>
                <td class="px-3 py-2.5">${escapeHtml(employee.name || 'N/A')}</td>
                <td class="w-64 px-3 py-2.5">
                    <select data-schedule-select="${employee.id}" class="eg-setup-schedule-select" ${canUpdateEmployeeSchedule ? '' : 'disabled'}>
                        ${renderScheduleOptions(employee.sched)}
                    </select>
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            pagination.innerHTML = '';
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

        pagination.innerHTML = buttons.join('');
    }

    async function fetchEmployees(page = 1) {
        currentPage = page;
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading employees...</td>
            </tr>
        `;

        const url = new URL(employeeScheduleRoutes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        if (searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        try {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            const payload = await response.json();
            const employees = payload.employees || {};

            scheduleOptions = payload.schedules || [];
            renderRows(employees.data || [], employees.from || 1);
            renderPagination(employees);
            tableSummary.textContent = employees.total
                ? `Showing ${employees.from} to ${employees.to} of ${employees.total} employees`
                : 'No employees available';
        } catch (error) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-rose-600">Unable to load employees right now.</td>
                </tr>
            `;
            tableSummary.textContent = 'Employee list unavailable';
            showMessage('Unable to load employees right now.', 'error');
        }
    }

    async function updateEmployeeSchedule(id) {
        const select = tableBody.querySelector(`[data-schedule-select="${id}"]`);
        const formData = new FormData();
        formData.set('_method', 'PUT');
        formData.set('schedule_id', select?.value || '');

        const response = await fetch(`${employeeScheduleRoutes.base}/${id}/schedule`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });
        const payload = await response.json();

        if (!response.ok) {
            throw new Error(payload.message || 'Unable to update employee schedule.');
        }

        showMessage(payload.message || 'Employee schedule updated successfully.', 'success');
        fetchEmployees(currentPage);
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => fetchEmployees(1), 350);
    });

    pagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchEmployees(Number(button.dataset.page));
    });

    tableBody.addEventListener('change', async (event) => {
        const select = event.target.closest('[data-schedule-select]');
        if (!select) {
            return;
        }

        try {
            await updateEmployeeSchedule(select.dataset.scheduleSelect);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    messageModal.addEventListener('click', (event) => {
        if (event.target === messageModal) {
            hideMessage();
        }
    });

    document.getElementById('close-message-modal').addEventListener('click', () => {
        hideMessage();
    });

    fetchEmployees();
</script>

@endsection
