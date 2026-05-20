@extends('layouts.clean')

@section('title', 'Login')

@section('clean')
    <main class="w-full h-full
    flex flex-col justify-center items-center">
        <form id="signinForm" action="{{ route('signin.submit') }}" method="POST" class="w-[20rem] rounded-lg shadow-lg py-3 px-4
        flex flex-col items-center gap-3
        border-2 border-black/40">
            @csrf
            <h4 class="text-lg font-bold">Sign In</h4>

            <div class="w-full gap-1
            flex flex-col">
                <label for="login">Email Or Username</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}"
                class="w-full rounded-full border-1 border-black/70 outline-none py-1 px-2"
                autocomplete="username" required>
            </div>

            <div class="w-full gap-1
            flex flex-col">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                class="w-full rounded-full border-1 border-black/70 outline-none py-1 px-2"
                autocomplete="current-password" required>
            </div>

            <button type="submit" class="w-full rounded-full bg-blue-500 text-white py-1 px-2
            hover:bg-blue-600 hover:scale-105 transition-colors duration-200 font-semibold"
            style="border-radius: 1.5rem;">Sign In</button>

        </form>
    </main>



<div id="errorModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex justify-center items-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl flex flex-col items-center text-center transform transition-all">

        <!-- Error Icon Container -->
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <!-- Content -->
        <div class="space-y-2">
            <h2 class="text-xl font-semibold text-gray-900">
                Login Failed
            </h2>
            <p id="errorMessage" class="text-sm text-gray-500 leading-relaxed">
                The username or password you entered is incorrect. Please try again.
            </p>
        </div>

        <!-- Action Button -->
        <button id="closeModal" class="mt-6 w-full rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800 active:scale-[0.98] transition-all duration-150 shadow-sm"
        style="border-radius: 1.5rem;">
            Try Again
        </button>

    </div>
</div>

<div id="shortcut-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/55 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm rounded-3xl border border-slate-200 bg-white p-4 shadow-2xl">
        <div class="mb-4 text-center">
            <h3 class="text-xl font-bold text-stone-900">Quick Open</h3>
            <p class="mt-2 text-sm text-slate-600">Use arrow keys, then press Enter.</p>
        </div>

        <div class="grid grid-cols-3 gap-2">
                    <a
                        href="{{ route('in') }}"
                        data-shortcut-option
                        class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-emerald-600 px-5 py-3 text-center text-white shadow-lg outline-none transition-all duration-200 hover:bg-emerald-700 focus:border-emerald-200 focus:ring-4 focus:ring-emerald-200/70"
                    >
                        <span class="text-base font-bold uppercase tracking-wide">In</span>
                    </a>

                    <a
                        href="{{ route('out') }}"
                        data-shortcut-option
                        class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-rose-600 px-5 py-3 text-center text-white shadow-lg outline-none transition-all duration-200 hover:bg-rose-700 focus:border-rose-200 focus:ring-4 focus:ring-rose-200/70"
                    >
                        <span class="text-base font-bold uppercase tracking-wide">Out</span>
                    </a>

                    <a
                        href="{{ route('signin') }}"
                        data-shortcut-option
                        class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-sky-600 px-5 py-3 text-center text-white shadow-xl outline-none transition-all duration-200 hover:bg-sky-700 focus:border-sky-200 focus:ring-4 focus:ring-sky-200/70">
                        <span class="text-base font-bold tracking-wide">ADMIN</span>
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
    const shortcutBaseColors = ['bg-emerald-600', 'bg-rose-600', 'bg-sky-600'];
    const shortcutHoverColors = ['hover:bg-emerald-700', 'hover:bg-rose-700', 'hover:bg-sky-700'];

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
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

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
