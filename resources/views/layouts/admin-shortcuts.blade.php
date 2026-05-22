<div id="admin-shortcut-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/55 backdrop-blur-sm px-4">
    <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-3 shadow-2xl">
        <div class="mb-4 text-center">
            <h3 class="text-xl font-bold text-stone-900">Quick Open</h3>
            <p class="mt-2 text-sm text-slate-600">Use arrow keys, then press Enter.</p>
        </div>

        <div class="grid grid-cols-2 gap-2">
            @if (canAccessWithParent(auth()->user(), 'time.in'))
            <a
                href="{{ route('in') }}"
                data-admin-shortcut-option
                data-base-class="bg-emerald-600"
                data-hover-class="hover:bg-emerald-700"
                class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-emerald-600 px-5 py-3 text-center text-white shadow-lg outline-none transition-all duration-200 hover:bg-emerald-700 focus:border-emerald-200 focus:ring-4 focus:ring-emerald-200/70"
            >
                <span class="text-base font-bold uppercase tracking-wide">In</span>
            </a>
            @endif

            @if (canAccessWithParent(auth()->user(), 'time.out'))
            <a
                href="{{ route('out') }}"
                data-admin-shortcut-option
                data-base-class="bg-rose-600"
                data-hover-class="hover:bg-rose-700"
                class="flex min-h-[3rem] text-decoration-none flex-col items-center justify-center rounded-2xl border-2 border-transparent bg-rose-600 px-5 py-3 text-center text-white shadow-lg outline-none transition-all duration-200 hover:bg-rose-700 focus:border-rose-200 focus:ring-4 focus:ring-rose-200/70"
            >
                <span class="text-base font-bold uppercase tracking-wide">Out</span>
            </a>
            @endif

        </div>
    </div>
</div>

<script>
    (() => {
        const shortcutModal = document.getElementById('admin-shortcut-modal');
        const shortcutOptions = shortcutModal ? Array.from(shortcutModal.querySelectorAll('[data-admin-shortcut-option]')) : [];
        let activeShortcutIndex = 0;

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
        }

        function focusShortcutOption() {
            if (!shortcutOptions.length) {
                return;
            }

            const safeIndex = ((activeShortcutIndex % shortcutOptions.length) + shortcutOptions.length) % shortcutOptions.length;
            activeShortcutIndex = safeIndex;

            shortcutOptions.forEach((option, index) => {
                option.classList.remove('scale-105', '-translate-y-1', 'border-amber-300', 'ring-4', 'ring-amber-300/70', 'ring-offset-2', 'ring-offset-slate-900', 'shadow-2xl', 'brightness-110', 'bg-gray-700');
                option.classList.add(option.dataset.baseClass);
                option.classList.add(option.dataset.hoverClass);

                if (index === activeShortcutIndex) {
                    option.classList.remove(option.dataset.baseClass);
                    option.classList.remove(option.dataset.hoverClass);
                    option.classList.add('scale-105', '-translate-y-1', 'border-amber-300', 'ring-4', 'ring-amber-300/70', 'ring-offset-2', 'ring-offset-slate-900', 'shadow-2xl', 'brightness-110', 'bg-gray-700');
                }
            });

            shortcutOptions[activeShortcutIndex].focus();
        }

        function moveShortcutSelection(step) {
            activeShortcutIndex += step;
            focusShortcutOption();
        }

        function activateShortcutSelection() {
            if (!shortcutOptions.length) {
                return;
            }

            shortcutOptions[activeShortcutIndex].click();
        }

        window.addEventListener('keydown', (event) => {
            if (event.defaultPrevented) {
                return;
            }

            if (event.ctrlKey && event.key === 'Enter') {
                event.preventDefault();
                showShortcutModal();
                return;
            }

            if (!shortcutModal || shortcutModal.classList.contains('hidden')) {
                return;
            }

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
        });

        shortcutModal?.addEventListener('click', (event) => {
            if (event.target === shortcutModal) {
                hideShortcutModal();
            }
        });
    })();
</script>
