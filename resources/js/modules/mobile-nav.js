/**
 * Mobile Navigation Active State Handler
 * Manages active state styling for mobile sidebar navigation links
 */

document.addEventListener('DOMContentLoaded', function () {
    updateMobileNavActive();
    window.addEventListener('hashchange', updateMobileNavActive);

    // Update when clicking nav link
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        link.addEventListener('click', function () {
            setTimeout(() => {
                // Safely call toggleMobileSidebar if it exists
                if (typeof window.toggleMobileSidebar === 'function') {
                    window.toggleMobileSidebar();
                }
                updateMobileNavActive();
            }, 100);
        });
    });
});

/**
 * Update mobile nav active state based on current URL and hash
 */
function updateMobileNavActive() {
    const path = window.location.pathname;
    const hash = window.location.hash;
    const links = document.querySelectorAll('.mobile-nav-link');

    links.forEach(link => {
        const navItem = link.querySelector('.nav-item');
        const icon = link.querySelector('i');
        const span = link.querySelector('span');
        const navKey = link.dataset.nav;

        if (!navItem || !icon || !span) return;

        let isActive = false;

        // Check active state
        if (navKey === 'home' && path === '/' && !hash) {
            isActive = true;
        } else if (navKey === 'layanan' && (hash === '#layanan' || path.startsWith('/layanan/'))) {
            isActive = true;
        } else if (navKey === 'testimoni' && path.startsWith('/testimoni')) {
            isActive = true;
        } else if (navKey === 'track' && path === '/track') {
            isActive = true;
        } else if (navKey === 'order' && path === '/order') {
            isActive = true;
        }

        // Update classes
        if (isActive) {
            navItem.classList.remove('nav-item-inactive', 'group');
            navItem.classList.add('nav-item-active');
            icon.classList.remove('text-gray-600', 'group-hover:text-primary');
            span.classList.remove('group-hover:text-primary');
        } else {
            navItem.classList.remove('nav-item-active');
            navItem.classList.add('nav-item-inactive', 'group');
            icon.classList.add('text-gray-600', 'group-hover:text-primary');
            span.classList.add('group-hover:text-primary');
        }
    });
}
