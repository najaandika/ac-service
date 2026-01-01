/**
 * Price Input Formatter
 * Auto-formats number inputs with Indonesian thousand separator (dots)
 */

document.addEventListener('DOMContentLoaded', function () {
    // Format number with dots as thousand separator
    function formatNumber(value) {
        // Remove non-digit characters
        let number = value.replace(/\D/g, '');
        // Add thousand separators
        return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Apply formatter to all price inputs
    document.querySelectorAll('input[name^="prices"]').forEach(function (input) {
        input.addEventListener('input', function (e) {
            let cursorPosition = this.selectionStart;
            let oldLength = this.value.length;
            this.value = formatNumber(this.value);
            let newLength = this.value.length;
            // Adjust cursor position
            cursorPosition += newLength - oldLength;
            this.setSelectionRange(cursorPosition, cursorPosition);
        });
    });
});
