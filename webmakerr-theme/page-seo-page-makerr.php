<?php
/**
 * Template Name: Webmakerr Local page Builder
 * Template Post Type: page
 */

wp_enqueue_style(
    'bootstrap-5-landing',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    [],
    '5.3.3'
);
wp_enqueue_script(
    'bootstrap-5-landing',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    [],
    '5.3.3',
    true
);

$theme_dir = get_template_directory_uri();
$checkout_url = 'https://webmakerr.com/?fluent-cart=instant_checkout&item_id=23&quantity=1';

get_header();
?>

<script>
    (function() {
        var ua = (navigator.userAgent || navigator.vendor || window.opera || '').toLowerCase();
        var isFacebookInApp = /fbav|fban|fb_iab|fbiab|fbbv|fbios|fb4a/i.test(ua);
        var isInstagramInApp = /instagram/i.test(ua);
        var isInAppBrowser = isFacebookInApp || isInstagramInApp;

        if (isInAppBrowser) {
            document.documentElement.classList.add('is-inapp-browser');
            if (isFacebookInApp) {
                document.documentElement.classList.add('is-facebook-inapp');
            }

            if (document.body) {
                document.body.classList.add('is-inapp-browser');
                if (isFacebookInApp) {
                    document.body.classList.add('is-facebook-inapp');
                }
            }

            var headEl = document.head || document.getElementsByTagName('head')[0];
            var existingStylesheet = document.querySelector('link[href*="/assets/css/header-modern.css"]');
            var stylesheetHref = '<?php echo esc_url( $theme_dir . '/assets/css/header-modern.css' ); ?>';

            var ensureStylesheet = function(stylesheet) {
                if (!stylesheet) {
                    return false;
                }

                stylesheet.rel = 'stylesheet';
                stylesheet.media = 'all';
                return true;
            };

            if (!ensureStylesheet(existingStylesheet) && headEl) {
                var inAppStylesheet = document.createElement('link');
                inAppStylesheet.rel = 'stylesheet';
                inAppStylesheet.media = 'all';
                inAppStylesheet.href = stylesheetHref + (isFacebookInApp ? '?fb-inapp=1' : '?inapp=1');
                inAppStylesheet.id = 'codex-offcanvas-inapp-style';
                headEl.appendChild(inAppStylesheet);
            } else if (isFacebookInApp && headEl && existingStylesheet) {
                // Facebook's in-app browser intermittently strips stylesheet attributes; reinforce them and bypass caching.
                ensureStylesheet(existingStylesheet);
                if (!existingStylesheet.dataset.fbInapp) {
                    existingStylesheet.dataset.fbInapp = '1';
                    existingStylesheet.href = stylesheetHref + '?fb-inapp=1';
                }
            }
        }
    })();
</script>

<style>
    /* Ensure the mobile side menu logo renders consistently across browsers */
    .codex-offcanvas-logo img {
        display: block;
        max-height: 44px;
        max-width: 180px;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    .is-inapp-browser .codex-offcanvas-logo img {
        max-height: 44px;
    }

    .is-inapp-browser .codex-offcanvas .codex-offcanvas-links svg,
    .is-inapp-browser .codex-offcanvas .codex-offcanvas-meta svg,
    .is-inapp-browser .codex-offcanvas .cart__icon svg,
    .is-inapp-browser .codex-offcanvas .search-post__icon svg {
        width: 20px;
        height: 20px;
        min-width: 20px;
        min-height: 20px;
        flex-shrink: 0;
    }

    .is-inapp-browser .codex-offcanvas .search-post__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.2rem 0.35rem;
        line-height: 1;
        gap: 0;
    }

    :root {
        --bs-border-radius: 4px;
        --bs-border-radius-sm: 4px;
        --bs-border-radius-lg: 4px;
        --bs-border-radius-xl: 4px;
        --bs-border-radius-xxl: 4px;
        --bs-border-radius-pill: 4px;
    }

    .rounded-circle {
        border-radius: 4px !important;
    }

    .booking-ambient {
        background:
            radial-gradient(circle at 20% 20%, rgba(96, 165, 250, 0.08), transparent 45%),
            radial-gradient(circle at 80% 0%, rgba(167, 139, 250, 0.08), transparent 35%),
            radial-gradient(circle at 50% 80%, rgba(16, 185, 129, 0.08), transparent 40%);
    }
    .hero-animation-shell {
        min-height: 260px;
        background: linear-gradient(135deg, #1877F2 50%, #000000 50%);
        border-radius: 4px;
        overflow: hidden;
        padding: 24px;
        display: flex;
        align-items: center;
    }
    .hero-feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1.25rem;
        width: 100%;
    }
    .hero-feature-card {
        background: #fff;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        text-align: center;
        padding: 16px;
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.08);
    }
    .hero-feature-card svg {
        width: 64px;
        height: 64px;
        stroke: #000;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
    }
    .hero-feature-card span {
        display: block;
        margin-top: 12px;
        font-weight: 600;
        color: #0f172a;
    }
    .hero-title {
        font-size: clamp(2rem, 1.5rem + 2vw, 3.4rem);
    }
    .hero-info-row {
        gap: 1rem;
    }
    .hero-pill {
        white-space: nowrap;
    }
    .feature-card {
        width: 180px;
        height: 180px;
        border-radius: 4px;
    }
    .feature-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 4px;
        background: linear-gradient(135deg, #f9fafb, #eef2f7);
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    }
    .more-feature-card {
        transition: transform 180ms ease, box-shadow 180ms ease, background-color 180ms ease;
    }
    .more-feature-card:hover {
        transform: scale(1.03);
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.1);
        background-color: #f8fafc;
    }
    .more-feature-card .feature-icon-box svg {
        width: 48px;
        height: 48px;
        stroke: #000;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
    }

    .testimonial-rotate-card {
        transition: opacity 450ms ease, transform 450ms ease;
    }

    .testimonial-rotate-card.is-fading {
        opacity: 0;
        transform: translateY(8px);
    }

    .more-feature-grid {
        --bs-gutter-x: 1.25rem;
        --bs-gutter-y: 1.25rem;
    }

    @media (min-width: 992px) {
        .hero-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .hero-actions .btn {
            width: 100%;
            max-width: 260px;
        }

        .hero-actions .btn + .btn {
            margin-top: 0 !important;
        }
    }

    .mobile-sticky-bar {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.08);
        padding: 12px 16px;
    }

    @media (max-width: 767.98px) {
        .mobile-sticky-bar {
            display: block;
        }
        .hero-row {
            text-align: center;
        }
        .hero-copy {
            margin: 0 auto;
            max-width: 520px;
        }
        .hero-info-row {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .hero-animation-shell {
            padding: 0;
            min-height: 200px;
        }
        .hero-feature-card {
            padding: 14px;
        }
        .hero-feature-card span {
            margin-top: 8px;
        }
        .hero-feature-card svg {
            width: 52px;
            height: 52px;
        }
        .hero-actions {
            flex-direction: column;
            align-items: center;
            width: 100%;
            gap: 0.75rem;
        }
        .hero-actions .btn {
            width: 100%;
            max-width: 320px;
        }
        .hero-actions .btn + .btn {
            margin-top: 0 !important;
        }
        .hero-copy .badge,
        .hero-copy .text-muted,
        .hero-copy .d-flex {
            justify-content: center;
        }
        .hero-copy .text-muted,
        .hero-copy .hero-title,
        .hero-copy p {
            text-align: center;
        }
    }

    .trial-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        z-index: 2000;
        opacity: 0;
        transition: opacity 200ms ease;
    }

    .trial-modal-backdrop.is-visible {
        display: flex;
        opacity: 1;
    }

    .trial-modal {
        width: 100%;
        max-width: 640px;
        background: #ffffff;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.14);
        position: relative;
        overflow: hidden;
        color: #0f172a;
    }

    .trial-modal::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.08), transparent 48%),
            radial-gradient(circle at 80% 10%, rgba(16, 185, 129, 0.08), transparent 44%),
            linear-gradient(120deg, rgba(148, 163, 184, 0.05), rgba(255, 255, 255, 0));
        pointer-events: none;
    }

    .trial-modal__inner {
        position: relative;
        z-index: 2;
    }

    .trial-modal__header {
        padding: 28px 28px 0;
    }

    .trial-modal__body {
        padding: 20px 28px 28px;
        position: relative;
        z-index: 2;
    }

    .trial-modal__close {
        position: absolute;
        top: 14px;
        right: 14px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        width: 38px;
        height: 38px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        transition: background-color 150ms ease, transform 150ms ease, box-shadow 150ms ease;
        cursor: pointer;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        z-index: 3;
    }

    .trial-modal__close:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.12);
    }

    .trial-modal__subtitle {
        color: #475569;
    }

    .trial-modal__status {
        display: none;
        margin-top: 12px;
    }

    .trial-modal__status.is-visible {
        display: block;
    }

    .trial-modal__pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.92rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
    }

    .trial-modal__pill svg {
        width: 18px;
        height: 18px;
    }

    .trial-modal__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-top: 18px;
    }

    .trial-modal__card {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 12px 14px;
        color: #0f172a;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .trial-modal__icon {
        width: 34px;
        height: 34px;
        border-radius: 4px;
        background: linear-gradient(135deg, #eef2f7, #f9fafb);
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .trial-modal__icon svg {
        width: 18px;
        height: 18px;
        color: #0f172a;
    }

    .trial-modal__card h6 {
        margin: 0 0 4px;
        color: #0f172a;
    }

    .trial-modal__card p {
        margin: 0;
        color: #475569;
        font-size: 0.95rem;
    }

    .trial-modal__form {
        background: #ffffff;
        border-radius: 4px;
        padding: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        border: 1px solid #e5e7eb;
    }

    .trial-modal__form label {
        color: #0f172a;
    }

    .trial-modal__form .form-control {
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        font-size: 1rem;
        box-shadow: none;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }

    .trial-modal__form .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }

    .trial-modal__form .btn {
        border-radius: 4px;
        padding: 12px;
        font-weight: 600;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
    }

    @media (max-width: 575.98px) {
        .trial-modal {
            border-radius: 4px;
        }

        .trial-modal__header,
        .trial-modal__body {
            padding: 18px 16px 0;
        }

        .trial-modal__body {
            padding-bottom: 18px;
        }
    }
</style>

<section class="pt-5 pb-5 bg-light">
    <div class="container-lg">
        <div class="p-4 p-md-5 bg-white border rounded-4 shadow-sm row g-4 align-items-center hero-row">
            <div class="col-lg-6 hero-copy">
                <span class="d-inline-flex align-items-center small bg-light text-secondary px-3 py-1 rounded-pill mb-3">
                    Facebook Ads Mastery Course
                    <svg width="12" height="12" class="ms-2" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 2l4 4-4 4" />
                    </svg>
                </span>

                <h1 class="fw-semibold lh-sm text-dark hero-title">
                    Launch Facebook ads that scale revenue without the agency markup.<br>
                </h1>

                <p class="mt-3 text-secondary">
                    Facebook Ads Mastery gives you proven hooks, headlines, budget and bid calculators, retargeting scripts, and plug-and-play landing pages so campaigns go live fast and stay profitable.
                </p>

                <div class="d-flex flex-wrap hero-actions mt-4">
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg d-flex align-items-center gap-2 w-100" style="max-width:260px;">
                        <img src="<?php echo esc_url( $theme_dir . '/images/home/user3.png' ); ?>" width="18" alt="Enrollment icon">
                        Enroll Now
                    </a>

                    <a href="#get-free-trial" class="btn btn-light border btn-lg d-flex align-items-center justify-content-between w-100" data-trial-open style="max-width:260px;">
                        <span class="text-dark">Preview a Free Lesson</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.4">
                            <path d="M4 2l6 5-6 5" />
                        </svg>
                    </a>
                </div>

                <p class="small text-muted mt-2">Seats are limited—reserve your cohort spot today</p>

                <div class="d-flex gap-4 mt-4">
                    <img src="<?php echo esc_url( $theme_dir . '/images/trustf/sslupf.svg' ); ?>" height="20" alt="Trustpilot">
                    <img src="<?php echo esc_url( $theme_dir . '/images/trustf/nortonf.svg' ); ?>" height="20" alt="Google Reviews">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative bg-white border rounded-4 shadow-sm overflow-hidden booking-ambient">
                    <div class="p-4 position-relative" style="z-index:2; min-height:350px;">
                        <div class="d-flex justify-content-between align-items-start mb-3 hero-info-row">
                            <div>
                                <p class="fw-semibold text-dark mb-1">Campaign Launch Blueprint</p>
                                <p class="small text-muted mb-0">Plan offers, budgets, bids, and retargeting sequences so every ad set launches with clear hooks and confident spend.</p>
                            </div>
                            <span class="small text-muted fw-normal hero-pill">Instructor-led guidance</span>
                        </div>

                        <div class="d-flex flex-wrap gap-2 small text-muted mb-3">
                            <span class="border rounded-pill px-3 py-1">Proven hooks & headlines</span>
                            <span class="border rounded-pill px-3 py-1">Budget + bid calculators</span>
                            <span class="border rounded-pill px-3 py-1">Data-backed testing plan</span>
                        </div>

                        <div class="position-relative border rounded-3 p-3 shadow-sm hero-animation-shell">
                            <div class="ratio ratio-16x9 w-100">
                                <video
                                    class="w-100 h-100 rounded-3"
                                    data-src="https://cdn.webmakerr.com/website/webmakerr-local-page-builder.mp4"
                                    autoplay
                                    muted
                                    playsinline
                                    loop
                                    controls
                                    preload="none"
                                ></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="trial-modal-backdrop" id="freeTrialModal" aria-hidden="true" role="dialog">
    <div class="trial-modal" role="document">
        <button type="button" class="trial-modal__close" aria-label="Close" data-trial-close>
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4l12 12m0-12L4 16" />
            </svg>
        </button>

        <div class="trial-modal__inner">
            <div class="trial-modal__header">
                <span class="trial-modal__pill">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 10.5l3 3 9-9" />
                    </svg>
                    Verified cohort application
                </span>
                <h3 class="fw-semibold text-dark mt-3 mb-1" style="font-size: 1.6rem;">Claim Your Seat in Facebook Ads Mastery</h3>
                <p class="trial-modal__subtitle small mb-0">Tell us where to send your welcome kit, free lesson, and launch plan.</p>
            </div>

            <div class="trial-modal__body">
                <div class="trial-modal__grid">
                    <div class="trial-modal__card">
                        <span class="trial-modal__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M8 7h8M8 12h8M8 17h5M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z" />
                            </svg>
                        </span>
                        <div>
                            <h6 class="fw-semibold">Reserved cohort seat</h6>
                            <p class="mb-0">We lock in your spot and send the blueprint with hooks, bids, and retargeting scripts.</p>
                        </div>
                    </div>

                    <div class="trial-modal__card">
                        <span class="trial-modal__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 21c4.97 0 9-3.582 9-8s-4.03-8-9-8-9 3.582-9 8c0 2.386 1.086 4.54 2.842 6.036L5 21l2.512-1.257C9 20.563 10.455 21 12 21z" />
                            </svg>
                        </span>
                        <div>
                            <h6 class="fw-semibold">Instructor support</h6>
                            <p class="mb-0">Get direct guidance on creative, budgets, and funnel tweaks before you launch.</p>
                        </div>
                    </div>
                </div>

                <div class="trial-modal__form mt-3">
                    <form id="freeTrialForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="trialName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="trialName" name="trialName" placeholder="Jane Doe" required>
                        </div>

                        <div class="mb-3">
                            <label for="trialEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="trialEmail" name="trialEmail" placeholder="you@company.com" required>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 d-flex justify-content-center align-items-center gap-2" data-trial-submit>
                            <span>Submit</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" data-trial-spinner></span>
                        </button>

                        <div class="alert alert-success trial-modal__status mt-3" role="status" data-trial-success>
                            Thanks! Your request has been received. Check your inbox for next steps.
                        </div>
                        <div class="alert alert-danger trial-modal__status mt-3" role="alert" data-trial-error>
                            Something went wrong. Please try again in a moment.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var testimonials = [
            {
                quote: '“The launch templates let us ship three winning campaigns in week one. Our cost per lead dropped 42% without hiring an agency.”',
                name: 'Noah Patel',
                title: 'Marketing Ops Lead, Horizon Creative',
                initial: 'N'
            },
            {
                quote: '“Following the weekly optimization checklist took us to 3.1x ROAS in 30 days. The offer tweaks alone paid for the course in a weekend.”',
                name: 'Melissa Grant',
                title: 'VP Growth, Northwind Legal',
                initial: 'M'
            },
            {
                quote: '“The retargeting scripts and email automations filled the gaps in our funnel. Every ad click now gets a personalized follow-up sequence.”',
                name: 'Sofia Alvarez',
                title: 'Head of Growth, Latitude Labs',
                initial: 'S'
            }
        ];

        var desktopCards = Array.prototype.slice.call(document.querySelectorAll('[data-testimonial-card="desktop"]'));
        var mobileCard = document.querySelector('[data-testimonial-card="mobile"]');
        var activeIndex = 1;
        var rotationDelay = 5000;

        function setCardContent(card, testimonial) {
            if (!card || !testimonial) {
                return;
            }

            var quoteEl = card.querySelector('[data-testimonial-quote]');
            var nameEl = card.querySelector('[data-testimonial-name]');
            var titleEl = card.querySelector('[data-testimonial-title]');
            var initialEl = card.querySelector('[data-testimonial-initial]');

            if (quoteEl) {
                quoteEl.textContent = testimonial.quote;
            }
            if (nameEl) {
                nameEl.textContent = testimonial.name;
            }
            if (titleEl) {
                titleEl.textContent = testimonial.title;
            }
            if (initialEl) {
                initialEl.textContent = testimonial.initial;
            }
        }

        function renderDesktop() {
            if (!desktopCards.length) {
                return;
            }

            var total = testimonials.length;
            var order = [
                (activeIndex + total - 1) % total,
                activeIndex % total,
                (activeIndex + 1) % total
            ];

            desktopCards.forEach(function (card, index) {
                setCardContent(card, testimonials[order[index]]);
            });
        }

        function renderMobile() {
            if (!mobileCard) {
                return;
            }

            setCardContent(mobileCard, testimonials[activeIndex % testimonials.length]);
        }

        function fadeCards(cards, callback) {
            cards.forEach(function (card) {
                card.classList.add('is-fading');
            });

            setTimeout(function () {
                callback();
                requestAnimationFrame(function () {
                    cards.forEach(function (card) {
                        card.classList.remove('is-fading');
                    });
                });
            }, 200);
        }

        function rotateTestimonials() {
            activeIndex = (activeIndex + 1) % testimonials.length;

            fadeCards(desktopCards, renderDesktop);
            fadeCards(mobileCard ? [mobileCard] : [], renderMobile);
        }

        renderDesktop();
        renderMobile();

        setInterval(rotateTestimonials, rotationDelay);
    });
</script>

<section class="py-5 bg-light border-top">
    <div class="container-lg">
        <div class="text-center mx-auto" style="max-width: 700px;">
            <span class="d-inline-flex align-items-center bg-white border px-3 py-1 rounded-pill text-secondary small shadow-sm mb-3">
                Why teams choose Facebook Ads Mastery
            </span>

            <h2 class="fw-semibold display-6 text-dark lh-sm">
                Everything you need to launch Facebook ads that convert
            </h2>

            <p class="mt-3 text-secondary">
                Get the creative, calculators, scripts, and checklists to build profitable campaigns faster—while your team focuses on refining offers and turning spend into revenue.
            </p>

            <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg mt-4 shadow-sm">
                Enroll Now
            </a>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-md-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                    <span class="d-flex justify-content-center align-items-center bg-light rounded-3 shadow-sm mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold text-secondary small">01</span>
                    </span>

                    <h5 class="fw-semibold text-dark">Creative that earns the click</h5>

                    <p class="text-muted small mt-2">
                        Swipe proven hooks and headlines, then plug them into ready-to-launch ad angles so every campaign starts with conversion-focused creative.
                    </p>

                    <img src="https://cdn.webmakerr.com/website/facebook-ads-3.png" alt="Facebook ads creative frameworks graphic" class="mt-auto pt-3 w-100" style="height: 250px; object-fit: cover;" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                    <span class="d-flex justify-content-center align-items-center bg-light rounded-3 shadow-sm mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold text-secondary small">02</span>
                    </span>

                    <h5 class="fw-semibold text-dark">Optimization that compounds</h5>

                    <p class="text-muted small mt-2">
                        Follow weekly optimization checklists that tighten targeting, refresh creatives, and steadily raise ROAS without guesswork.
                    </p>

                    <img src="https://cdn.webmakerr.com/website/facebook-ads-1.png" alt="Optimization checklist graphic" class="mt-auto pt-3 w-100" style="height: 250px; object-fit: cover;" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                    <span class="d-flex justify-content-center align-items-center bg-light rounded-3 shadow-sm mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold text-secondary small">03</span>
                    </span>

                    <h5 class="fw-semibold text-dark">Measurement built for teams</h5>

                    <p class="text-muted small mt-2">
                        Use budget and bid calculators, tracking templates, and retargeting scripts to scale spend confidently and keep pipelines full.
                    </p>

                    <img src="https://cdn.webmakerr.com/website/facebook-ads-2.png" alt="Measurement and retargeting toolkit graphic" class="mt-auto pt-3 w-100" style="height: 250px; object-fit: cover;" />

                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container-lg">
        <h2 class="text-center fw-semibold lh-sm text-dark mb-5" style="font-size: 2.5rem;">
            ...plus everything needed for a profitable Facebook ads system
        </h2>

        <div class="row g-4 justify-content-center more-feature-grid">
            <?php
            $icons = [
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="10" y="14" width="44" height="30" rx="6"></rect><path d="M10 26h44"></path><path d="M22 8v12"></path><path d="M42 8v12"></path><path d="M20 38h16"></path><path d="M36 38l4 6 8-10"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="10" y="12" width="28" height="32" rx="6"></rect><path d="M18 20h12"></path><path d="M18 28h12"></path><path d="M18 36h12"></path><rect x="38" y="18" width="16" height="28" rx="4"></rect><path d="M38 30h16"></path><path d="M38 26l4-4"></path><path d="M54 38l-4 4"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16h24l6 10-18 22-18-22z"></path><path d="M20 16l12 16 12-16"></path><path d="M24 46h16"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M32 12c9.941 0 18 6.716 18 15 0 4.542-2.13 8.766-5.7 11.7L44 46H20l-0.3-7.3C16.13 35.766 14 31.542 14 27c0-8.284 8.059-15 18-15z"></path><path d="M26 46v4a6 6 0 0 0 12 0v-4"></path><path d="M27 30h10"></path><path d="M32 24v12"></path><path d="M40 34l4 4 6-8"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="12" y="16" width="40" height="26" rx="5"></rect><path d="M12 26h40"></path><path d="M22 18v6"></path><path d="M42 18v6"></path><path d="M24 38h8"></path><path d="M36 32h6"></path><path d="M20 32h8"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 14a6 6 0 1 1-6 6 6 6 0 0 1 6-6z"></path><path d="M46 12a6 6 0 1 1-6 6 6 6 0 0 1 6-6z"></path><path d="M48 38a6 6 0 1 1-6 6 6 6 0 0 1 6-6z"></path><path d="M22 38a6 6 0 1 1-6 6 6 6 0 0 1 6-6z"></path><path d="M24 22 38 18"></path><path d="M46 18 50 32"></path><path d="M22 38l18 6"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 44c0-8.836 7.164-16 16-16h12"></path><path d="M44 16h-8c-8.836 0-16 7.164-16 16v16"></path><path d="M46 28 54 20"></path><path d="M46 28l8 8"></path><circle cx="22" cy="48" r="4"></circle></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 42l8-18 8 10 10-18 10 26"></path><path d="M14 52h36"></path><path d="M22 22h-6"></path><path d="M46 18h-8"></path><circle cx="24" cy="50" r="2"></circle><circle cx="40" cy="50" r="2"></circle><path d="M18 12h12l-2 6h-8z"></path><path d="M34 34l6 6"></path></svg>',
            ];

            $labels = [
                'Proven hooks & headlines',
                'Budget & bid calculators',
                'Retargeting scripts that convert',
                'Plug-and-play landing pages',
                'Data-backed testing plan',
                'Checklists for every launch',
                'Creative and offer swipe files',
                'Student results library'
            ];

            foreach ($labels as $index => $label):
                ?>
                <div class="col-6 col-md-3 d-flex justify-content-center">
                    <div class="feature-card more-feature-card bg-white border rounded-4 shadow-sm d-flex flex-column align-items-center justify-content-center p-3">
                        <div class="feature-icon-box d-flex align-items-center justify-content-center position-relative mb-3">
                            <?php echo $icons[$index]; ?>
                        </div>
                        <p class="fw-medium text-dark small mb-0"><?php echo $label; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container-lg text-center">
        <span class="d-inline-flex align-items-center bg-white border px-3 py-1 rounded-pill text-secondary small shadow-sm mb-3">
            Student results
        </span>

        <h2 class="fw-semibold lh-sm text-dark" style="font-size: 2.5rem;">
            Marketers turning ads into revenue
        </h2>

        <p class="mt-3 text-secondary small mx-auto" style="max-width: 620px;">
            See how the Facebook Ads course helps founders and growth teams launch profitable campaigns without guesswork.
        </p>

        <div class="d-none d-md-flex justify-content-center align-items-center gap-4 mt-5" data-testimonial-container="desktop">
            <div class="bg-white border rounded-4 shadow-sm p-4 testimonial-rotate-card" data-testimonial-card="desktop" data-position="left" style="width: 350px; height: 210px; opacity: 0.35; transform: scale(0.94);">
                <p class="text-dark small fst-italic mb-4" data-testimonial-quote="desktop"></p>

                <div class="d-flex align-items-center">
                    <svg width="36" height="36" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="20" cy="20" r="18" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="20" y="22" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="black" data-testimonial-initial="desktop"></text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1" data-testimonial-name="desktop"></p>
                        <p class="text-muted small m-0" data-testimonial-title="desktop"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-4 shadow-sm p-4 testimonial-rotate-card" data-testimonial-card="desktop" data-position="center" style="width: 420px; height: 230px;">
                <p class="text-dark fw-semibold mb-4" data-testimonial-quote="desktop"></p>

                <div class="d-flex align-items-center">
                    <svg width="40" height="40" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="22" cy="22" r="20" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="22" y="24" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="black" data-testimonial-initial="desktop"></text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1" data-testimonial-name="desktop"></p>
                        <p class="text-muted small m-0" data-testimonial-title="desktop"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-4 shadow-sm p-4 testimonial-rotate-card" data-testimonial-card="desktop" data-position="right" style="width: 350px; height: 210px; opacity: 0.35; transform: scale(0.94);">
                <p class="text-dark small fst-italic mb-4" data-testimonial-quote="desktop"></p>

                <div class="d-flex align-items-center">
                    <svg width="36" height="36" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="20" cy="20" r="18" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="20" y="22" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="black" data-testimonial-initial="desktop"></text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1" data-testimonial-name="desktop"></p>
                        <p class="text-muted small m-0" data-testimonial-title="desktop"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-md-none mx-auto mt-4" style="max-width: 380px;" data-testimonial-container="mobile">
            <div class="bg-white border rounded-4 shadow-sm p-4 testimonial-rotate-card" data-testimonial-card="mobile">
                <p class="text-dark fw-semibold mb-4" data-testimonial-quote="mobile"></p>

                <div class="d-flex align-items-center">
                    <svg width="36" height="36" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="20" cy="20" r="18" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="20" y="22" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="black" data-testimonial-initial="mobile"></text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1" data-testimonial-name="mobile"></p>
                        <p class="text-muted small m-0" data-testimonial-title="mobile"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5 row g-4 align-items-center">
            <div class="col-md-5 d-flex flex-column align-items-center align-items-md-start text-center text-md-start">
                    <span class="d-inline-flex align-items-center bg-white border px-3 py-1 rounded-pill text-secondary small shadow-sm mb-3">
                    Process
                    </span>

                <h2 class="fw-semibold lh-sm text-dark mb-3" style="font-size: 2.25rem;">
                    Launch campaigns without guesswork.
                </h2>

                <p class="text-muted small mb-4" style="max-width: 420px;">
                    Follow the curriculum, checklists, and calculators with weekly updates—so leadership knows what’s launching, what’s optimizing, and when the next revenue lift lands.
                </p>

                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start w-100" style="max-width: 420px;">
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg shadow-sm">Enroll Now</a>
                    <a href="#get-free-trial" class="btn btn-light border btn-lg" data-trial-open>Preview a Free Lesson</a>
                </div>
            </div>

            <div class="col-md-7">
                <div class="bg-white border rounded-4 shadow-sm overflow-hidden">
                    <div class="p-4 p-md-5">
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden border" style="--bs-border-opacity: 0.35;">
                            <video
                                class="w-100 h-100"
                                data-src="https://cdn.webmakerr.com/Learn/FacebookAds/1.Facebook%20Advertising%20Overview.mp4"
                                style="object-fit: cover;"
                                controls
                                playsinline
                                autoplay
                                muted
                                preload="none"
                            ></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-top">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center bg-light border px-3 py-1 rounded-pill text-secondary small mb-3">Frequently Asked Questions</span>
                    <h2 class="fw-semibold text-dark">Clear answers so you can enroll with confidence</h2>
                    <p class="text-secondary mt-2">If you're on the fence, these quick answers explain exactly what you get, how it works, and what to do if it isn't the right fit.</p>
                </div>

                <div class="accordion" id="faqsAccordion">
                    <div class="accordion-item border rounded-3 mb-3">
                        <h2 class="accordion-header" id="faqOneHeading">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="true" aria-controls="faqOne">
                                How quickly will I start seeing results?
                            </button>
                        </h2>
                        <div id="faqOne" class="accordion-collapse collapse show" aria-labelledby="faqOneHeading" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body text-secondary">
                                Most members launch their first optimized campaigns within the first week using our ready-to-use templates, checklists, and ad calculators. Your pace depends on implementation, but every module is designed for fast action.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3">
                        <h2 class="accordion-header" id="faqTwoHeading">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo">
                                What if I'm new to Facebook ads?
                            </button>
                        </h2>
                        <div id="faqTwo" class="accordion-collapse collapse" aria-labelledby="faqTwoHeading" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body text-secondary">
                                The curriculum includes beginner-friendly walkthroughs plus advanced modules. We show you how to set up the pixel, structure campaigns, and scale without guesswork—no prior media buying experience required.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3">
                        <h2 class="accordion-header" id="faqThreeHeading">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree">
                                Do I get support if I get stuck?
                            </button>
                        </h2>
                        <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="faqThreeHeading" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body text-secondary">
                                Yes. You’ll receive weekly live Q&A access with our instructors and can submit campaign screenshots for feedback. We also provide email support to help troubleshoot creative, targeting, and tracking questions.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3">
                        <h2 class="accordion-header" id="faqFourHeading">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour">
                                Is there a guarantee?
                            </button>
                        </h2>
                        <div id="faqFour" class="accordion-collapse collapse" aria-labelledby="faqFourHeading" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body text-secondary">
                                If you complete the core modules and templates within 30 days and don’t feel more confident running profitable ads, email us and we’ll work with you to make it right.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3">
                        <h2 class="accordion-header" id="faqFiveHeading">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive">
                                Can I access updates in the future?
                            </button>
                        </h2>
                        <div id="faqFive" class="accordion-collapse collapse" aria-labelledby="faqFiveHeading" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body text-secondary">
                                Absolutely. Enrollment includes ongoing updates whenever Meta releases major changes. We refresh the playbooks and templates so your campaigns stay compliant and competitive.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="position-relative bg-white border rounded-4 shadow-sm p-4 p-md-5 text-center overflow-hidden">
            <div class="position-absolute top-0 bottom-0 start-0 end-0 opacity-25"
                 style="background-image: radial-gradient(circle at center, #000 1px, transparent 1px), radial-gradient(circle at center, #000 1px, transparent 1px); background-size: 34px 34px; background-position: 0 0, 17px 17px; pointer-events: none;">
            </div>

            <div class="position-relative" style="z-index:2;">
                <h2 class="fw-semibold lh-sm text-dark mx-auto" style="font-size: 2.25rem; max-width: 700px;">
                    Claim your seat in Facebook Ads Mastery.
                    <br class="d-none d-sm-block">Bonuses disappear when the cohort fills.
                </h2>

                <div class="mt-4">
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg shadow-sm">
                        Enroll Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="mobile-sticky-bar d-md-none">
    <div class="container-lg">
        <div class="d-flex gap-2">
            <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg w-100">
                Enroll Now
            </a>
            <a href="#get-free-trial" class="btn btn-light border btn-lg w-100 text-dark" data-trial-open>
                Preview a Free Lesson
            </a>
        </div>
    </div>
</div>

<script>
    (function () {
        var modalBackdrop = document.getElementById('freeTrialModal');
        var form = document.getElementById('freeTrialForm');
        var nameInput = document.getElementById('trialName');
        var emailInput = document.getElementById('trialEmail');
        var successAlert = modalBackdrop ? modalBackdrop.querySelector('[data-trial-success]') : null;
        var errorAlert = modalBackdrop ? modalBackdrop.querySelector('[data-trial-error]') : null;
        var spinner = modalBackdrop ? modalBackdrop.querySelector('[data-trial-spinner]') : null;
        var submitBtn = modalBackdrop ? modalBackdrop.querySelector('[data-trial-submit]') : null;
        var webhookUrl = 'https://webmakerr.com/?fluentcrm=1&route=contact&hash=f4146b0f-34b7-4677-8b3c-9628b056387b';

        if (!modalBackdrop || !form || !nameInput || !emailInput) {
            return;
        }

        var toggleModal = function (show) {
            modalBackdrop.classList.toggle('is-visible', show);
            modalBackdrop.setAttribute('aria-hidden', show ? 'false' : 'true');
            document.body.classList.toggle('overflow-hidden', show);

            if (show) {
                successAlert && successAlert.classList.remove('is-visible');
                errorAlert && errorAlert.classList.remove('is-visible');
                nameInput.focus();
            }
        };

        var closeModal = function () {
            toggleModal(false);
        };

        var openButtons = document.querySelectorAll('[data-trial-open]');
        openButtons.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                toggleModal(true);
            });
        });

        var closeButtons = modalBackdrop.querySelectorAll('[data-trial-close]');
        closeButtons.forEach(function (btn) {
            btn.addEventListener('click', closeModal);
        });

        modalBackdrop.addEventListener('click', function (event) {
            if (event.target === modalBackdrop) {
                closeModal();
            }
        });

        document.addEventListener('keyup', function (event) {
            if (event.key === 'Escape' && modalBackdrop.classList.contains('is-visible')) {
                closeModal();
            }
        });

        var setLoading = function (isLoading) {
            if (!submitBtn || !spinner) {
                return;
            }

            submitBtn.disabled = isLoading;
            spinner.classList.toggle('d-none', !isLoading);
        };

        var showStatus = function (type) {
            if (successAlert) {
                successAlert.classList.toggle('is-visible', type === 'success');
            }
            if (errorAlert) {
                errorAlert.classList.toggle('is-visible', type === 'error');
            }
        };

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var name = nameInput.value.trim();
            var email = emailInput.value.trim();

            showStatus('');
            if (!name || !email) {
                showStatus('error');
                return;
            }

            setLoading(true);

            fetch(webhookUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    first_name: name
                })
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json().catch(function () {
                        return {};
                    });
                })
                .then(function () {
                    form.reset();
                    showStatus('success');
                    window.location.href = '/thank-you-contact';
                })
                .catch(function () {
                    showStatus('error');
                })
                .finally(function () {
                    setLoading(false);
                });
        });
    })();
</script>

<script>
    window.addEventListener('load', function () {
        var lazyVideos = document.querySelectorAll('video[data-src]');

        lazyVideos.forEach(function (video) {
            var source = video.getAttribute('data-src');

            if (!source) {
                return;
            }

            var startPlayback = function () {
                var playPromise = video.play();

                if (playPromise && typeof playPromise.catch === 'function') {
                    playPromise.catch(function () {
                        // Suppress autoplay promise rejections in browsers that block playback.
                    });
                }
            };

            video.muted = true;
            video.setAttribute('muted', '');
            video.setAttribute('autoplay', '');
            video.setAttribute('playsinline', '');
            video.src = source;
            video.removeAttribute('data-src');

            if (video.readyState >= 2) {
                startPlayback();
            } else {
                video.addEventListener(
                    'loadeddata',
                    function handleLoadedData() {
                        video.removeEventListener('loadeddata', handleLoadedData);
                        startPlayback();
                    }
                );
            }
        });
    });
</script>

<section class="wmk-disclaimer" style="padding:20px 0; font-size:11px; color:#777; line-height:1.5;">
    <div class="container">
        <p>Important Disclaimer: This training is for educational purposes only and does not guarantee results. This page is not endorsed by or affiliated with Meta Platforms, Inc. Advertisers are responsible for following all Meta Advertising Policies.</p>
    </div>
</section>


<?php get_footer(); ?>
