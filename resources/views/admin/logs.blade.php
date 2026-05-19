@extends('layouts.app')
@section('title', 'Logs')
@section('content')

<main class="w-full p-2 gap-2 flex flex-1 flex-col overflow-hidden">
    <h3 class="text-lg font-semibold text-slate-800">Logs</h3>

    <section class="w-full flex flex-1 justify-center p-2 overflow-hidden">
        <div class="w-full bg-white rounded-md shadow-sm overflow-hidden border border-slate-200 flex flex-col min-h-0">
            <div class="w-full px-3 py-3 border-b border-slate-200 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
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
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden">
                <table class="w-full text-left">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-white">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="px-3 py-2.5">Status</th>
                            <th class="px-3 py-2.5">DateTime</th>
                        </tr>
                    </thead>

                    <tbody id="logs-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="5" class="px-3 py-5 text-center text-slate-500">Loading logs...</td>
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

<script>
    const logsRoutes = {
        fetch: @json(route('admin.logs.fetch')),
    };

    const searchLogsInput = document.getElementById('search-logs');
    const searchLogsButton = document.getElementById('search-logs-button');
    const logsTableBody = document.getElementById('logs-table-body');
    const logsTableSummary = document.getElementById('table-summary');
    const logsPagination = document.getElementById('pagination');

    let logsCurrentPage = 1;

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function formatTime(value) {
        if (!value) {
            return 'N/A';
        }

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleString();
    }

    function renderLogRows(logs, from) {
        if (!logs.length) {
            logsTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-3 py-5 text-center text-slate-500">No logs found.</td>
                </tr>
            `;
            return;
        }

        logsTableBody.innerHTML = logs.map((log, index) => `
            <tr class="border-b border-black hover:bg-gray-50 transition">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${escapeHtml(log.student_id)}</td>
                <td class="px-3 py-2.5">${escapeHtml(log.name)}</td>
                <td class="px-3 py-2.5">${escapeHtml(log.status)}</td>
                <td class="px-3 py-2.5">${escapeHtml(formatTime(log.time))}</td>
            </tr>
        `).join('');
    }

    function renderLogsPagination(meta) {
        if (meta.last_page <= 1) {
            logsPagination.innerHTML = '';
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

        logsPagination.innerHTML = buttons.join('');
    }

    async function fetchLogs(page = 1) {
        logsCurrentPage = page;
        logsTableBody.innerHTML = `
            <tr>
                <td colspan="5" class="px-3 py-5 text-center text-slate-500">Loading logs...</td>
            </tr>
        `;

        const url = new URL(logsRoutes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        if (searchLogsInput.value.trim() !== '') {
            url.searchParams.set('search', searchLogsInput.value.trim());
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
                    <td colspan="5" class="px-3 py-5 text-center text-rose-600">Unable to load logs right now.</td>
                </tr>
            `;
            logsTableSummary.textContent = 'Log list unavailable';
        }
    }

    searchLogsButton.addEventListener('click', () => {
        fetchLogs(1);
    });

    searchLogsInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            fetchLogs(1);
        }
    });

    logsPagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchLogs(Number(button.dataset.page));
    });

    fetchLogs();
</script>

@endsection
