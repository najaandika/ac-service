
window.incrementQuantity = function () {
    const input = document.getElementById('ac_quantity');
    if (input && input.value < 10) input.value = parseInt(input.value) + 1;
}

window.decrementQuantity = function () {
    const input = document.getElementById('ac_quantity');
    if (input && input.value > 1) input.value = parseInt(input.value) - 1;
}

/**
 * Service Details Alpine component
 * Handles conditional visibility of AC-specific fields
 */
window.serviceDetails = function () {
    return {
        isAcService: true,
        serviceName: '',

        init() {
            // Listen to service radio changes
            document.querySelectorAll('.service-radio').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    const category = e.target.dataset.category || 'ac';
                    this.isAcService = (category === 'ac');
                    this.serviceName = e.target.dataset.name || '';
                });

                // Check if already selected (page reload with old values)
                if (radio.checked) {
                    const category = radio.dataset.category || 'ac';
                    this.isAcService = (category === 'ac');
                    this.serviceName = radio.dataset.name || '';
                }
            });
        }
    };
}

/**
 * Form data persistence using localStorage
 * Saves form data as user types and restores on page reload
 */
function initFormPersistence() {
    const formFields = ['name', 'phone', 'email', 'address', 'city', 'ac_type', 'scheduled_time'];
    const storageKey = 'order_form_data';

    // Restore saved data
    const savedData = JSON.parse(localStorage.getItem(storageKey) || '{}');
    formFields.forEach(field => {
        const el = document.getElementById(field);
        if (el && savedData[field] && !el.value) {
            el.value = savedData[field];
        }
    });

    // Save on input
    formFields.forEach(field => {
        const el = document.getElementById(field);
        if (el) {
            el.addEventListener('input', () => {
                const data = JSON.parse(localStorage.getItem(storageKey) || '{}');
                data[field] = el.value;
                localStorage.setItem(storageKey, JSON.stringify(data));
            });
        }
    });

    // Clear on successful form submit
    document.querySelector('form')?.addEventListener('submit', function () {
        localStorage.removeItem(storageKey);
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initFormPersistence);
