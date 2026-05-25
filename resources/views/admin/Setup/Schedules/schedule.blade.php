@extends('layouts.app')

@section('title', 'Schedule Management')

@section('content')

<main class="eg-setup-page">
    <section class="eg-setup-hero">
        <div>
            <p class="eg-setup-kicker">Setup Module</p>
            <h1 class="eg-setup-title">Schedule Management</h1>
            <p class="eg-setup-subtitle">Create schedules and configure daily time-in/time-out rules.</p>
        </div>
    </section>

    <section class="eg-setup-table-card">
        <div class="eg-setup-table-inner">
            <div class="eg-setup-toolbar">
                <div class="eg-setup-search">
                        <label for="search-data" class="sr-only">Search Schedule</label>
                        <input
                            id="search-data"
                            type="text"
                            placeholder="Search Schedule"
                            class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div class="eg-setup-toolbar-actions">
                        @can('setschedcehed.create')
                        <button
                            type="button"
                            id="open-add-schedule-modal"
                            class="eg-setup-button eg-setup-button--primary"
                        >
                            Add Schedule
                        </button>
                        @endcan
                    </div>
                </div>

            <div class="eg-setup-table-wrap">
                <table class="eg-setup-table">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="px-3 py-2.5 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="schedules-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading schedules...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="eg-setup-footer">
                <p id="table-summary" class="text-sm text-slate-600">Preparing schedule list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>

        </div>

    </section>

</main>

<div id="schedule-modal" class="eg-setup-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-setup-modal-panel w-full max-w-md rounded-xl bg-white shadow-2xl flex flex-col overflow-hidden">
        <form id="schedule-form">
            <input type="hidden" id="schedule-id">

            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                <h4 id="schedule-modal-title" class="text-lg font-bold text-gray-900">Add Schedule</h4>
                <button type="button" data-close-modal="schedule-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div class="px-4 py-3">
                <div class="flex flex-col gap-1">
                    <label for="form-schedule-name">Name</label>
                    <input id="form-schedule-name" type="text" class="w-full rounded-full border border-black/70 px-3 py-2 outline-none" required>
                </div>
            </div>

            <div class="px-4 py-3 border-t border-slate-200 flex justify-center gap-2">
                <button type="button" data-close-modal="schedule-modal" class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                    Cancel
                </button>
                @canany(['setschedcehed.create', 'setschedcehed.update'])
                <button type="submit" id="schedule-submit-button" class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600 hover:scale-105">
                    Save Schedule
                </button>
                @endcanany
            </div>
        </form>
    </div>
</div>

<div id="schedule-details-modal" class="eg-setup-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6">
    <div class="eg-setup-modal-panel w-full max-w-5xl max-h-[90vh] rounded-xl bg-white shadow-2xl flex flex-col overflow-hidden">
        <form id="schedule-details-form" class="flex min-h-0 flex-col">
            <input type="hidden" id="details-schedule-id">

            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                <div>
                    <h4 id="schedule-details-modal-title" class="text-lg font-bold text-gray-900">Schedule Details</h4>
                    <p class="text-sm text-slate-500">Days 1-7, Monday to Sunday</p>
                </div>
                <button type="button" data-close-modal="schedule-details-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            @can('setschedcehed.update')
            <div class="eg-setup-details-tools">
                <div class="eg-setup-time-grid">
                    <div class="eg-setup-field">
                        <label for="fill-am-in" class="text-sm font-medium text-slate-700">AM In</label>
                        <input id="fill-am-in" type="time" value="08:00" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none">
                    </div>
                    <div class="eg-setup-field">
                        <label for="fill-am-out" class="text-sm font-medium text-slate-700">AM Out</label>
                        <input id="fill-am-out" type="time" value="12:00" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none">
                    </div>
                    <div class="eg-setup-field">
                        <label for="fill-pm-in" class="text-sm font-medium text-slate-700">PM In</label>
                        <input id="fill-pm-in" type="time" value="13:00" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none">
                    </div>
                    <div class="eg-setup-field">
                        <label for="fill-pm-out" class="text-sm font-medium text-slate-700">PM Out</label>
                        <input id="fill-pm-out" type="time" value="17:00" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none">
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input id="fill-working-days" type="checkbox" checked class="h-4 w-4 rounded border-slate-300">
                        Working day
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input id="fill-weekend-days" type="checkbox" class="h-4 w-4 rounded border-slate-300">
                        Weekend
                    </label>
                    <button type="button" id="autofill-schedule-details" class="eg-setup-button eg-setup-button--primary">
                        Auto Fill
                    </button>
                    <button type="button" id="clear-schedule-details" class="eg-setup-button eg-setup-button--ghost">
                        Clear Schedule
                    </button>
                </div>
            </div>
            @endcan

            <div class="eg-setup-table-wrap eg-setup-table-wrap--modal">
                <table class="eg-setup-table">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">Day</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="px-3 py-2.5">AM In</th>
                            <th class="px-3 py-2.5">AM Out</th>
                            <th class="px-3 py-2.5">PM In</th>
                            <th class="px-3 py-2.5">PM Out</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-details-table-body" class="divide-y divide-black"></tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-200 flex justify-center gap-2">
                <button type="button" data-close-modal="schedule-details-modal" class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                    Cancel
                </button>
                @can('setschedcehed.update')
                <button type="submit" class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600 hover:scale-105">
                    Save Details
                </button>
                @endcan
            </div>
        </form>
    </div>
</div>

<div id="delete-schedule-modal" class="eg-setup-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-setup-modal-panel w-full max-w-sm rounded-xl bg-white p-4 shadow-2xl flex flex-col items-center text-center">
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="space-y-2">
            <h4 class="text-xl font-semibold text-gray-900">Delete Schedule</h4>
            <p id="delete-schedule-modal-text" class="text-sm text-gray-500 leading-relaxed">Are you sure you want to delete this schedule?</p>
        </div>
        <div class="mt-6 w-full grid grid-cols-2 gap-2 justify-items-center">
            <button type="button" data-close-modal="delete-schedule-modal" class="w-full rounded-full bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                Cancel
            </button>
            <button type="button" id="confirm-delete-schedule" class="w-full rounded-full bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-red-700 active:scale-[0.98]">
                Delete
            </button>
        </div>
    </div>
</div>

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
    const scheduleRoutes = {
        fetch: @json(route('admin.setup.schedules.fetch')),
        store: @json(route('admin.setup.schedules.store')),
        base: @json(url('admin/setup/schedules')),
    };
    const canCreateSchedule = @json(auth()->user()?->can('setschedcehed.create'));
    const canUpdateSchedule = @json(auth()->user()?->can('setschedcehed.update'));
    const canDeleteSchedule = @json(auth()->user()?->can('setschedcehed.delete'));
    const scheduleDays = [
        { day: 1, name: 'Monday' },
        { day: 2, name: 'Tuesday' },
        { day: 3, name: 'Wednesday' },
        { day: 4, name: 'Thursday' },
        { day: 5, name: 'Friday' },
        { day: 6, name: 'Saturday' },
        { day: 7, name: 'Sunday' },
    ];

    const searchScheduleInput = document.getElementById('search-data');
    const schedulesTableBody = document.getElementById('schedules-table-body');
    const schedulesTableSummary = document.getElementById('table-summary');
    const schedulesPagination = document.getElementById('pagination');
    const scheduleModal = document.getElementById('schedule-modal');
    const scheduleForm = document.getElementById('schedule-form');
    const scheduleIdInput = document.getElementById('schedule-id');
    const scheduleNameInput = document.getElementById('form-schedule-name');
    const scheduleModalTitle = document.getElementById('schedule-modal-title');
    const scheduleSubmitButton = document.getElementById('schedule-submit-button');
    const openAddScheduleModalButton = document.getElementById('open-add-schedule-modal');
    const scheduleDetailsModal = document.getElementById('schedule-details-modal');
    const scheduleDetailsForm = document.getElementById('schedule-details-form');
    const detailsScheduleIdInput = document.getElementById('details-schedule-id');
    const scheduleDetailsModalTitle = document.getElementById('schedule-details-modal-title');
    const scheduleDetailsTableBody = document.getElementById('schedule-details-table-body');
    const fillAmInInput = document.getElementById('fill-am-in');
    const fillAmOutInput = document.getElementById('fill-am-out');
    const fillPmInInput = document.getElementById('fill-pm-in');
    const fillPmOutInput = document.getElementById('fill-pm-out');
    const fillWorkingDaysInput = document.getElementById('fill-working-days');
    const fillWeekendDaysInput = document.getElementById('fill-weekend-days');
    const autofillScheduleDetailsButton = document.getElementById('autofill-schedule-details');
    const clearScheduleDetailsButton = document.getElementById('clear-schedule-details');
    const deleteScheduleModal = document.getElementById('delete-schedule-modal');
    const deleteScheduleModalText = document.getElementById('delete-schedule-modal-text');
    const confirmDeleteScheduleButton = document.getElementById('confirm-delete-schedule');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');

    let schedulesCurrentPage = 1;
    let schedulesSearchTimer = null;
    let messageHideTimer = null;
    let scheduleToDelete = null;

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
            messageHideTimer = null;
        }

        messageModalPanel.classList.remove('scale-100', 'opacity-100');
        messageModalPanel.classList.add('scale-95', 'opacity-0');

        window.setTimeout(() => {
            closeModal(messageModal);
        }, 150);
    }

    function renderScheduleRows(schedules, from) {
        if (!schedules.length) {
            schedulesTableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-slate-500">No schedules found.</td>
                </tr>
            `;
            return;
        }

        schedulesTableBody.innerHTML = schedules.map((schedule, index) => {
            const actionButtons = [
                canUpdateSchedule ? `
                        <button type="button" data-action="edit" data-id="${schedule.id}" class="eg-setup-icon-button">
                            <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="edit schedule name">
                        </button>
                ` : '',
                `
                        <button type="button" data-action="details" data-id="${schedule.id}" class="eg-setup-icon-button">
                            <img src="{{ asset('icons/schedule.png') }}" class="w-7 h-7" alt="view schedule details">
                        </button>
                `,
                canDeleteSchedule ? `
                        <button type="button" data-action="delete" data-id="${schedule.id}" data-name="${escapeHtml(schedule.name || 'this schedule')}" class="eg-setup-icon-button eg-setup-icon-button--danger">
                            <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete schedule">
                        </button>
                ` : '',
            ].join('');

            return `
            <tr class="eg-setup-row">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(schedule.id)}</td>
                <td class="px-3 py-2.5">${escapeHtml(schedule.name || 'N/A')}</td>
                <td class="px-3 py-2.5">
                    <div class="eg-setup-actions-cell">
                        ${actionButtons || '<span class="text-sm text-slate-400">N/A</span>'}
                    </div>
                </td>
            </tr>
        `;
        }).join('');
    }

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            schedulesPagination.innerHTML = '';
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

        schedulesPagination.innerHTML = buttons.join('');
    }

    async function fetchSchedules(page = 1) {
        schedulesCurrentPage = page;
        schedulesTableBody.innerHTML = `
            <tr>
                <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading schedules...</td>
            </tr>
        `;

        const url = new URL(scheduleRoutes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        if (searchScheduleInput.value.trim() !== '') {
            url.searchParams.set('search', searchScheduleInput.value.trim());
        }

        try {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            const payload = await response.json();

            renderScheduleRows(payload.data || [], payload.from || 1);
            renderPagination(payload);
            schedulesTableSummary.textContent = payload.total
                ? `Showing ${payload.from} to ${payload.to} of ${payload.total} schedules`
                : 'No schedules available';
        } catch (error) {
            schedulesTableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-rose-600">Unable to load schedules right now.</td>
                </tr>
            `;
            schedulesTableSummary.textContent = 'Schedule list unavailable';
            showMessage('Unable to load schedules right now.', 'error');
        }
    }

    function resetScheduleForm(isEdit = false) {
        scheduleForm.reset();
        scheduleIdInput.value = '';
        scheduleModalTitle.textContent = isEdit ? 'Edit Schedule' : 'Add Schedule';
        scheduleSubmitButton.textContent = isEdit ? 'Update Schedule' : 'Save Schedule';
    }

    function openAddScheduleModal() {
        hideMessage();
        resetScheduleForm(false);
        openModal(scheduleModal);
    }

    async function openEditScheduleModal(id) {
        hideMessage();
        resetScheduleForm(true);

        const response = await fetch(`${scheduleRoutes.base}/${id}`, {
            headers: { 'Accept': 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            showMessage(payload.message || 'Unable to load schedule details.', 'error');
            return;
        }

        scheduleIdInput.value = payload.schedule.id || '';
        scheduleNameInput.value = payload.schedule.name || '';
        openModal(scheduleModal);
    }

    function renderScheduleDetailsRows(details = []) {
        const detailsByDay = new Map(details.map((detail) => [Number(detail.day), detail]));

        scheduleDetailsTableBody.innerHTML = scheduleDays.map(({ day, name }) => {
            const detail = detailsByDay.get(day) || {};
            const disabled = canUpdateSchedule ? '' : 'disabled';

            return `
                <tr class="eg-setup-row">
                    <td class="px-3 py-2.5">${day}</td>
                    <td class="px-3 py-2.5">${name}</td>
                    <td class="px-3 py-2.5">
                        <input type="time" data-day="${day}" data-field="am_in" value="${escapeHtml(detail.am_in || '')}" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none" ${disabled}>
                    </td>
                    <td class="px-3 py-2.5">
                        <input type="time" data-day="${day}" data-field="am_out" value="${escapeHtml(detail.am_out || '')}" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none" ${disabled}>
                    </td>
                    <td class="px-3 py-2.5">
                        <input type="time" data-day="${day}" data-field="pm_in" value="${escapeHtml(detail.pm_in || '')}" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none" ${disabled}>
                    </td>
                    <td class="px-3 py-2.5">
                        <input type="time" data-day="${day}" data-field="pm_out" value="${escapeHtml(detail.pm_out || '')}" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none" ${disabled}>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function openScheduleDetailsModal(id) {
        hideMessage();
        detailsScheduleIdInput.value = id;
        scheduleDetailsModalTitle.textContent = 'Schedule Details';
        scheduleDetailsTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading schedule details...</td>
            </tr>
        `;
        openModal(scheduleDetailsModal);

        const response = await fetch(`${scheduleRoutes.base}/${id}/details`, {
            headers: { 'Accept': 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            closeModal(scheduleDetailsModal);
            showMessage(payload.message || 'Unable to load schedule details.', 'error');
            return;
        }

        scheduleDetailsModalTitle.textContent = `${payload.schedule.name} Details`;
        renderScheduleDetailsRows(payload.details || []);
    }

    function autofillScheduleDetails() {
        const targetDays = [];

        if (fillWorkingDaysInput.checked) {
            targetDays.push(1, 2, 3, 4, 5);
        }

        if (fillWeekendDaysInput.checked) {
            targetDays.push(6, 7);
        }

        const values = {
            am_in: fillAmInInput.value,
            am_out: fillAmOutInput.value,
            pm_in: fillPmInInput.value,
            pm_out: fillPmOutInput.value,
        };

        targetDays.forEach((day) => {
            Object.entries(values).forEach(([field, value]) => {
                const input = scheduleDetailsTableBody.querySelector(`[data-day="${day}"][data-field="${field}"]`);
                if (input) {
                    input.value = value;
                }
            });
        });
    }

    function clearScheduleDetails() {
        scheduleDetailsTableBody.querySelectorAll('input[type="time"]').forEach((input) => {
            input.value = '';
        });
    }

    function collectScheduleDetails() {
        return scheduleDays.map(({ day }) => {
            const detail = { day };

            ['am_in', 'am_out', 'pm_in', 'pm_out'].forEach((field) => {
                detail[field] = scheduleDetailsTableBody.querySelector(`[data-day="${day}"][data-field="${field}"]`)?.value || '';
            });

            return detail;
        });
    }

    function openDeleteScheduleModal(id, name) {
        hideMessage();
        scheduleToDelete = id;
        deleteScheduleModalText.textContent = `Are you sure you want to delete ${name}?`;
        openModal(deleteScheduleModal);
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

    async function sendJsonRequest(url, method, body) {
        const response = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body),
        });
        const payload = await response.json();

        if (!response.ok) {
            throw new Error(payload.message || 'Request failed.');
        }

        return payload;
    }

    openAddScheduleModalButton?.addEventListener('click', () => {
        openAddScheduleModal();
    });

    searchScheduleInput.addEventListener('input', () => {
        clearTimeout(schedulesSearchTimer);
        schedulesSearchTimer = window.setTimeout(() => fetchSchedules(1), 350);
    });

    schedulesPagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchSchedules(Number(button.dataset.page));
    });

    schedulesTableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) {
            return;
        }

        const { action, id, name } = button.dataset;

        try {
            if (action === 'edit') {
                await openEditScheduleModal(id);
            }

            if (action === 'details') {
                await openScheduleDetailsModal(id);
            }

            if (action === 'delete') {
                openDeleteScheduleModal(id, name);
            }
        } catch (error) {
            showMessage(error.message || 'Action failed.', 'error');
        }
    });

    autofillScheduleDetailsButton?.addEventListener('click', () => {
        autofillScheduleDetails();
    });

    clearScheduleDetailsButton?.addEventListener('click', () => {
        clearScheduleDetails();
    });

    scheduleForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const scheduleId = scheduleIdInput.value;
        const formData = new FormData();
        formData.set('name', scheduleNameInput.value);

        if (scheduleId) {
            formData.set('_method', 'PUT');
        }

        try {
            const payload = await sendRequest(
                scheduleId ? `${scheduleRoutes.base}/${scheduleId}` : scheduleRoutes.store,
                'POST',
                formData
            );

            closeModal(scheduleModal);
            showMessage(payload.message || 'Schedule saved successfully.', 'success');
            fetchSchedules(schedulesCurrentPage);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    scheduleDetailsForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const scheduleId = detailsScheduleIdInput.value;

        try {
            const payload = await sendJsonRequest(`${scheduleRoutes.base}/${scheduleId}/details`, 'POST', {
                details: collectScheduleDetails(),
            });
            closeModal(scheduleDetailsModal);
            showMessage(payload.message || 'Schedule details saved successfully.', 'success');
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    confirmDeleteScheduleButton.addEventListener('click', async () => {
        hideMessage();

        const formData = new FormData();
        formData.set('_method', 'DELETE');

        try {
            const payload = await sendRequest(`${scheduleRoutes.base}/${scheduleToDelete}`, 'POST', formData);
            closeModal(deleteScheduleModal);
            showMessage(payload.message || 'Schedule deleted successfully.', 'success');
            fetchSchedules(1);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(document.getElementById(button.dataset.closeModal));
        });
    });

    [scheduleModal, scheduleDetailsModal, deleteScheduleModal, messageModal].forEach((modal) => {
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

    fetchSchedules();
</script>

@endsection
