/**
 * Price Input Formatter
 * Auto-formats number inputs with Indonesian thousand separator (dots)
 */

// Helper functions
export function formatRupiah(value) {
    if (!value) return '';
    // Remove non-digit characters then format
    let number = value.toString().replace(/\D/g, '');
    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

export function parseRupiah(value) {
    return value ? value.toString().replace(/\./g, '') : '';
}

document.addEventListener('DOMContentLoaded', function () {
    // Apply formatter to all price inputs
    document.querySelectorAll('input[name^="prices"]').forEach(function (input) {
        input.addEventListener('input', function (e) {
            let cursorPosition = this.selectionStart;
            let oldLength = this.value.length;

            // Allow only digits
            let val = this.value.replace(/\D/g, '');
            this.value = formatRupiah(val);

            let newLength = this.value.length;
            // Adjust cursor position
            cursorPosition += newLength - oldLength;
            this.setSelectionRange(cursorPosition, cursorPosition);
        });
    });
});

