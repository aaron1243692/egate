<script id="egate-kiosk-script">
(function () {
    'use strict';

    if (window.__egateKioskReady) {
        return;
    }

    window.__egateKioskReady = true;

    const root = document.documentElement;
    const KIOSK_CLASS = 'egate-kiosk';
    const ESC_GRACE_MS = 2500;
    const FULLSCREEN_CHECK_MS = 2000;
    let escGraceUntil = 0;
    let reenterTimer = null;
    let gestureFullscreenBound = false;

    function isShortcutModalOpen() {
        const modal = document.getElementById('shortcut-modal');
        return Boolean(modal && !modal.classList.contains('hidden'));
    }

    function isNativeFullscreen() {
        return Boolean(
            document.fullscreenElement
            || document.webkitFullscreenElement
            || document.mozFullScreenElement
            || document.msFullscreenElement
        );
    }

    function applyKioskLayout() {
        root.classList.add(KIOSK_CLASS);
        document.body.classList.add(KIOSK_CLASS);
    }

    async function requestNativeFullscreen() {
        if (isNativeFullscreen()) {
            return true;
        }

        const targets = [root, document.body].filter(Boolean);

        for (const target of targets) {
            try {
                if (target.requestFullscreen) {
                    await target.requestFullscreen({ navigationUI: 'hide' });
                } else if (target.webkitRequestFullscreen) {
                    target.webkitRequestFullscreen();
                } else if (target.mozRequestFullScreen) {
                    target.mozRequestFullScreen();
                } else if (target.msRequestFullscreen) {
                    target.msRequestFullscreen();
                } else {
                    continue;
                }

                if (isNativeFullscreen()) {
                    return true;
                }
            } catch (error) {
                // Try the next target or wait for a user gesture retry.
            }
        }

        return isNativeFullscreen();
    }

    function exitNativeFullscreen() {
        try {
            if (document.exitFullscreen) {
                return document.exitFullscreen();
            }

            if (document.webkitExitFullscreen) {
                return document.webkitExitFullscreen();
            }

            if (document.mozCancelFullScreen) {
                return document.mozCancelFullScreen();
            }

            if (document.msExitFullscreen) {
                return document.msExitFullscreen();
            }
        } catch (error) {
            return Promise.resolve();
        }

        return Promise.resolve();
    }

    function scheduleReenterFullscreen() {
        if (Date.now() < escGraceUntil) {
            return;
        }

        clearTimeout(reenterTimer);
        reenterTimer = window.setTimeout(enterFullscreenMode, 300);
    }

    function enterFullscreenMode() {
        applyKioskLayout();
        requestNativeFullscreen();
    }

    function ensureFullscreenActive() {
        if (Date.now() < escGraceUntil) {
            return;
        }

        applyKioskLayout();

        if (!isNativeFullscreen()) {
            requestNativeFullscreen();
        }
    }

    function bindGestureFullscreenUnlock() {
        if (gestureFullscreenBound) {
            return;
        }

        gestureFullscreenBound = true;

        const unlock = function () {
            enterFullscreenMode();
        };

        window.addEventListener('pointerdown', unlock, { once: true, capture: true });
        window.addEventListener('keydown', unlock, { once: true, capture: true });
        window.addEventListener('touchstart', unlock, { once: true, capture: true });
    }

    function isEditableTarget(target) {
        if (!target) {
            return false;
        }

        if (target instanceof HTMLInputElement || target instanceof HTMLTextAreaElement) {
            return !target.readOnly && !target.disabled;
        }

        return Boolean(target.isContentEditable);
    }

    function shouldBlockPageShortcut(event) {
        if (event.key === 'Escape') {
            return false;
        }

        if (/^F\d{1,2}$/i.test(event.key)) {
            return true;
        }

        if (event.key === 'ContextMenu') {
            return true;
        }

        if (event.altKey && (event.key === 'ArrowLeft' || event.key === 'ArrowRight' || event.key === 'Home')) {
            return true;
        }

        if ((event.ctrlKey || event.metaKey) && ['r', 'w', 't', 'n', 'p', 'l', 'u', 's', 'o', 'j', 'h', 'd', 'g', 'i', 'k'].includes(event.key.toLowerCase())) {
            return true;
        }

        if ((event.ctrlKey || event.metaKey) && event.shiftKey && ['i', 'j', 'c', 'n', 'r', 't', 'k'].includes(event.key.toLowerCase())) {
            return true;
        }

        if (event.key === 'Backspace' && !isEditableTarget(event.target)) {
            return true;
        }

        return false;
    }

    function handleKioskKeydown(event) {
        if (event.key === 'Escape') {
            if (isShortcutModalOpen()) {
                return;
            }

            if (isNativeFullscreen()) {
                escGraceUntil = Date.now() + ESC_GRACE_MS;
                exitNativeFullscreen();
            }

            return;
        }

        if (shouldBlockPageShortcut(event)) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
    }

    function handleKioskKeyup(event) {
        if (event.key === 'Escape') {
            return;
        }

        if (shouldBlockPageShortcut(event)) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
    }

    window.addEventListener('keydown', handleKioskKeydown, true);
    window.addEventListener('keyup', handleKioskKeyup, true);
    window.addEventListener('contextmenu', (event) => event.preventDefault());
    window.addEventListener('auxclick', (event) => {
        if (event.button === 1) {
            event.preventDefault();
        }
    });

    ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'].forEach((eventName) => {
        document.addEventListener(eventName, () => {
            applyKioskLayout();
            root.dataset.egateFullscreen = isNativeFullscreen() ? '1' : '0';

            if (!isNativeFullscreen()) {
                scheduleReenterFullscreen();
            }
        });
    });

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            scheduleReenterFullscreen();
        }
    });

    window.addEventListener('focus', scheduleReenterFullscreen);
    window.addEventListener('resize', applyKioskLayout);
    window.addEventListener('pageshow', enterFullscreenMode);
    window.addEventListener('load', enterFullscreenMode);

    window.setInterval(ensureFullscreenActive, FULLSCREEN_CHECK_MS);

    applyKioskLayout();
    root.dataset.egateFullscreen = '0';
    enterFullscreenMode();
    bindGestureFullscreenUnlock();
    ensureFullscreenActive();

    window.EgateKiosk = {
        isFullscreen: isNativeFullscreen,
        enter: enterFullscreenMode,
        ensure: ensureFullscreenActive,
    };
})();
</script>
