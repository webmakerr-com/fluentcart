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

(function () {
    var STORAGE_KEY = 'fluentcart_post_id';

    function setPersistentPostId(id) {
        if (!id) {
            return;
        }

        var normalizedId = String(id);
        window.fluentcartPostId = normalizedId;

        try {
            localStorage.setItem(STORAGE_KEY, normalizedId);
        } catch (e) {
            // no-op
        }
    }

    function getPersistentPostId() {
        if (window.fluentcartPostId) {
            return window.fluentcartPostId;
        }

        try {
            var stored = localStorage.getItem(STORAGE_KEY);
            if (stored) {
                window.fluentcartPostId = stored;
                return stored;
            }
        } catch (e) {
            // no-op
        }

        return null;
    }

    function applyPostIdToPayload(args) {
        var postId = getPersistentPostId();
        if (!postId) {
            return args;
        }

        var updatedArgs = Array.prototype.slice.call(args);
        var payloadIndex = updatedArgs.length - 1;
        var payload = updatedArgs[payloadIndex];

        if (typeof payload === 'object' && payload !== null) {
            updatedArgs[payloadIndex] = Object.assign({}, payload, {
                post_id: postId,
                content_ids: [postId]
            });
        } else {
            updatedArgs.push({
                post_id: postId,
                content_ids: [postId]
            });
        }

        return updatedArgs;
    }

    function wrapFacebookPixel() {
        if (typeof window.fbq !== 'function' || window.fbq.__fluentCartWrapped) {
            return;
        }

        var originalFbq = window.fbq;

        var wrappedFbq = function () {
            var args = applyPostIdToPayload(arguments);
            return originalFbq.apply(this, args);
        };

        wrappedFbq.__fluentCartWrapped = true;

        // Preserve any queued calls or helpers on the original instance
        for (var key in originalFbq) {
            if (Object.prototype.hasOwnProperty.call(originalFbq, key)) {
                wrappedFbq[key] = originalFbq[key];
            }
        }

        window.fbq = wrappedFbq;
    }

    // Capture the product ID shared from PHP or earlier scripts.
    if (window.fluentcartPostId || window.fluent_cart_post_id) {
        setPersistentPostId(window.fluentcartPostId || window.fluent_cart_post_id);
    }

    // Listen for explicit product ID broadcasts.
    window.addEventListener('fluentcart:product-id', function (event) {
        var detail = event && event.detail;
        var postId = detail && (detail.post_id || detail.id);
        setPersistentPostId(postId);
    });

    // Attempt to wrap immediately and again after DOM changes in case fbq loads late.
    wrapFacebookPixel();

    var pixelObserver = new MutationObserver(function () {
        wrapFacebookPixel();
    });

    pixelObserver.observe(document.documentElement, {
        childList: true,
        subtree: true
    });
})();
