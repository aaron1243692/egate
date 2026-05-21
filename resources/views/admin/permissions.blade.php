@extends('layouts.app')
@section('title', 'permissions')
@section('content')

<main class="w-full p-2 gap-2 flex flex-1 flex-col overflow-hidden">
    <h3 class="text-lg font-semibold text-slate-800">Permissions</h3>

    <section class="w-full flex flex-1 justify-center p-2 overflow-hidden">
        <div class="w-full bg-white rounded-md shadow-sm overflow-hidden border border-slate-200 flex flex-col min-h-0">
            <div class="w-full px-3 py-3 border-b border-slate-200 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div class="w-full md:max-w-sm">
                    <label for="search-permissions" class="sr-only">Search permissions</label>
                    <input
                        id="search-permissions"
                        type="text"
                        placeholder="Search permission name"
                        class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <button
                    type="button"
                    id="open-add-permission-modal"
                    class="rounded-full bg-blue-600 px-4 py-1.5 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 hover:scale-105"
                >
                    Add Permission
                </button>
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

                    <tbody id="permissions-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading permissions...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-2.5 border-t border-slate-200 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <p id="table-summary" class="text-sm text-slate-600">Preparing permission list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="permission-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-md rounded-xl bg-white p-4 shadow-2xl">
        <form id="permission-form" class="flex flex-col items-center gap-2">
            <input type="hidden" id="permission-id">

            <div class="w-full flex items-center justify-between">
                <h4 id="permission-modal-title" class="text-lg font-bold text-gray-900">Add Permission</h4>
                <button type="button" data-close-modal="permission-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div class="w-full gap-1 flex flex-col">
                <label for="permission-name">Permission Name</label>
                <input id="permission-name" name="name" type="text" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full flex justify-center gap-2 pt-2">
                <button
                    type="button"
                    data-close-modal="permission-modal"
                    class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    id="permission-submit-button"
                    class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600 hover:scale-105"
                >
                    Save Permission
                </button>
            </div>
        </form>
    </div>
</div>

<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm rounded-xl bg-white p-4 shadow-2xl flex flex-col items-center text-center">
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="space-y-2">
            <h4 class="text-xl font-semibold text-gray-900">Delete Permission</h4>
            <p id="delete-modal-text" class="text-sm text-gray-500 leading-relaxed">Are you sure you want to delete this permission?</p>
        </div>
        <div class="mt-6 w-full grid grid-cols-2 gap-2 justify-items-center">
            <button
                type="button"
                data-close-modal="delete-modal"
                class="w-full rounded-full bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]"
            >
                Cancel
            </button>

            <button
                type="button"
                id="confirm-delete-permission"
                class="w-full rounded-full bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-red-700 active:scale-[0.98]"
            >
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
        <button
            type="button"
            id="close-message-modal"
            class="mt-6 w-full rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]"
        >
            Close
        </button>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const routes = {
        fetch: @json(route('admin.permissions.fetch')),
        store: @json(route('admin.permissions.store')),
        editBase: @json(url('admin/permissions')),
    };

    const searchInput = document.getElementById('search-permissions');
    const tableBody = document.getElementById('permissions-table-body');
    const tableSummary = document.getElementById('table-summary');
    const pagination = document.getElementById('pagination');
    const permissionModal = document.getElementById('permission-modal');
    const deleteModal = document.getElementById('delete-modal');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');
    const permissionForm = document.getElementById('permission-form');
    const permissionIdInput = document.getElementById('permission-id');
    const permissionNameInput = document.getElementById('permission-name');
    const permissionModalTitle = document.getElementById('permission-modal-title');
    const permissionSubmitButton = document.getElementById('permission-submit-button');
    const confirmDeleteButton = document.getElementById('confirm-delete-permission');
    const deleteModalText = document.getElementById('delete-modal-text');

    let searchTimer = null;
    let currentPage = 1;
    let permissionToDelete = null;
    let messageHideTimer = null;

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
                icon: 'bg-red-50 text-red-500',
                title: 'Action Failed',
            },
            info: {
                icon: 'bg-blue-50 text-blue-500',
                title: 'Notice',
            },
        };
        const activeTone = tones[tone] || tones.info;

        messageModalPanel.className = 'w-full max-w-sm scale-95 rounded-xl bg-white p-4 text-center opacity-0 shadow-2xl transition duration-200';
        messageModalIcon.className = `mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-full ${activeTone.icon}`;
        messageModalTitle.textContent = activeTone.title;
        messageModalText.className = 'text-sm text-gray-500 leading-relaxed';
        messageModalText.textContent = message;

        messageModal.classList.remove('hidden');
        messageModal.classList.add('flex');

        window.requestAnimationFrame(() => {
            messageModalPanel.classList.remove('scale-95', 'opacity-0');
            messageModalPanel.classList.add('scale-100', 'opacity-100');
        });
    }

    function hideMessage() {
        if (messageHideTimer) {
            window.clearTimeout(messageHideTimer);
            messageHideTimer = null;
        }

        if (messageModal.classList.contains('hidden')) {
            return;
        }

        messageModalPanel.classList.remove('scale-100', 'opacity-100');
        messageModalPanel.classList.add('scale-95', 'opacity-0');

        messageHideTimer = window.setTimeout(() => {
            messageModal.classList.add('hidden');
            messageModal.classList.remove('flex');
            messageModalText.textContent = '';
            messageHideTimer = null;
        }, 200);
    }

    function openModal(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function renderRows(permissions, from) {
        if (!permissions.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-slate-500">No permissions found.</td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = permissions.map((permission, index) => `
            <tr class="border-b border-black hover:bg-gray-50 transition">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${permission.id}</td>
                <td class="px-3 py-2.5">${escapeHtml(permission.name)}</td>
                <td class="px-3 py-2.5">
                    <div class="flex flex-row justify-center items-center gap-2.5">
                        <button type="button" class="transition duration-200 hover:scale-110" data-action="edit" data-id="${permission.id}">
                            <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="edit permission">
                        </button>
                        <button type="button" class="transition duration-200 hover:scale-110" data-action="delete" data-id="${permission.id}" data-name="${escapeHtml(permission.name)}">
                            <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete permission">
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(meta) {
        if (meta.last_page <= 1) {
            pagination.innerHTML = '';
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

        pagination.innerHTML = buttons.join('');
    }

    async function fetchPermissions(page = 1) {
        currentPage = page;
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading permissions...</td>
            </tr>
        `;

        const url = new URL(routes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        if (searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                },
            });
            const payload = await response.json();

            renderRows(payload.data || [], payload.from || 1);
            renderPagination(payload);
            tableSummary.textContent = payload.total
                ? `Showing ${payload.from} to ${payload.to} of ${payload.total} permissions`
                : 'No permissions available';
        } catch (error) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-rose-600">Unable to load permissions right now.</td>
                </tr>
            `;
            tableSummary.textContent = 'Permission list unavailable';
            showMessage('Unable to load permissions right now.', 'error');
        }
    }

    function resetPermissionForm(isEdit = false) {
        permissionForm.reset();
        permissionIdInput.value = '';
        permissionModalTitle.textContent = isEdit ? 'Edit Permission' : 'Add Permission';
        permissionSubmitButton.textContent = isEdit ? 'Update Permission' : 'Save Permission';
    }

    function openAddPermissionModal() {
        hideMessage();
        resetPermissionForm(false);
        openModal(permissionModal);
    }

    async function openEditPermissionModal(permissionId) {
        hideMessage();
        resetPermissionForm(true);

        const response = await fetch(`${routes.editBase}/${permissionId}/edit`, {
            headers: {
                'Accept': 'application/json',
            },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            showMessage(payload.message || 'Unable to load permission details.', 'error');
            return;
        }

        permissionIdInput.value = payload.permission.id;
        permissionNameInput.value = payload.permission.name;
        openModal(permissionModal);
    }

    function openDeleteModal(permissionId, permissionName) {
        hideMessage();
        permissionToDelete = permissionId;
        deleteModalText.textContent = `Are you sure you want to delete ${permissionName}?`;
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

    document.getElementById('open-add-permission-modal').addEventListener('click', () => {
        openAddPermissionModal();
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => {
            fetchPermissions(1);
        }, 350);
    });

    pagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchPermissions(Number(button.dataset.page));
    });

    tableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) {
            return;
        }

        const { action, id, name } = button.dataset;

        try {
            if (action === 'edit') {
                await openEditPermissionModal(id);
            }

            if (action === 'delete') {
                openDeleteModal(id, name);
            }
        } catch (error) {
            showMessage(error.message || 'Action failed.', 'error');
        }
    });

    permissionForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const permissionId = permissionIdInput.value;
        const formData = new FormData();
        formData.set('name', permissionNameInput.value);

        if (permissionId) {
            formData.set('_method', 'PUT');
        }

        try {
            const payload = await sendRequest(
                permissionId ? `${routes.editBase}/${permissionId}` : routes.store,
                'POST',
                formData
            );

            closeModal(permissionModal);
            showMessage(payload.message || 'Permission saved successfully.', 'success');
            fetchPermissions(currentPage);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    confirmDeleteButton.addEventListener('click', async () => {
        hideMessage();

        const formData = new FormData();
        formData.set('_method', 'DELETE');

        try {
            const payload = await sendRequest(
                `${routes.editBase}/${permissionToDelete}`,
                'POST',
                formData
            );

            closeModal(deleteModal);
            showMessage(payload.message || 'Permission deleted successfully.', 'success');
            fetchPermissions(1);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(document.getElementById(button.dataset.closeModal));
        });
    });

    document.getElementById('close-message-modal').addEventListener('click', () => {
        hideMessage();
    });

    [permissionModal, deleteModal].forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

    messageModal.addEventListener('click', (event) => {
        if (event.target === messageModal) {
            hideMessage();
        }
    });

    fetchPermissions();
</script>

@endsection
