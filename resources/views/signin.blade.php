@extends('layouts.clean')

@section('title', 'Login')

@section('clean')
    <main class="eg-auth-page">
        <div class="eg-auth-shell">
            <section class="eg-auth-intro" aria-label="EGate branding">
                <div class="eg-auth-brand">
                    <img src="{{ asset('images/olpcc-logo.png') }}" alt="OLPCC logo" class="eg-auth-logo">
                    <div>
                        <p class="eg-auth-kicker">OLPCC / OSMIS-eGATE</p>
                        <p class="eg-auth-brand-title">EGate</p>
                    </div>
                </div>

                <div class="eg-auth-copy">
                    <h1>Secure campus access, clearly monitored.</h1>
                    <p>Use your administrator account to manage gate monitoring, records, and access control.</p>
                </div>
            </section>

            <form id="signinForm" action="{{ route('signin.submit') }}" method="POST" class="eg-auth-card">
                @csrf
                <div class="eg-auth-card-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to continue to the EGate monitoring system.</p>
                </div>

                <div class="eg-auth-field">
                    <label for="login">Email or username</label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}"
                        class="eg-auth-input" autocomplete="username" required>
                </div>

                <div class="eg-auth-field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"
                        class="eg-auth-input" autocomplete="current-password" required>
                </div>

                <button type="submit" class="eg-auth-button">Sign In</button>
                <p class="eg-auth-footer">EGate Access Control System</p>
            </form>
        </div>
    </main>

    <div id="errorModal" class="hidden fixed inset-0 z-50 bg-black/55 backdrop-blur-sm flex justify-center items-center p-4">
        <div class="eg-auth-modal-panel">
            <div class="eg-auth-modal-icon" aria-hidden="true">!</div>
            <h2 class="text-xl font-bold text-slate-950">Login Failed</h2>
            <p id="errorMessage" class="mt-2 text-sm leading-relaxed text-slate-600">
                The username or password you entered is incorrect. Please try again.
            </p>
            <button id="closeModal" class="eg-auth-button mt-6" type="button">Try Again</button>
        </div>
    </div>

    <div id="shortcut-modal" class="eg-kiosk-modal fixed inset-0 z-[60] hidden items-center justify-center bg-black/55 px-4">
        <div class="eg-auth-modal-panel">
            <h3 class="text-xl font-bold text-slate-950">Quick Open</h3>
            <p class="mt-2 mb-4 text-sm text-slate-600">Use arrow keys, then press Enter.</p>

            <div class="eg-auth-shortcuts">
                <a
                    href="{{ route('in') }}"
                    data-shortcut-option
                    class="eg-auth-shortcut bg-emerald-600 hover:bg-emerald-700 outline-none transition-all duration-200 focus:border-emerald-200 focus:ring-4 focus:ring-emerald-200/70"
                >
                    Time In
                </a>

                <a
                    href="{{ route('out') }}"
                    data-shortcut-option
                    class="eg-auth-shortcut bg-rose-600 hover:bg-rose-700 outline-none transition-all duration-200 focus:border-rose-200 focus:ring-4 focus:ring-rose-200/70"
                >
                    Time Out
                </a>
            </div>
        </div>
    </div>

    <script>
    const form = document.getElementById('signinForm');
    const modal = document.getElementById('errorModal');
    const message = document.getElementById('errorMessage');
    const closeModal = document.getElementById('closeModal');
    const shortcutModal = document.getElementById('shortcut-modal');
    const shortcutOptions = shortcutModal ? Array.from(shortcutModal.querySelectorAll('[data-shortcut-option]')) : [];
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    let activeShortcutIndex = 0;
    const shortcutBaseColors = ['bg-emerald-600', 'bg-rose-600'];
    const shortcutHoverColors = ['hover:bg-emerald-700', 'hover:bg-rose-700'];
    const csrfTokenUrl = @json(route('csrf.token'));

    function readCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function writeCsrfToken(token) {
        const meta = document.querySelector('meta[name="csrf-token"]');
        const input = form?.querySelector('input[name="_token"]');

        if (meta && token) {
            meta.content = token;
        }

        if (input && token) {
            input.value = token;
        }
    }

    async function refreshCsrfToken() {
        const response = await fetch(`${csrfTokenUrl}?fresh=1`, {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();
        writeCsrfToken(data.token || '');

        return data.token || '';
    }

    async function submitSignin(formData, allowRetry = true) {
        formData.set('_token', readCsrfToken());

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': readCsrfToken(),
                'Accept': 'application/json'
            },
            body: formData
        });

        const responseToken = response.headers.get('X-CSRF-TOKEN');
        if (responseToken) {
            writeCsrfToken(responseToken);
        }

        if (response.status === 419 && allowRetry) {
            await refreshCsrfToken();
            return submitSignin(formData, false);
        }

        const data = await response.json().catch(() => ({
            message: response.status === 419 ? 'CSRF token mismatch.' : 'Something went wrong',
            status: 0,
        }));
        writeCsrfToken(data.csrf_token || responseToken || readCsrfToken());

        return data;
    }

    function focusLoginInput() {
        loginInput?.focus();
    }

    function showShortcutModal() {
        if (!shortcutModal) {
            return;
        }

        shortcutModal.classList.remove('hidden');
        shortcutModal.classList.add('flex');
        activeShortcutIndex = 0;
        focusShortcutOption();
    }

    function hideShortcutModal() {
        if (!shortcutModal) {
            return;
        }

        shortcutModal.classList.add('hidden');
        shortcutModal.classList.remove('flex');
        focusLoginInput();
    }

    function focusShortcutOption() {
        if (!shortcutOptions.length) {
            return;
        }

        const safeIndex = ((activeShortcutIndex % shortcutOptions.length) + shortcutOptions.length) % shortcutOptions.length;
        activeShortcutIndex = safeIndex;
        shortcutOptions.forEach((option, index) => {
            option.classList.remove('scale-105', '-translate-y-1', 'border-amber-300', 'ring-4', 'ring-amber-300/70', 'ring-offset-2', 'ring-offset-slate-900', 'shadow-2xl', 'brightness-110', 'bg-gray-700');
            option.classList.add(shortcutBaseColors[index]);
            option.classList.add(shortcutHoverColors[index]);

            if (index === activeShortcutIndex) {
                option.classList.remove(shortcutBaseColors[index]);
                option.classList.remove(shortcutHoverColors[index]);
                option.classList.add('scale-105', '-translate-y-1', 'border-amber-300', 'ring-4', 'ring-amber-300/70', 'ring-offset-2', 'ring-offset-slate-900', 'shadow-2xl', 'brightness-110', 'bg-gray-700');
            }
        });
        shortcutOptions[activeShortcutIndex].focus();
    }

    function moveShortcutSelection(step) {
        if (!shortcutOptions.length) {
            return;
        }

        activeShortcutIndex += step;
        focusShortcutOption();
    }

    function activateShortcutSelection() {
        if (!shortcutOptions.length) {
            return;
        }

        shortcutOptions[activeShortcutIndex].click();
    }

    if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const data = await submitSignin(formData);

            if (data.status === 1) {
                window.location.href = data.redirect || "{{ route('admin.dashboard') }}";
                return;
            }

            // show modal if status = 0
            message.textContent = data.message;
            modal.classList.remove('hidden');

        } catch (error) {
            message.textContent = 'Something went wrong';
            modal.classList.remove('hidden');
        }
    });
    }

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    [loginInput, passwordInput].forEach((input) => {
        input?.addEventListener('keydown', (event) => {
            if (event.ctrlKey && event.key === 'Enter') {
                event.preventDefault();
                event.stopPropagation();
                showShortcutModal();
            }
        });
    });

    window.addEventListener('keydown', (event) => {
        if (event.defaultPrevented) {
            return;
        }

        if (event.ctrlKey && event.key === 'Enter') {
            event.preventDefault();
            showShortcutModal();
            return;
        }

        if (shortcutModal && !shortcutModal.classList.contains('hidden')) {
            if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                event.preventDefault();
                moveShortcutSelection(-1);
                return;
            }

            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                event.preventDefault();
                moveShortcutSelection(1);
                return;
            }

            if (event.key === 'Enter') {
                event.preventDefault();
                activateShortcutSelection();
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                hideShortcutModal();
            }
        }
    });

    shortcutModal?.addEventListener('click', (event) => {
        if (event.target === shortcutModal) {
            hideShortcutModal();
        }
    });
    </script>

@endsection
