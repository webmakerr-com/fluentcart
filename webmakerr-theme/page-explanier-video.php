<?php
/**
 * Template Name: Explainer Video Service Landing
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
$checkout_url = 'https://beta.webmakerr.com/?fluent-cart=instant_checkout&item_id=1&quantity=1';

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

    .hero-section .hero-primary-btn,
    .hero-section .hero-secondary-btn {
        max-width: 280px;
    }

    @media (max-width: 767.98px) {
        .hero-section .hero-copy {
            text-align: center;
        }

        .hero-section .hero-copy > *:not(:last-child) {
            margin-left: auto;
            margin-right: auto;
        }

        .hero-section .hero-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        .hero-section .hero-primary-btn,
        .hero-section .hero-secondary-btn {
            width: 100%;
            max-width: 100%;
            justify-content: center;
        }

        .hero-section .hero-trust {
            justify-content: center;
        }

        .mobile-sticky-bar {
            display: block;
        }
    }
</style>

<section class="pt-5 pb-5 bg-light hero-section">
    <div class="container-lg">
        <div class="p-4 p-md-5 bg-white border rounded-4 shadow-sm row g-4 align-items-center">
            <div class="col-lg-6 hero-copy">
                <span class="d-inline-flex align-items-center small bg-light text-secondary px-3 py-1 rounded-pill mb-3">
                    Premium production • Conversion-focused explainer videos
                    <svg width="12" height="12" class="ms-2" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 2l4 4-4 4" />
                    </svg>
                </span>

                <h1 class="fw-semibold display-5 lh-sm text-dark">
                    Turn your offer into a 90-second story that closes deals.<br>
                </h1>

                <p class="mt-3 text-secondary">
                    We plan, script, and produce cinematic explainers that remove buyer doubt—so your team gets more demos booked, faster sales cycles, and a brand narrative that feels premium.
                </p>

                <div class="hero-actions">
                <a href="<?php echo esc_url( $checkout_url ); ?>" class="mt-4 btn btn-dark btn-lg d-flex align-items-center gap-2 w-100 hero-primary-btn">
                    <img src="<?php echo esc_url( $theme_dir . '/images/home/user3.png' ); ?>" width="18" alt="Google icon">
                    Get my video proposal
                </a>

                <a href="<?php echo esc_url( $checkout_url ); ?>" class="mt-2 btn btn-light border btn-lg d-flex align-items-center justify-content-between w-100 hero-secondary-btn">
                    <span class="text-dark">Schedule a 15-min strategy call</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.4">
                        <path d="M4 2l6 5-6 5" />
                    </svg>
                </a>
                </div>

                <p class="small text-muted mt-2">We accept a limited number of productions each month to protect quality. Save your spot now.</p>

                <div class="d-flex gap-4 mt-4 hero-trust">
                    <img src="<?php echo esc_url( $theme_dir . '/images/trustf/sslupf.svg' ); ?>" height="20" alt="Trustpilot">
                    <img src="<?php echo esc_url( $theme_dir . '/images/trustf/nortonf.svg' ); ?>" height="20" alt="Google Reviews">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative bg-white border rounded-4 shadow-sm overflow-hidden booking-ambient">
                    <div class="p-4 position-relative" style="z-index:2; min-height:350px;">
                        <div class="d-flex flex-wrap gap-2 small text-muted mb-3">
                            <span class="border rounded-pill px-3 py-1">Concept engineered to convert</span>
                            <span class="border rounded-pill px-3 py-1">Script, storyboard, voiceover included</span>
                            <span class="border rounded-pill px-3 py-1">Delivery window: 14–21 days</span>
                        </div>

                        <div class="position-relative border rounded-3 p-3 shadow-sm hero-animation-shell">
                            <div class="ratio ratio-16x9 w-100">
                                <video class="w-100 h-100 rounded-3" src="https://cdn.webmakerr.com/website/explainer-video.mp4" autoplay muted playsinline loop controls></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light border-top">
    <div class="container-lg">
        <div class="text-center mx-auto" style="max-width: 700px;">
            <span class="d-inline-flex align-items-center bg-white border px-3 py-1 rounded-pill text-secondary small shadow-sm mb-3">
                What you get
            </span>

            <h2 class="fw-semibold display-6 text-dark lh-sm">
                A conversion-ready explainer, scripted and produced end-to-end
            </h2>

            <p class="mt-3 text-secondary">
                We handle the research, script, storyboard, design, voiceover, and delivery—so you launch a premium video without chasing freelancers or guessing what will resonate.
            </p>

            <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg mt-4 shadow-sm">
                Start my explainer →
            </a>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-md-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                    <span class="d-flex justify-content-center align-items-center bg-light rounded-3 shadow-sm mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold text-secondary small">01</span>
                    </span>

                    <h5 class="fw-semibold text-dark">Narratives that make prospects feel understood</h5>

                    <p class="text-muted small mt-2">
                        We distill your value into a clear storyline and voiceover that mirrors your buyer’s language, so they instantly see you solve their exact problem.
                    </p>

                    <div class="mt-auto d-flex justify-content-center pt-3">
                        <div class="position-relative border rounded-circle d-flex justify-content-center align-items-center" style="width: 160px; height: 160px;">
                            <span class="position-absolute bg-white px-2 py-1 small border rounded-pill shadow">
                                App
                            </span>

                            <span class="position-absolute top-0 translate-middle-x bg-white p-2 border rounded-circle small shadow">
                                📅
                            </span>
                            <span class="position-absolute start-0 top-50 translate-middle-y bg-white p-2 border rounded-circle small shadow">
                                🔄
                            </span>
                            <span class="position-absolute bottom-0 translate-middle-x bg-white p-2 border rounded-circle small shadow">
                                📆
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                    <span class="d-flex justify-content-center align-items-center bg-light rounded-3 shadow-sm mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold text-secondary small">02</span>
                    </span>

                    <h5 class="fw-semibold text-dark">Design that makes your product feel inevitable</h5>

                    <p class="text-muted small mt-2">
                        Bespoke illustration, motion, and sound tied to your brand system—so every scene looks like it shipped from your product team, not a template.
                    </p>

                    <div class="mt-auto pt-3">
                        <div class="border rounded-3 shadow-sm p-3 bg-white">
                            <div class="d-flex justify-content-between text-dark small mb-2">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle bg-dark d-inline-block" style="width: 8px; height: 8px;"></span> Mon
                                </span>
                                <span>8:30am – 5:00pm</span>
                            </div>

                            <div class="d-flex justify-content-between text-muted small mb-2">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle bg-secondary d-inline-block opacity-50" style="width: 8px; height: 8px;"></span> Tue
                                </span>
                                <span>–</span>
                            </div>

                            <div class="d-flex justify-content-between text-dark small">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle bg-dark d-inline-block" style="width: 8px; height: 8px;"></span> Wed
                                </span>
                                <span>10:00am – 7:00pm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                    <span class="d-flex justify-content-center align-items-center bg-light rounded-3 shadow-sm mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold text-secondary small">03</span>
                    </span>

                    <h5 class="fw-semibold text-dark">Built to plug straight into growth motions</h5>

                    <p class="text-muted small mt-2">
                        Multiple aspect ratios, captions, and CTA variants so the video slots into paid campaigns, sales follow-ups, onboarding, and product tours on day one.
                    </p>

                    <div class="mt-auto d-flex justify-content-center pt-3">
                        <div class="position-relative border rounded-circle d-flex justify-content-center align-items-center" style="width: 160px; height: 160px;">
                            <span class="position-absolute bg-white px-2 py-1 small border rounded-pill shadow">
                                App
                            </span>

                            <span class="position-absolute top-0 translate-middle-x bg-white p-2 border rounded-circle small shadow">
                                📅
                            </span>
                            <span class="position-absolute start-0 top-50 translate-middle-y bg-white p-2 border rounded-circle small shadow">
                                🔄
                            </span>
                            <span class="position-absolute bottom-0 translate-middle-x bg-white p-2 border rounded-circle small shadow">
                                📆
                            </span>
                            <span class="position-absolute end-0 top-50 translate-middle-y bg-white p-2 border rounded-circle small shadow">
                                📈
                            </span>

                            <div class="text-center">
                                <svg width="88" height="88" viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="10" y="16" width="68" height="52" rx="12" stroke="black" stroke-width="2.5" />
                                    <rect x="22" y="30" width="44" height="30" rx="6" stroke="black" stroke-width="2.5" />
                                    <path d="M22 30h44" stroke="black" stroke-width="2.5" />
                                    <path d="M32 40h20" stroke="black" stroke-width="2.5" />
                                    <path d="M32 48h20" stroke="black" stroke-width="2.5" />
                                </svg>

                                <p class="fw-semibold small text-dark mt-2 mb-0">Storytelling built for conversions</p>
                                <p class="text-muted small mb-0">Every frame engineered to move buyers</p>
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
        <h2 class="text-center fw-semibold lh-sm text-dark mb-5" style="font-size: 2.5rem;">
            Every deliverable dialed in so you launch on-brand, on schedule
        </h2>

        <div class="row g-4 justify-content-center">
            <?php
            $icons = [
                '<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="10" y="18" width="44" height="28" rx="6"></rect><path d="M18 26h12"></path><path d="M18 34h8"></path><path d="M42 34h8"></path><path d="M38 22h14"></path><path d="M32 42l4 4 8-8"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="10" y="16" width="30" height="32" rx="6"></rect><path d="M26 28c0 3.866-3.134 7-7 7s-7-3.134-7-7 3.134-7 7-7 7 3.134 7 7z"></path><path d="M20 35v5"></path><path d="M40 26l12-7v26l-12-7"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M22 36l-6 6c-4.418 4.418-4.418 11.582 0 16s11.582 4.418 16 0l6-6"></path><path d="M42 28l6-6c4.418-4.418 4.418-11.582 0-16s-11.582-4.418-16 0l-6 6"></path><path d="M28 36l8-8"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M32 54c11 0 20-9 20-20V18l-20-8-20 8v16c0 11 9 20 20 20z"></path><path d="M26 32l4 4 8-8"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><circle cx="32" cy="32" r="22"></circle><path d="M10 32h44"></path><path d="M32 10c6 6 9 14 9 22s-3 16-9 22c-6-6-9-14-9-22s3-16 9-22z"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="12" y="14" width="40" height="30" rx="6"></rect><path d="M20 32h24"></path><path d="M28 24l-8 8 8 8"></path><path d="M36 40l8-8-8-8"></path><path d="M24 50h16"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><circle cx="20" cy="20" r="6"></circle><circle cx="44" cy="16" r="6"></circle><circle cx="48" cy="40" r="6"></circle><circle cx="22" cy="46" r="6"></circle><path d="M25 24l12 8"></path><path d="M26 40l16 0"></path><path d="M40 18l6 14"></path></svg>',
                '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M16 20h24c4.418 0 8 3.582 8 8s-3.582 8-8 8H16z"></path><path d="M32 28v-8"></path><path d="M32 44v-8"></path><path d="M16 44h24c4.418 0 8 3.582 8 8s-3.582 8-8 8H16z"></path></svg>',
            ];

            $labels = [
                'Messaging & value prop workshop',
                'Storyboards built with your product UI',
                'Voiceover cast to match your tone',
                'Custom illustration & motion system',
                'Captions + multi-language subtitles',
                'Cut-downs for ads, socials, and email',
                'Hooks & CTAs tuned for performance',
                'Launch checklist and organized handoff'
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
            Client results
        </span>

        <h2 class="fw-semibold lh-sm text-dark" style="font-size: 2.5rem;">
            Brands turning clarity into revenue
        </h2>

        <p class="mt-3 text-secondary small mx-auto" style="max-width: 620px;">
            Explainers that disarm objections, earn trust, and speed up buying decisions—so teams see more demos booked, smoother onboarding, and higher close rates.
        </p>

        <div class="d-none d-md-flex justify-content-center align-items-center gap-4 mt-5">
            <div class="bg-white border rounded-4 shadow-sm p-4" style="width: 350px; height: 210px; opacity: 0.35; transform: scale(0.94);">
                <p class="text-dark small fst-italic mb-4">
                    “They translated our platform into a story prospects finally understood. Demo requests doubled in two weeks.”
                </p>

                <div class="d-flex align-items-center">
                    <svg width="36" height="36" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="20" cy="20" r="18" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="20" y="22" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="black">N</text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1">Noah Patel</p>
                        <p class="text-muted small m-0">Marketing Ops Lead, Horizon Creative</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-4 shadow-sm p-4" style="width: 420px; height: 230px;">
                <p class="text-dark fw-semibold mb-4">
                    “Sales now opens every pitch with the video. Calls start with context, objections drop, and close rates are up 38%.”
                </p>

                <div class="d-flex align-items-center">
                    <svg width="40" height="40" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="22" cy="22" r="20" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="22" y="24" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="black">M</text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1">Melissa Grant</p>
                        <p class="text-muted small m-0">VP Growth, Northwind Legal</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-4 shadow-sm p-4" style="width: 350px; height: 210px; opacity: 0.35; transform: scale(0.94);">
                <p class="text-dark small fst-italic mb-4">
                    “We dropped the video into onboarding and support tickets fell by 27%. Customers finally grasp the product in minutes.”
                </p>

                <div class="d-flex align-items-center">
                    <svg width="36" height="36" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="20" cy="20" r="18" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="20" y="22" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="black">S</text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1">Sofia Alvarez</p>
                        <p class="text-muted small m-0">Head of Growth, Latitude Labs</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-md-none mx-auto mt-4" style="max-width: 380px;">
            <div class="bg-white border rounded-4 shadow-sm p-4">
                <p class="text-dark fw-semibold mb-4">
                    “Sales now opens every pitch with the video. Calls start with context, objections drop, and close rates are up 38%.”
                </p>

                <div class="d-flex align-items-center">
                    <svg width="36" height="36" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="20" cy="20" r="18" fill="white" stroke="black" stroke-width="2"></circle>
                        <text x="20" y="22" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="black">M</text>
                    </svg>
                    <div class="ms-3 text-start">
                        <p class="fw-semibold text-dark small mb-1">Melissa Grant</p>
                        <p class="text-muted small m-0">VP Growth, Northwind Legal</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="bg-white border rounded-4 shadow-sm p-4 p-md-5 row g-4 align-items-center">
            <div class="col-md-5 d-flex flex-column">
                <span class="d-inline-flex align-items-center bg-white border px-3 py-1 rounded-pill text-secondary small shadow-sm mb-3">
                    Production roadmap
                </span>

                <h2 class="fw-semibold lh-sm text-dark mb-3" style="font-size: 2.25rem;">
                    Guided from kickoff to launch.<br>Every deliverable handled for you.
                </h2>

                <p class="text-muted small mb-4" style="max-width: 420px;">
                    Strategy call, scripting, storyboard, motion design, voiceover, revisions, and multi-format delivery—managed by one team so you launch confidently and on time.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg shadow-sm">Get my proposal →</a>
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-light border btn-lg">See the roadmap →</a>
                </div>
            </div>

            <div class="col-md-7">
                <div class="border rounded-3 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-4 d-flex justify-content-center align-items-center border-end border-bottom p-4" style="height:120px;">
                            <img src="<?php echo esc_url( $theme_dir . '/images/payment_methods/visa.svg' ); ?>" class="opacity-75" width="40" alt="Slack">
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center border-end border-bottom p-4" style="height:120px;">
                            <img src="<?php echo esc_url( $theme_dir . '/images/payment_methods/master_card.svg' ); ?>" class="opacity-75" width="80" alt="Zapier">
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center border-bottom p-4" style="height:120px;">
                            <img src="<?php echo esc_url( $theme_dir . '/images/payment_methods/american_express.svg' ); ?>" class="opacity-75" width="55" alt="Stripe">
                        </div>
                    </div>

                    <div class="row g-0">
                        <div class="col-4 d-flex justify-content-center align-items-center border-end p-4" style="height:120px;">
                            <img src="<?php echo esc_url( $theme_dir . '/images/payment_methods/discover.svg' ); ?>" class="opacity-75" width="60" alt="HubSpot">
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center border-end p-4" style="height:120px;">
                            <img src="<?php echo esc_url( $theme_dir . '/images/payment_methods/paypal.svg' ); ?>" class="opacity-75" width="55" alt="Outlook">
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center p-4" style="height:120px;">
                            <img src="<?php echo esc_url( $theme_dir . '/images/payment_methods/visa.svg' ); ?>" class="opacity-75" width="40" alt="Slack">
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
                    Ready for an explainer that closes the deal for you?
                    <br class="d-none d-sm-block">Reserve your production slot before this month fills up.
                </h2>

                <div class="mt-4">
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-dark btn-lg shadow-sm">
                        Reserve my explainer →
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
                Start my explainer
            </a>
            <a href="/contact-us" class="btn btn-light border btn-lg w-100 text-dark">
                Chat with a producer
            </a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
