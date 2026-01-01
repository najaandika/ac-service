/**
 * Sidebar functionality
 */

/**
 * Toggle sidebar visibility (mobile)
 */
export function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebar && overlay) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }
}

/**
 * Initialize accordion components
 */
export function initAccordions() {
    document.querySelectorAll('[data-accordion]').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.accordion);
            const chevron = this.querySelector('[data-lucide="chevron-down"]');

            if (target) {
                target.classList.toggle('hidden');
            }
            if (chevron) {
                chevron.classList.toggle('rotate-180');
            }
        });
    });
}
