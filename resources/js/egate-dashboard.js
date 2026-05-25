document.addEventListener('DOMContentLoaded', () => {
    const dashboard = document.querySelector('.eg-dash');

    if (!dashboard) {
        return;
    }

    const dateLabel = dashboard.querySelector('[data-eg-dash-date]');
    const timeLabel = dashboard.querySelector('[data-eg-dash-time]');
    const dateFormat = new Intl.DateTimeFormat('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
    const timeFormat = new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
    });

    const updateClock = () => {
        const now = new Date();

        if (dateLabel) {
            dateLabel.textContent = dateFormat.format(now);
        }

        if (timeLabel) {
            timeLabel.textContent = timeFormat.format(now);
        }
    };

    updateClock();
    window.setInterval(updateClock, 1000);

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const formatter = new Intl.NumberFormat('en-US');

    dashboard.querySelectorAll('[data-eg-count]').forEach((element) => {
        const target = Number(element.dataset.egCount);

        if (!Number.isFinite(target) || target <= 0) {
            return;
        }

        const duration = 650;
        const startedAt = performance.now();

        const draw = (time) => {
            const progress = Math.min(1, (time - startedAt) / duration);
            const eased = 1 - Math.pow(1 - progress, 3);

            element.textContent = formatter.format(Math.round(target * eased));

            if (progress < 1) {
                window.requestAnimationFrame(draw);
            }
        };

        window.requestAnimationFrame(draw);
    });
});
