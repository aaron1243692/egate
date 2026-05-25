document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-eg-ribbon]');

    if (!root) {
        return;
    }

    const tabs = Array.from(root.querySelectorAll('[data-eg-ribbon-tab]'));
    const pages = Array.from(root.querySelectorAll('[data-eg-ribbon-page]'));
    const ribbon = root.querySelector('.eg-rb-ribbon');
    const tabsScroller = root.querySelector('.eg-rb-tabs');
    const userButton = root.querySelector('#egRbUserBtn');
    const userMenu = root.querySelector('#egRbUserMenu');

    const setShellOffset = () => {
        document.documentElement.style.setProperty('--eg-rb-fixed', `${root.offsetHeight}px`);
    };

    const activateTab = (key) => {
        tabs.forEach((tab) => {
            tab.classList.toggle('is-active', tab.dataset.egRibbonTab === key);
        });

        pages.forEach((page) => {
            page.classList.toggle('is-active', page.dataset.egRibbonPage === key);
        });

        root.classList.toggle('is-flat', !pages.some((page) => page.classList.contains('is-active')));
        setShellOffset();
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            activateTab(tab.dataset.egRibbonTab);
        });
    });

    if (userButton && userMenu) {
        const closeUserMenu = () => {
            userMenu.classList.remove('is-open');
            userButton.setAttribute('aria-expanded', 'false');
        };

        userButton.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = userMenu.classList.toggle('is-open');
            userButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', (event) => {
            if (!userMenu.contains(event.target) && !userButton.contains(event.target)) {
                closeUserMenu();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeUserMenu();
                userButton.focus();
            }
        });
    }

    const enableWheelScroll = (element) => {
        if (!element) {
            return;
        }

        element.addEventListener('wheel', (event) => {
            if (element.scrollWidth <= element.clientWidth) {
                return;
            }

            const delta = Math.abs(event.deltaY) > Math.abs(event.deltaX)
                ? event.deltaY
                : event.deltaX;

            if (!delta) {
                return;
            }

            event.preventDefault();
            element.scrollLeft += delta;
        }, { passive: false });
    };

    enableWheelScroll(ribbon);
    enableWheelScroll(tabsScroller);
    window.addEventListener('resize', setShellOffset);
    setShellOffset();
});
