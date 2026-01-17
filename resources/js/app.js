/**
 * AC Service - Main Application Entry
 * Imports all modules and initializes the application
 */

import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Register Alpine plugins
Alpine.plugin(collapse);

// Import modules
import { toggleSidebar, initAccordions } from './modules/sidebar';
import { openModal, closeModal } from './modules/modal';
import { togglePassword } from './modules/forms';
import { initDashboardCharts } from './modules/charts';
import './scroll-animation';
import './modules/order-notification';
import './modules/mobile-nav';

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

Alpine.data('deleteModal', () => ({
    showDeleteModal: false,
    deleteUrl: '',
    itemName: '',

    openDeleteModal(url, name) {
        this.deleteUrl = url;
        this.itemName = name;
        this.showDeleteModal = true;
    }
}));

Alpine.data('promoDiscountForm', () => ({
    discountType: 'percentage',

    init() {
        // Get initial type from data attribute
        this.discountType = this.$el.dataset.initialType || 'percentage';
    }
}));

// Order form with summary modal
Alpine.data('orderSummaryForm', (config) => ({
    submitting: false,
    showSummary: false,
    summary: {
        layanan: '',
        jadwal: '',
        nama: '',
        whatsapp: '',
        alamat: '',
        harga: 0,
        diskon: 0,
        total: 0
    },

    defaultServiceName: config?.serviceName || '-',
    defaultPrice: config?.price || 0,

    formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    getSummary() {
        // Service name
        const checkedService = document.querySelector('[name=service_id]:checked');
        if (checkedService) {
            const label = checkedService.nextElementSibling;
            this.summary.layanan = label?.querySelector('.font-semibold')?.textContent?.trim() || this.defaultServiceName;
        } else {
            this.summary.layanan = this.defaultServiceName;
        }

        // Schedule
        const dateEl = document.getElementById('scheduled_date');
        const timeEl = document.getElementById('scheduled_time');
        this.summary.jadwal = (dateEl?.value || '') + ' ' + (timeEl?.value || '');

        // Customer info
        this.summary.nama = document.getElementById('name')?.value || '-';
        this.summary.whatsapp = document.getElementById('phone')?.value || '-';
        this.summary.alamat = document.getElementById('address')?.value || '-';

        // Price - try to get from price calculator or use default
        const priceEl = document.querySelector('[x-text="formattedTotal"]');
        if (priceEl && priceEl.textContent) {
            this.summary.harga = parseInt(priceEl.textContent.replace(/[^\d]/g, '')) || this.defaultPrice;
        } else {
            this.summary.harga = this.defaultPrice;
        }

        // Discount from promo section
        const discountEl = document.querySelector('[name="promo_discount"]');
        this.summary.diskon = parseInt(discountEl?.value) || 0;

        this.summary.total = this.summary.harga - this.summary.diskon;
        this.showSummary = true;
    },

    confirmOrder() {
        this.submitting = true;
        this.$root.submit();
    }
}));

// Price Calculator for order form - live price display
Alpine.data('priceCalculator', () => ({
    serviceName: '',
    capacity: '1pk',
    quantity: 1,
    unitPrice: 0,
    total: 0,
    prices: {},

    init() {
        this.updatePrice();

        // Listen to service radio changes
        document.querySelectorAll('input[name="service_id"]').forEach(radio => {
            radio.addEventListener('change', () => this.updatePrice());
        });

        // Listen to capacity changes
        const capacitySelect = document.getElementById('ac_capacity');
        if (capacitySelect) {
            capacitySelect.addEventListener('change', () => this.updatePrice());
        }

        // Listen to quantity changes via MutationObserver (since it's readonly)
        const quantityInput = document.getElementById('ac_quantity');
        if (quantityInput) {
            const observer = new MutationObserver(() => this.updatePrice());
            observer.observe(quantityInput, { attributes: true, attributeFilter: ['value'] });

            // Also check periodically for button clicks
            setInterval(() => {
                const newQty = parseInt(quantityInput.value) || 1;
                if (newQty !== this.quantity) {
                    this.quantity = newQty;
                    this.calculateTotal();
                }
            }, 200);
        }
    },

    updatePrice() {
        const selectedService = document.querySelector('input[name="service_id"]:checked');
        const capacitySelect = document.getElementById('ac_capacity');
        const quantityInput = document.getElementById('ac_quantity');

        if (selectedService) {
            this.serviceName = selectedService.dataset.name || '';
            this.prices = JSON.parse(selectedService.dataset.prices || '{}');
        }

        if (capacitySelect) {
            this.capacity = capacitySelect.value;
        }

        if (quantityInput) {
            this.quantity = parseInt(quantityInput.value) || 1;
        }

        this.calculateTotal();
    },

    calculateTotal() {
        // Get price for selected capacity
        this.unitPrice = this.prices[this.capacity] || 0;

        // If no specific capacity price, use minimum
        if (!this.unitPrice) {
            const priceValues = Object.values(this.prices);
            this.unitPrice = priceValues.length > 0 ? Math.min(...priceValues) : 0;
        }

        this.total = this.unitPrice * this.quantity;
    },

    get formattedTotal() {
        if (this.total === 0) {
            return 'Pilih layanan';
        }
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.total);
    }
}));

Alpine.data('promoCodeValidator', () => ({
    code: '',
    appliedCode: '',
    loading: false,
    applied: false,
    error: false,
    message: '',
    discount: 0,
    discountFormatted: '',

    init() {
        // Get service id from data attributes
        this.serviceId = this.$el.dataset.serviceId || null;
        this.baseSubtotal = parseFloat(this.$el.dataset.subtotal) || 0;
        this.unitPrice = parseFloat(this.$el.dataset.unitPrice) || this.baseSubtotal;
    },

    // Calculate current subtotal based on quantity
    getCurrentSubtotal() {
        const quantityInput = document.getElementById('ac_quantity');
        const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

        // Check for selected service radio button (full form flow)
        const selectedService = document.querySelector('input[name="service_id"]:checked');
        if (selectedService && selectedService.dataset.price) {
            return parseFloat(selectedService.dataset.price) * quantity;
        }

        // If unit price is available, calculate based on quantity
        if (this.unitPrice > 0) {
            return this.unitPrice * quantity;
        }
        // Fallback to base subtotal
        return this.baseSubtotal;
    },

    async applyPromo() {
        if (!this.code || this.loading) return;

        this.loading = true;
        this.error = false;
        this.message = '';

        try {
            const response = await fetch('/api/promo/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: this.code.toUpperCase(),
                    service_id: this.serviceId,
                    subtotal: this.getCurrentSubtotal()
                })
            });

            const data = await response.json();

            if (data.valid) {
                this.applied = true;
                this.appliedCode = this.code.toUpperCase();
                this.discount = data.discount;
                this.discountFormatted = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.discount);
                this.message = data.message || 'Promo berhasil diterapkan!';
                this.error = false;
            } else {
                this.error = true;
                this.message = data.message || 'Kode promo tidak valid';
                this.applied = false;
            }
        } catch (e) {
            this.error = true;
            this.message = 'Terjadi kesalahan, coba lagi';
            console.error('Promo validation error:', e);
        } finally {
            this.loading = false;
        }
    },

    resetPromo() {
        if (this.applied) {
            this.applied = false;
            this.appliedCode = '';
            this.discount = 0;
            this.discountFormatted = '';
        }
        this.error = false;
        this.message = '';
    },

    clearPromo() {
        this.code = '';
        this.resetPromo();
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

// Import & Expose Price Formatter Utils
import { formatRupiah, parseRupiah } from './utils/price-formatter';
window.formatRupiah = formatRupiah;
window.parseRupiah = parseRupiah;

// Audio preview utility for settings page
window.previewAudio = function (url) {
    const audio = new Audio(url);
    audio.play().catch(err => {
        alert('Tidak dapat memutar audio. Pastikan file valid.');
    });
};

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
