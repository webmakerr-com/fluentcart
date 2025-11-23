(function () {
    function setExpanded(toggle, expanded) {
        if (toggle) {
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        }
    }

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-fluent-cart-checkout-page-coupon-field-toggle]');
        if (!toggle) {
            return;
        }

        var container = document.querySelector('[data-fluent-cart-checkout-page-coupon-container]');
        if (!container) {
            return;
        }

        var wasHidden = container.hasAttribute('hidden');
        event.preventDefault();

        setTimeout(function () {
            var isHidden = container.hasAttribute('hidden');

            if (isHidden === wasHidden) {
                if (isHidden) {
                    container.removeAttribute('hidden');
                    setExpanded(toggle, true);
                } else {
                    container.setAttribute('hidden', '');
                    setExpanded(toggle, false);
                }
            } else {
                setExpanded(toggle, !isHidden);
            }
        }, 0);
    });
})();
