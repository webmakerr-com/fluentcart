(function () {
    'use strict';

    var initHeaderShadow = function () {
        var header = document.querySelector('.codex-header');
        if (!header) {
            return;
        }

        var toggleShadow = function () {
            if (window.scrollY > 10) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };

        document.addEventListener('scroll', toggleShadow, { passive: true });
        window.addEventListener('load', toggleShadow);
        toggleShadow();
    };

    var initOffcanvasMenu = function () {
        var nav = document.querySelector('.codex-offcanvas-nav');
        var offcanvas = document.querySelector('#codexOffcanvas');
        if (!nav || !offcanvas) {
            return;
        }

        var toggleRecords = [];

        var bootstrapAvailable = typeof bootstrap !== 'undefined' && typeof bootstrap.Offcanvas !== 'undefined';

        if (!bootstrapAvailable) {
            var toggles = document.querySelectorAll('[data-bs-toggle="offcanvas"]');
            var closeButton = offcanvas.querySelector('[data-bs-dismiss="offcanvas"]');
            var backdrop = document.querySelector('.codex-offcanvas-backdrop');

            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'codex-offcanvas-backdrop';
                document.body.appendChild(backdrop);
            }

            var closeOffcanvas = function () {
                offcanvas.classList.remove('is-open');
                backdrop.classList.remove('show');
                document.body.classList.remove('codex-offcanvas-open');
                offcanvas.setAttribute('aria-hidden', 'true');
            };

            var openOffcanvas = function () {
                offcanvas.classList.add('is-open');
                backdrop.classList.add('show');
                document.body.classList.add('codex-offcanvas-open');
                offcanvas.setAttribute('aria-hidden', 'false');
            };

            toggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    openOffcanvas();
                });
            });

            if (closeButton) {
                closeButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    closeOffcanvas();
                });
            }

            backdrop.addEventListener('click', closeOffcanvas);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && offcanvas.classList.contains('is-open')) {
                    closeOffcanvas();
                }
            });
        }

        var queryDirectChild = function (element, selector) {
            return Array.prototype.find.call(element.children, function (child) {
                return child.matches(selector);
            }) || null;
        };

        var normalizeUrl = function (url) {
            if (!url) {
                return '';
            }
            var value = url.split('#')[0];
            value = value.split('?')[0];
            if (value.length > 1 && value.slice(-1) === '/') {
                value = value.slice(0, -1);
            }
            return value;
        };

        var buildSubmenuToggle = function (item) {
            var link = queryDirectChild(item, 'a');
            var submenu = queryDirectChild(item, '.sub-menu');
            if (!link || !submenu) {
                return;
            }

            if (queryDirectChild(item, '.codex-submenu-toggle')) {
                return;
            }

            var toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'codex-submenu-toggle';
            toggle.setAttribute('aria-expanded', 'false');
            toggle.innerHTML = '<span class="visually-hidden">Toggle submenu</span>' +
                '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.293 9.293a1 1 0 0 1 1.414 0L12 13.586l4.293-4.293a1 1 0 1 1 1.414 1.414l-5 5a1 1 0 0 1-1.414 0l-5-5a1 1 0 0 1 0-1.414z"/></svg>';

            link.insertAdjacentElement('afterend', toggle);

            var setState = function (open) {
                item.classList.toggle('is-open', open);
                submenu.style.maxHeight = open ? submenu.scrollHeight + 'px' : '0px';
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                setState(!item.classList.contains('is-open'));
            });

            toggleRecords.push({ item: item, submenu: submenu, setState: setState });

            if (item.classList.contains('current-menu-ancestor') || item.classList.contains('current-menu-parent')) {
                setState(true);
            }
        };

        nav.querySelectorAll('.menu-item-has-children').forEach(buildSubmenuToggle);

        var currentUrl = normalizeUrl(window.location.href);

        nav.querySelectorAll('a[href]').forEach(function (link) {
            var linkUrl = normalizeUrl(link.href);
            if (!linkUrl) {
                return;
            }

            if (linkUrl === currentUrl) {
                link.classList.add('is-active');
                var listItem = link.closest('li');
                if (listItem) {
                    listItem.classList.add('is-active');
                    var ancestorSubmenu = listItem.closest('.sub-menu');
                    while (ancestorSubmenu) {
                        var parentItem = ancestorSubmenu.parentElement;
                        if (parentItem && parentItem.classList.contains('menu-item-has-children')) {
                            var record = toggleRecords.find(function (data) {
                                return data.item === parentItem;
                            });
                            if (record) {
                                record.setState(true);
                            } else {
                                parentItem.classList.add('is-open');
                            }
                        }
                        ancestorSubmenu = parentItem ? parentItem.closest('.sub-menu') : null;
                    }
                }
            }
        });

        window.addEventListener('resize', function () {
            toggleRecords.forEach(function (record) {
                if (record.item.classList.contains('is-open')) {
                    record.submenu.style.maxHeight = record.submenu.scrollHeight + 'px';
                }
            });
        });
    };

    var init = function () {
        initHeaderShadow();
        initOffcanvasMenu();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
