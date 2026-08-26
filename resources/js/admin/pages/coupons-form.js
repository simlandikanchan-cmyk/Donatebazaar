(function () {
    'use strict';

    function toggleMaxDiscount() {
        var type = document.getElementById('discount_type');
        var field = document.getElementById('maxDiscountField');
        var hint = document.getElementById('valueHint');
        if (!type || !field || !hint) return;
        if (type.value === 'percent') {
            field.style.display = 'block';
            hint.textContent = 'Percentage of the donation amount.';
        } else {
            field.style.display = 'none';
            hint.textContent = 'Amount in \u20B9 to deduct.';
        }
    }

    var typeEl = document.getElementById('discount_type');
    if (typeEl) {
        typeEl.addEventListener('change', toggleMaxDiscount);
        toggleMaxDiscount();
    }
})();
