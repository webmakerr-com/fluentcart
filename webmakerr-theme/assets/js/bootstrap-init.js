(function () {
    const run = function () {
        if (typeof window.bootstrap === 'undefined') {
            return;
        }

        const tooltipTriggerList = Array.prototype.slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            bootstrap.Tooltip.getInstance(tooltipTriggerEl) || new bootstrap.Tooltip(tooltipTriggerEl);
        });
    };

    document.addEventListener('DOMContentLoaded', run);
})();
