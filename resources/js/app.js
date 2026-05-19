import './bootstrap';

// Avoid top-of-page jump when navigating pagination links
document.addEventListener('DOMContentLoaded', () => {
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    const hash = window.location.hash;
    if (hash) {
        const target = document.querySelector(hash);
        if (target) {
            target.scrollIntoView({ behavior: 'auto', block: 'start' });
        }
    }

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href*="page="]');
        if (!link) {
            return;
        }

        const container = link.closest('[id]');
        if (container && !link.hash) {
            const url = new URL(link.href, window.location.origin);
            url.hash = container.id;
            link.href = url.toString();
        }
    });
});
