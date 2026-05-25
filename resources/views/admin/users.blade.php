@extends('layouts.app')
@section('title', 'User Management')
@section('content')

<main class="eg-access-page">
    <section class="eg-access-hero">
        <div>
            <p class="eg-access-kicker">Access Control</p>
            <h1 class="eg-access-title">User Management</h1>
            <p class="eg-access-subtitle">Create admin accounts, assign roles, and manage account access.</p>
        </div>
    </section>

    <section class="eg-access-guide-grid" aria-label="User management guide">
        <article class="eg-access-guide-card">Assign each user one role.</article>
        <article class="eg-access-guide-card">Use password reset for account recovery.</article>
        <article class="eg-access-guide-card">Do not delete the last admin account.</article>
    </section>

    <section class="eg-access-card">
        <div class="eg-access-card-inner">
            <div class="eg-access-toolbar">
                <div class="eg-access-search">
                    <label for="search-users" class="sr-only">Search users</label>
                    <input
                        id="search-users"
                        type="text"
                        placeholder="Search username or email"
                        class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                @can('users.create')<button
                    type="button"
                    id="open-add-user-modal"
                    class="eg-access-button eg-access-button--primary"
                >
                    Add User
                </button>@endcan
            </div>

            <div class="eg-access-table-wrap">
                <table class="eg-access-table">
                    <thead class="sticky top-0 z-10 bg-blue-600 text-black">
                        <tr>
                            <th class="px-3 py-2.5">No.</th>
                            <th class="px-3 py-2.5">ID</th>
                            <th class="px-3 py-2.5">Username</th>
                            <th class="px-3 py-2.5">Email</th>
                            <th class="px-3 py-2.5">Role</th>
                            <th class="px-3 py-2.5 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="users-table-body" class="divide-y divide-black">
                        <tr>
                            <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading users...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="eg-access-footer">
                <p id="table-summary" class="text-sm text-slate-600">Preparing user list...</p>
                <div id="pagination" class="flex flex-wrap items-center justify-end gap-1.5"></div>
            </div>
        </div>
    </section>
</main>

<div id="user-modal" class="eg-access-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-access-modal-panel w-full max-w-2xl rounded-xl bg-white p-4 shadow-2xl">
        <form id="user-form" class="eg-access-form flex flex-col items-center gap-2">
            <input type="hidden" id="user-id">
            <div class="w-full flex items-center justify-between">
                <h4 id="user-modal-title" class="text-lg font-bold text-gray-900">Add User</h4>
                <button type="button" data-close-modal="user-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div class="eg-access-form-grid">
            <div class="w-full gap-1 flex flex-col">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full gap-1 flex flex-col">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full gap-1 flex flex-col" id="password-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full gap-1 flex flex-col" id="password-confirmation-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full gap-1 flex flex-col eg-access-form-span">
                <label for="role">Role</label>
                <select id="role" name="role" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none bg-white">
                    <option value="">Select role</option>
                </select>
            </div>
            </div>

            <div class="w-full flex justify-center gap-2 pt-2">

                <button type="button" data-close-modal="user-modal"
                class="rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white
                transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">

                    Cancel
                </button>

                <button type="submit" id="user-submit-button"
                class="rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white
                transition-colors duration-200 hover:bg-blue-600 hover:scale-105">

                    Save User
                </button>

            </div>
        </form>
    </div>
</div>

<div id="password-modal" class="eg-access-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="eg-access-modal-panel w-full max-w-md rounded-xl bg-white p-4 shadow-2xl">
        <form id="password-form" class="eg-access-form flex flex-col items-center gap-2">
            <input type="hidden" id="password-user-id">
            <div class="w-full flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold text-gray-900">Change Password</h4>
                    <p class="eg-access-modal-help">Set a new password for this user. The user can sign in using the updated password after saving.</p>
                </div>
                <button type="button" data-close-modal="password-modal" class="rounded-full px-2 py-1 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">X</button>
            </div>

            <div class="w-full gap-1 flex flex-col">
                <label for="new-password">New Password</label>
                <input id="new-password" name="password" type="password" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full gap-1 flex flex-col">
                <label for="new-password-confirmation">Confirm Password</label>
                <input id="new-password-confirmation" name="password_confirmation" type="password" class="w-full rounded-full border-1 border-black/70 px-3 py-2 outline-none">
            </div>

            <div class="w-full grid grid-cols-2 gap-2 pt-2 place-items-center">

                <button type="button" data-close-modal="password-modal"
                class="w-full rounded-full bg-gray-900 px-4 py-1.5 text-sm font-medium text-white
                transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">

                    Cancel
                </button>

                <button type="submit"
                class="w-full rounded-full bg-blue-500 px-4 py-1.5 text-sm font-semibold text-white
                transition-colors duration-200 hover:bg-blue-600 hover:scale-105">

                    Update Password
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
            <h4 class="text-xl font-semibold text-gray-900">Delete User</h4>
            <p id="delete-modal-text" class="text-sm text-gray-500 leading-relaxed">This will remove the user account. This action cannot be undone.</p>
        </div>
        <div class="mt-6 w-full grid grid-cols-2 gap-2 justify-items-center">

            <button type="button" data-close-modal="delete-modal"
            class="w-full rounded-full bg-gray-900 px-4 py-2.5 text-sm font-medium text-white
            transition-all duration-150 hover:bg-gray-800 active:scale-[0.98]">

                Cancel
            </button>

            <button type="button" id="confirm-delete-user"
            class="w-full rounded-full bg-red-600 px-4 py-2.5 text-sm font-medium text-white
            transition-all duration-150 hover:bg-red-700 active:scale-[0.98]">

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
    const canCreateUsers = @json(auth()->user()?->can('users.create'));
    const canUpdateUsers = @json(auth()->user()?->can('users.update'));
    const canUpdateUserPasswords = @json(auth()->user()?->can('users.update.pass'));
    const canDeleteUsers = @json(auth()->user()?->can('users.delete'));
    const routes = {
        fetch: @json(route('admin.users.fetch')),
        roles: @json(route('admin.users.roles')),
        store: @json(route('admin.users.store')),
        editBase: @json(url('admin/users')),
    };

    const searchInput = document.getElementById('search-users');
    const tableBody = document.getElementById('users-table-body');
    const tableSummary = document.getElementById('table-summary');
    const pagination = document.getElementById('pagination');
    const userModal = document.getElementById('user-modal');
    const passwordModal = document.getElementById('password-modal');
    const deleteModal = document.getElementById('delete-modal');
    const messageModal = document.getElementById('message-modal');
    const messageModalPanel = document.getElementById('message-modal-panel');
    const messageModalIcon = document.getElementById('message-modal-icon');
    const messageModalTitle = document.getElementById('message-modal-title');
    const messageModalText = document.getElementById('message-modal-text');
    const userForm = document.getElementById('user-form');
    const passwordForm = document.getElementById('password-form');
    const userIdInput = document.getElementById('user-id');
    const passwordUserIdInput = document.getElementById('password-user-id');
    const userModalTitle = document.getElementById('user-modal-title');
    const userSubmitButton = document.getElementById('user-submit-button');
    const roleSelect = document.getElementById('role');
    const passwordGroup = document.getElementById('password-group');
    const passwordConfirmationGroup = document.getElementById('password-confirmation-group');
    const confirmDeleteButton = document.getElementById('confirm-delete-user');
    const deleteModalText = document.getElementById('delete-modal-text');
    const openAddUserModalButton = document.getElementById('open-add-user-modal');

    let rolesLoaded = false;
    let searchTimer = null;
    let currentPage = 1;
    let userToDelete = null;
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

        messageModalPanel.className = 'eg-access-modal-panel w-full max-w-sm scale-95 rounded-xl bg-white p-4 text-center opacity-0 shadow-2xl transition duration-200';
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

    function roleNames(user) {
        if (!Array.isArray(user.roles) || user.roles.length === 0) {
            return 'No role';
        }

        return user.roles.map((role) => role.name).join(', ');
    }

    function renderRows(users, from) {
        if (!users.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-5 text-center text-slate-500">No users found.</td>
                </tr>
            `;
            return;
        }

        const buildActionButtons = (user) => {
            const buttons = [];

            if (canUpdateUsers) {
                buttons.push(`
                    <button type="button" class="eg-access-action" data-action="edit" data-id="${user.id}" aria-label="Edit user">
                        <img src="{{ asset('icons/list.png') }}" class="w-7 h-7" alt="edit user">
                        <span>Edit</span>
                    </button>
                `);
            }

            if (canUpdateUserPasswords) {
                buttons.push(`
                    <button type="button" class="eg-access-action" data-action="password" data-id="${user.id}" data-username="${escapeHtml(user.username)}" aria-label="Change password">
                        <img src="{{ asset('icons/key.png') }}" class="w-7 h-7" alt="change password">
                        <span>Password</span>
                    </button>
                `);
            }

            if (canDeleteUsers) {
                buttons.push(`
                    <button type="button" class="eg-access-action eg-access-action--danger" data-action="delete" data-id="${user.id}" data-username="${escapeHtml(user.username)}" aria-label="Delete user">
                        <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7" alt="delete user">
                        <span>Delete</span>
                    </button>
                `);
            }

            if (buttons.length === 0) {
                return '<span class="text-sm text-slate-400">No actions</span>';
            }

            return buttons.join('');
        };

        tableBody.innerHTML = users.map((user, index) => `
            <tr class="eg-access-row">
                <td class="px-3 py-2.5">${from + index}</td>
                <td class="px-3 py-2.5">${user.id}</td>
                <td class="px-3 py-2.5">${escapeHtml(user.username)}</td>
                <td class="px-3 py-2.5">${escapeHtml(user.email)}</td>
                <td class="px-3 py-2.5"><span class="eg-access-badge">${escapeHtml(roleNames(user))}</span></td>
                <td class="px-3 py-2.5">
                    <div class="eg-access-action-group">
                        ${buildActionButtons(user)}
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

    async function fetchRoles() {
        if (rolesLoaded) {
            return;
        }

        const response = await fetch(routes.roles, {
            headers: {
                'Accept': 'application/json',
            },
        });

        const payload = await response.json();

        if (!response.ok || !payload.success) {
            throw new Error(payload.message || 'Unable to load roles.');
        }

        roleSelect.innerHTML = '<option value="">Select role</option>' + payload.roles.map((role) => `
            <option value="${escapeHtml(role.name)}">${escapeHtml(role.name)}</option>
        `).join('');

        rolesLoaded = true;
    }

    async function fetchUsers(page = 1) {
        currentPage = page;
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-3 py-5 text-center text-slate-500">Loading users...</td>
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
                ? `Showing ${payload.from} to ${payload.to} of ${payload.total} users`
                : 'No users available';
        } catch (error) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-3 py-5 text-center text-rose-600">Unable to load users right now.</td>
                </tr>
            `;
            tableSummary.textContent = 'User list unavailable';
            showMessage('Unable to load users right now.', 'error');
        }
    }

    function resetUserForm(isEdit = false) {
        userForm.reset();
        userIdInput.value = '';
        userModalTitle.textContent = isEdit ? 'Edit User' : 'Add User';
        userSubmitButton.textContent = isEdit ? 'Update User' : 'Save User';
        passwordGroup.classList.toggle('hidden', isEdit);
        passwordConfirmationGroup.classList.toggle('hidden', isEdit);
        document.getElementById('password').required = !isEdit;
        document.getElementById('password_confirmation').required = !isEdit;
    }

    async function openAddUserModal() {
        hideMessage();
        resetUserForm(false);
        await fetchRoles();
        openModal(userModal);
    }

    async function openEditUserModal(userId) {
        hideMessage();
        resetUserForm(true);
        await fetchRoles();

        const response = await fetch(`${routes.editBase}/${userId}/edit`, {
            headers: {
                'Accept': 'application/json',
            },
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            showMessage(payload.message || 'Unable to load user details.', 'error');
            return;
        }

        userIdInput.value = payload.user.id;
        document.getElementById('username').value = payload.user.username;
        document.getElementById('email').value = payload.user.email;
        roleSelect.value = payload.user.roles?.[0]?.name || '';

        openModal(userModal);
    }

    function openPasswordModal(userId, username) {
        hideMessage();
        passwordForm.reset();
        passwordUserIdInput.value = userId;
        document.querySelector('#password-modal h4').textContent = `Change Password: ${username}`;
        openModal(passwordModal);
    }

    function openDeleteModal(userId, username) {
        hideMessage();
        userToDelete = userId;
        deleteModalText.textContent = `This will remove the user account for ${username}. This action cannot be undone.`;
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

    if (openAddUserModalButton) {
        openAddUserModalButton.addEventListener('click', async () => {
            try {
                await openAddUserModal();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        });
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => {
            fetchUsers(1);
        }, 350);
    });

    pagination.addEventListener('click', (event) => {
        const button = event.target.closest('[data-page]');
        if (!button || button.disabled) {
            return;
        }

        fetchUsers(Number(button.dataset.page));
    });

    tableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) {
            return;
        }

        const { action, id, username } = button.dataset;

        try {
            if (action === 'edit') {
                await openEditUserModal(id);
            }

            if (action === 'password') {
                openPasswordModal(id, username);
            }

            if (action === 'delete') {
                openDeleteModal(id, username);
            }
        } catch (error) {
            showMessage(error.message || 'Action failed.', 'error');
        }
    });

    userForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const userId = userIdInput.value;
        const formData = new FormData();
        formData.set('username', document.getElementById('username').value);
        formData.set('email', document.getElementById('email').value);
        formData.set('role', roleSelect.value);

        if (!userId) {
            formData.set('password', document.getElementById('password').value);
            formData.set('password_confirmation', document.getElementById('password_confirmation').value);
        } else {
            formData.set('_method', 'PUT');
        }

        try {
            const payload = await sendRequest(
                userId ? `${routes.editBase}/${userId}` : routes.store,
                'POST',
                formData
            );

            closeModal(userModal);
            showMessage(payload.message || 'User saved successfully.', 'success');
            fetchUsers(currentPage);
        } catch (error) {
            showMessage(error.message, 'error');
        }
    });

    passwordForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideMessage();

        const formData = new FormData();
        formData.set('_method', 'PUT');
        formData.set('password', document.getElementById('new-password').value);
        formData.set('password_confirmation', document.getElementById('new-password-confirmation').value);

        try {
            const payload = await sendRequest(
                `${routes.editBase}/${passwordUserIdInput.value}/password`,
                'POST',
                formData
            );

            closeModal(passwordModal);
            showMessage(payload.message || 'Password updated successfully.', 'success');
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
                `${routes.editBase}/${userToDelete}`,
                'POST',
                formData
            );

            closeModal(deleteModal);
            showMessage(payload.message || 'User deleted successfully.', 'success');
            fetchUsers(1);
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

    [userModal, passwordModal, deleteModal].forEach((modal) => {
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

    fetchUsers();
</script>

@endsection
