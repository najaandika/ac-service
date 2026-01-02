/**
 * AC Service - Main Application Entry
 * Imports all modules and initializes the application
 */

import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';

// Import modules
import { toggleSidebar, initAccordions } from './modules/sidebar';
import { openModal, closeModal } from './modules/modal';
import { togglePassword } from './modules/forms';
import { initDashboardCharts } from './modules/charts';
import './scroll-animation';
import './modules/order-notification';

// Register Alpine components
Alpine.data('quickOrder', (config) => ({
    selectedCapacity: config.defaultCapacity,
    unitPrice: config.defaultPrice,
    quantity: 1,
    slug: config.slug,

    selectCapacity(capacity, price) {
        this.selectedCapacity = capacity;
        this.unitPrice = price;
    },

    incrementQty() {
        if (this.quantity < 10) this.quantity++;
    },

    decrementQty() {
        if (this.quantity > 1) this.quantity--;
    },

    get total() {
        return this.unitPrice * this.quantity;
    },

    get formattedTotal() {
        return 'Rp ' + this.total.toLocaleString('id-ID');
    },

    get orderUrl() {
        return `/order?service=${this.slug}&capacity=${this.selectedCapacity}&qty=${this.quantity}`;
    }
}));

// Start Alpine
window.Alpine = Alpine;
Alpine.start();

// Expose functions globally for inline onclick handlers
window.toggleSidebar = toggleSidebar;
window.openModal = openModal;
window.closeModal = closeModal;
window.togglePassword = togglePassword;
window.initDashboardCharts = initDashboardCharts;

/**
 * Initialize application when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Initialize sidebar accordions
    initAccordions();

    // Auto-initialize dashboard charts if Chart.js is loaded
    if (typeof Chart !== 'undefined' && document.getElementById('revenueTrendChart')) {
        initDashboardCharts();
    }
});
