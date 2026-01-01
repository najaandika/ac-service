
window.incrementQuantity = function () {
    const input = document.getElementById('ac_quantity');
    if (input && input.value < 10) input.value = parseInt(input.value) + 1;
}

window.decrementQuantity = function () {
    const input = document.getElementById('ac_quantity');
    if (input && input.value > 1) input.value = parseInt(input.value) - 1;
}
