@extends('layouts.app')
@section('title', 'Role Management')
@section('content')

<main class="eg-access-page">
    <section class="eg-access-hero">
        <div>
            <p class="eg-access-kicker">Access Control</p>
            <h1 class="eg-access-title">Role Management</h1>
            <p class="eg-access-subtitle">Manage system roles and control module permissions for EGate administrators.</p>
        </div>
    </section>

    <section class="eg-access-guide-grid" aria-label="Role management guide">
        <article class="eg-access-guide-card">Roles define access groups.</article>
        <article class="eg-access-guide-card">Permissions control actions.</article>
        <article class="eg-access-guide-card">Changes affect admin access immediately.</article>
    </section>

    <section class="eg-access-card">
        <div class="eg-access-card-inner">
            <div class="eg-access-toolbar">
                <div class="eg-access-search">
                    <label for="search-roles" class="sr-only">Search roles</label>
                    <input
                        id="search-roles"
                        type="text"
                        placeholder="Search role name"
                        class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                @can('roles.create')<button
                    type="button"
                    id="open-add-role-modal"
                    class="eg-access-button eg-access-button--primary"
                >
                    Add Role
                </button>@endcan
            </div>

            <div class="eg-access-table-wrap">
                <table class="eg-access-table">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Name</th>
                            <th class="px-3 py-2.5 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="roles-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading roles...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="eg-access-footer">
                <p id="table-summary" class="text-sm text-slate-600">Preparing role list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="role-modal" class="eg-access-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-access-modal-panel w-full max-w-md rounded-xl bg-white p-4 shadow-2xl">
        <form id="role-form" class="eg-access-form flex flex-col items-center gap-2">
            <input type="hidden" id="role-id">

            <div class="w-full flex items-center justify-between">
                <h4 id="role-modal-title" class="text-lg font-bold text-gray-900">Add Role</h4>
                <button type="button" data-close-modal="role-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div class="w-full gap-1 flex flex-col">
                <label for="role-name">Role Name</label>
                <input id="role-name" name="name" type="text" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full flex justify-center gap-2 pt-2">
                <button type="button" data-close-modal="role-modal" class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                    Cancel
                </button>

                <button type="submit" id="role-submit-button" class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600 hover:scale-105">
                    Save Role
                </button>
            </div>
        </form>
    </div>
</div>

<div id="permissions-modal" class="eg-access-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-4">
    <div class="eg-access-modal-panel w-full max-w-4xl max-h-[calc(100vh-2rem)] rounded-xl bg-white p-4 shadow-2xl overflow-hidden">
        <form id="permissions-form" class="eg-access-form flex max-h-[calc(100vh-4rem)] min-h-0 flex-col gap-3">
            <input type="hidden" id="permissions-role-id">

            <div class="w-full flex items-center justify-between">
                <div>
                    <h4 id="permissions-modal-title" class="text-lg font-bold text-gray-900">Modify Permissions</h4>
                    <p class="eg-access-modal-help">Select the actions this role can access. Save changes carefully because this affects account access.</p>
                </div>
                <button type="button" data-close-modal="permissions-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div id="permissions-card-list" class="eg-access-permission-grid grid min-h-0 gap-3 overflow-y-auto pr-1 md:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-3 text-sm text-slate-500">Loading permissions...</div>
            </div>

            <div class="w-full flex shrink-0 justify-center gap-2 border-t border-slate-200 pt-3">
                <button type="button" data-close-modal="permissions-modal" class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                    Cancel
                </button>

                <button type="submit" class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600 hover:scale-105">
                    Save Permissions
                </button>
            </div>
        </form>
    </div>
</div>

<div id="delete-modal" class="eg-access-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-access-modal-panel w-full max-w-sm rounded-xl bg-white p-4 shadow-2xl flex flex-col items-center text-center">
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="space-y-2">
            <h4 class="text-xl font-semibold text-gray-900">Delete Role</h4>
            <p id="delete-modal-text" class="text-sm text-gray-500 leading-relaxed">Are you sure you want to delete this role?</p>
        </div>
        <div class="mt-6 w-full grid grid-cols-2 gap-2 justify-items-center">
            <button type="button" data-close-modal="delete-modal" class="w-full rounded-full bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">
                Cancel
            </button>

            <button type="button" id="confirm-delete-role" class="w-full rounded-full bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-150 hover:bg-red-700 active:scale-[0.98]">
                Delete
            </button>
        </div>
    </div>
</div>

<div id="message-modal" class="eg-access-modal eg-access-message fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div id="message-modal-panel" class="eg-access-modal-panel w-full max-w-sm scale-95 rounded-xl bg-white p-4 text-center opacity-0 shadow-2xl transition duration-200">
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
    const canCreateRoles = @json(auth()->user()?->can('roles.create'));
    const canUpdateRoles = @json(auth()->user()?->can('roles.update'));
    const canDeleteRoles = @json(auth()->user()?->can('roles.delete'));
    const routes = {
        fetch: @json(route('admin.roles.fetch')),
        store: @json(route('admin.roles.store')),
        editBase: @json(url('admin/roles')),
    };

    const searchInput = document.getElementById('search-roles');
    const tableBody = document.getElementById('roles-table-body');
    const tableSummary = document.getElementById('table-summary');
    const pagination = document.getElementById('pagination');
    const roleModal = document.getElementById('role-modal');
    const permissionsModal = document.getElementById('permissions-modal');
    const deleteModal = document.getElementById('delete-modal');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');
    const roleForm = document.getElementById('role-form');
    const permissionsForm = document.getElementById('permissions-form');
    const roleIdInput = document.getElementById('role-id');
    const roleNameInput = document.getElementById('role-name');
    const roleModalTitle = document.getElementById('role-modal-title');
    const roleSubmitButton = document.getElementById('role-submit-button');
    const permissionsRoleIdInput = document.getElementById('permissions-role-id');
    const permissionsModalTitle = document.getElementById('permissions-modal-title');
    const permissionsCardList = document.getElementById('permissions-card-list');
    const deleteModalText = document.getElementById('delete-modal-text');
    const confirmDeleteRoleButton = document.getElementById('confirm-delete-role');
    const openAddRoleModalButton = document.getElementById('open-add-role-modal');

    let currentPage = 1;
    let roleToDelete = null;
    let searchTimer = null;
    let messageHideTimer = null;

    function showMessage(message, tone = 'success') {
        if (messageHideTimer) {
            window.clearTimeout(messageHideTimer);
            messageHideTimer = null;
        }

        const tones = {
            success: { icon: 'bg-emerald-50 text-emerald-500', title: 'Success' },
            error: { icon: 'bg-red-50 text-red-500', title: 'Action Failed' },
            info: { icon: 'bg-blue-50 text-blue-500', title: 'Notice' },
        };

        const activeTone = tones[tone] || tones.info;
        messageModalIcon.className = `mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-full ${activeTone.icon}`;
        messageModalTitle.textContent = activeTone.title;
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

    function renderRows(roles, from) {
        if (!roles.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-slate-500">No roles found.</td>
                </tr>
            `;
            return;
        }

        const buildActionButtons = (role) => {
            const buttons = [];

            if (canUpdateRoles) {
                buttons.push(`
                    <button type="button" data-action="edit" data-id="${role.id}" class="eg-access-action" aria-label="Edit role">
                        <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="edit role">
                        <span>Edit</span>
                    </button>
                `);

                buttons.push(`
                    <button type="button" data-action="permissions" data-id="${role.id}" data-name="${escapeHtml(role.name)}" class="eg-access-action" aria-label="Manage permissions">
                        <img src="{{ asset('icons/crown.png') }}" class="w-7 h-7" alt="modify permissions">
                        <span>Permissions</span>
                    </button>
                `);
            }

            if (canDeleteRoles) {
                buttons.push(`
                    <button type="button" data-action="delete" data-id="${role.id}" data-name="${escapeHtml(role.name)}" class="eg-access-action eg-access-action--danger" aria-label="Delete role">
                        <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete role">
                        <span>Delete</span>
                    </button>
                `);
            }

            if (buttons.length === 0) {
                return '<span class="text-sm text-slate-400">No actions</span>';
            }

            return buttons.join('');
        };

        tableBody.innerHTML = roles.map((role, index) => `
            <tr class="eg-access-row">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${role.id}</td>
                <td class="px-3 py-2.5">${escapeHtml(role.name)}</td>
                <td class="px-3 py-2.5">
                    <div class="eg-access-action-group">
                        ${buildActionButtons(role)}
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

    async function fetchRoles(page = 1) {
        currentPage = page;
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="px-3 py-5 text-center text-slate-500">Loading roles...</td>
            </tr>
        `;

        const url = new URL(routes.fetch, window.location.origin);
        url.searchParams.set('page', String(page));
        if (searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        try {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            const payload = await response.json();

            renderRows(payload.data || [], payload.from || 1);
            renderPagination(payload);
            tableSummary.textContent = payload.total
                ? `Showing ${payload.from} to ${payload.to} of ${payload.total} roles`
                : 'No roles available';
        } catch (error) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-5 text-center text-rose-600">Unable to load roles right now.</td>
                </tr>
            `;
            tableSummary.textContent = 'Role list unavailable';
            showMessage('Unable to load roles right now.', 'error');
        }
    }

    function resetRoleForm(isEdit = false) {
        roleForm.reset();
        roleIdInput.value = '';
        roleModalTitle.textContent = isEdit ? 'Edit Role' : 'Add Role';
        roleSubmitButton.textContent = isEdit ? 'Update Role' : 'Save Role';
    }

    function renderPermissionCards(parents) {
        if (!parents.length) {
            permissionsCardList.innerHTML = '<div class="rounded-xl border border-slate-200 p-3 text-sm text-slate-500">No permissions available.</div>';
            return;
        }

        permissionsCardList.innerHTML = parents.map((parent) => `
            <div class="eg-access-permission-card rounded-xl">
                <label class="eg-access-permission-parent flex items-center gap-3 text-base font-semibold text-slate-800">
                    <input type="checkbox" class="permission-parent h-4 w-4 accent-blue-600" value="${parent.id}" ${parent.checked ? 'checked' : ''}>
                    <span>${escapeHtml(parent.name)}</span>
                </label>
                <div class="eg-access-permission-children mt-3 flex flex-col gap-2 pl-2">
                    ${(parent.children || []).length
                        ? parent.children.map((child) => `
                            <label class="eg-access-permission-child-row flex items-center gap-3 text-sm text-slate-700">
                                <input type="checkbox" class="permission-child h-4 w-4 accent-blue-600" value="${child.id}" data-parent-id="${parent.id}" ${child.checked ? 'checked' : ''}>
                                <span>${escapeHtml(child.name)}</span>
                            </label>
                        `).join('')
                        : '<p class="text-sm text-slate-400">No child permissions.</p>'}
                </div>
            </div>
        `).join('');
    }

    function openAddRoleModal() {
        hideMessage();
        resetRoleForm(false);
        openModal(roleModal);
    }

    async function openEditRoleModal(roleId) {
        hideMessage();
        resetRoleForm(true);

        const response = await fetch(`${routes.editBase}/${roleId}/edit`, {
            headers: { 'Accept': 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            showMessage(payload.message || 'Unable to load role details.', 'error');
            return;
        }

        roleIdInput.value = payload.role.id;
        roleNameInput.value = payload.role.name;
        openModal(roleModal);
    }

    async function openPermissionsModal(roleId, roleName) {
        hideMessage();
        permissionsRoleIdInput.value = roleId;
        permissionsModalTitle.textContent = `Modify Permissions: ${roleName}`;
        permissionsCardList.innerHTML = '<div class="rounded-xl border border-slate-200 p-3 text-sm text-slate-500">Loading permissions...</div>';
        openModal(permissionsModal);

        const response = await fetch(`${routes.editBase}/${roleId}/permissions`, {
            headers: { 'Accept': 'application/json' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            closeModal(permissionsModal);
            showMessage(payload.message || 'Unable to load permissions.', 'error');
            return;
        }

        renderPermissionCards(payload.permissions || []);
    }

    function openDeleteModal(roleId, roleName) {
        hideMessage();
        roleToDelete = roleId;
        deleteModalText.textContent = `Are you sure you want to delete ${roleName}?`;
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

    if (openAddRoleModalButton) {
        openAddRoleModalButton.addEventListener('click', () => {
            openAddRoleModal();
        });
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => fetchRoles(1), 350);
    });

    pagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchRoles(Number(button.dataset.page));
    });

    tableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) {
            return;
        }

        const { action, id, name } = button.dataset;

        try {
            if (action === 'edit') {
                await openEditRoleModal(id);
            }

            if (action === 'permissions') {
                await openPermissionsModal(id, name);
            }

            if (action === 'delete') {
                openDeleteModal(id, name);
            }
        } catch (error) {
            showMessage(error.message || 'Action failed.', 'error');
        }
    });

    roleForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const roleId = roleIdInput.value;
        const formData = new FormData();
        formData.set('name', roleNameInput.value);

        if (roleId) {
            formData.set('_method', 'PUT');
        }

        try {
            const payload = await sendRequest(
                roleId ? `${routes.editBase}/${roleId}` : routes.store,
                'POST',
                formData
            );

            closeModal(roleModal);
            showMessage(payload.message || 'Role saved successfully.', 'success');
            fetchRoles(currentPage);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    permissionsForm.addEventListener('change', (event) => {
        const parentCheckbox = event.target.closest('.permission-parent');
        if (parentCheckbox) {
            const card = parentCheckbox.closest('.rounded-xl');
            card?.querySelectorAll('.permission-child').forEach((checkbox) => {
                checkbox.checked = parentCheckbox.checked;
            });
            return;
        }

        const childCheckbox = event.target.closest('.permission-child');
        if (!childCheckbox) {
            return;
        }

        const card = childCheckbox.closest('.rounded-xl');
        const parent = card?.querySelector('.permission-parent');
        const children = [...(card?.querySelectorAll('.permission-child') || [])];
        if (parent) {
            parent.checked = children.some((checkbox) => checkbox.checked);
        }
    });

    permissionsForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const roleId = permissionsRoleIdInput.value;
        const formData = new FormData();
        formData.set('_method', 'PUT');

        permissionsCardList.querySelectorAll('input[type="checkbox"]:checked').forEach((checkbox) => {
            formData.append('permission_ids[]', checkbox.value);
        });

        try {
            const payload = await sendRequest(
                `${routes.editBase}/${roleId}/permissions`,
                'POST',
                formData
            );

            closeModal(permissionsModal);
            showMessage(payload.message || 'Role permissions updated successfully.', 'success');
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    confirmDeleteRoleButton.addEventListener('click', async () => {
        hideMessage();

        const formData = new FormData();
        formData.set('_method', 'DELETE');

        try {
            const payload = await sendRequest(
                `${routes.editBase}/${roleToDelete}`,
                'POST',
                formData
            );

            closeModal(deleteModal);
            showMessage(payload.message || 'Role deleted successfully.', 'success');
            fetchRoles(1);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(document.getElementById(button.dataset.closeModal));
        });
    });

    document.getElementById('close-message-modal').addEventListener('click', hideMessage);

    [roleModal, permissionsModal, deleteModal].forEach((modal) => {
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

    fetchRoles();
</script>

@endsection
