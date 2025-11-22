<?php
/**
 * Template Name: Legal Portal
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
    :root {
        --legal-green: #00b22d;
        --legal-dark: #222325;
        --legal-border: #e5e7eb;
        --legal-muted: #6a6f75;
        --legal-soft: #f5f7fa;
        --bs-border-radius: 8px;
    }

    body.page-template-legal-portal {
        background: #ffffff;
        color: var(--legal-dark);
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    .legal-shell {
        padding: 64px 0 96px;
        background: linear-gradient(180deg, #fbfbfc 0%, #ffffff 12%);
    }

    .legal-hero {
        background: #0b3b2e;
        color: #fff;
        border-radius: 16px;
        padding: 56px 48px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.16);
    }

    .legal-hero:after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(0, 178, 45, 0.18) 0%, rgba(11, 59, 46, 0) 70%);
        right: -80px;
        bottom: -120px;
    }

    .legal-hero h1 {
        font-size: 2.6rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .legal-hero .lead {
        max-width: 780px;
        color: #e2ebe4;
        margin-bottom: 24px;
    }

    .legal-hero .hero-actions .btn {
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 700;
    }

    .hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: #e8f2ec;
        font-weight: 700;
    }

    .search-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--legal-border);
        box-shadow: 0 10px 30px rgba(18, 38, 63, 0.06);
        margin-top: -28px;
        position: relative;
        z-index: 2;
    }

    .search-card .form-control {
        padding: 12px 14px;
        border-radius: 10px;
        border-color: var(--legal-border);
    }

    .search-card .btn {
        border-radius: 10px;
        padding: 12px 16px;
    }

    .section-heading {
        font-weight: 800;
        font-size: 1.25rem;
    }

    .legal-card {
        border: 1px solid var(--legal-border);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 12px 30px rgba(18, 38, 63, 0.05);
    }

    .legal-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--legal-border);
        padding: 20px 24px 14px;
    }

    .legal-card .card-body {
        padding: 18px 24px 24px;
    }

    .bullet-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 12px;
    }

    .bullet-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--legal-border);
    }

    .bullet-list li:last-child {
        border-bottom: none;
    }

    .bullet-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--legal-green);
        box-shadow: 0 0 0 6px rgba(0, 178, 45, 0.12);
    }

    .legal-link {
        color: var(--legal-dark);
        font-weight: 700;
        text-decoration: none;
    }

    .legal-link:hover {
        color: var(--legal-green);
    }

    .policy-chip {
        background: var(--legal-soft);
        color: var(--legal-dark);
        border: 1px solid var(--legal-border);
        border-radius: 999px;
        padding: 8px 12px;
        font-weight: 700;
    }

    .resources-grid {
        display: grid;
        gap: 16px;
    }

    .resources-grid .item {
        border: 1px solid var(--legal-border);
        border-radius: 12px;
        padding: 16px;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .cta-banner {
        background: #0e0f10;
        color: #fff;
        border-radius: 14px;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }

    .cta-banner:after {
        content: '';
        position: absolute;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(0, 178, 45, 0.18) 0%, rgba(14, 15, 16, 0) 70%);
        right: -60px;
        top: -60px;
    }

    .cta-banner h4 {
        font-weight: 800;
    }

    .cta-banner .btn {
        border-radius: 10px;
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .legal-hero {
            padding: 36px 28px;
        }

        .legal-hero h1 {
            font-size: 2.2rem;
        }

        .search-card {
            margin-top: -18px;
        }
    }
</style>

<div class="legal-shell page-template-legal-portal">
    <div class="container">
        <div class="legal-hero mb-4">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="hero-chip">Legal Portal</span>
                <span class="hero-chip" style="background: rgba(0, 178, 45, 0.14); color: #c1f0d0;">Updated weekly</span>
            </div>
            <h1 class="mb-3">Policies, compliance, and trust resources</h1>
            <p class="lead mb-4">Find all Fiverr-style legal, compliance, and security documents for Webmakerr. Get the latest updates on privacy, payments, and platform integrity in one place.</p>
            <div class="d-flex flex-wrap gap-2 hero-actions">
                <a class="btn btn-success" href="mailto:legal@webmakerr.com">Contact legal</a>
                <a class="btn btn-outline-light" href="https://cal.com/webmakerr/legal" target="_blank" rel="noopener">Book a call</a>
                <span class="badge bg-light text-dark rounded-pill d-inline-flex align-items-center gap-2"><span class="badge bg-success rounded-pill">Live</span> Response within 1 business day</span>
            </div>
        </div>

        <div class="search-card mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 text-success fw-bold">Search</div>
                        <div>
                            <h5 class="mb-1 fw-bold">Search legal topics</h5>
                            <small class="text-muted">Terms of Service, payments, privacy, IP, subprocessors, and more.</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <form class="d-flex gap-2" action="https://webmakerr.com/search" method="get">
                        <input class="form-control" type="search" name="q" placeholder="Try &quot;Data Processing Agreement&quot;" required>
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="legal-card h-100">
                    <div class="card-header">
                        <p class="text-uppercase text-muted fw-bold mb-1" style="letter-spacing:0.06em;">Policies &amp; notices</p>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <h4 class="section-heading mb-0">Core documents</h4>
                            <span class="policy-chip">Updated</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="resources-grid">
                                    <div class="item">
                                        <div>
                                            <p class="fw-bold mb-1">Terms of Service</p>
                                            <small class="text-muted">Marketplace rules, payments, account use.</small>
                                        </div>
                                        <a class="legal-link" href="https://webmakerr.com/terms-of-service" target="_blank" rel="noopener">View</a>
                                    </div>
                                    <div class="item">
                                        <div>
                                            <p class="fw-bold mb-1">Privacy Policy</p>
                                            <small class="text-muted">Data handling, cookies, international transfers.</small>
                                        </div>
                                        <a class="legal-link" href="https://webmakerr.com/privacy-policy" target="_blank" rel="noopener">Read</a>
                                    </div>
                                    <div class="item">
                                        <div>
                                            <p class="fw-bold mb-1">Payments &amp; billing</p>
                                            <small class="text-muted">Refunds, deposits, payout cadence.</small>
                                        </div>
                                        <a class="legal-link" href="https://webmakerr.com/terms-of-service#payments" target="_blank" rel="noopener">Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="resources-grid">
                                    <div class="item">
                                        <div>
                                            <p class="fw-bold mb-1">Data Processing Agreement</p>
                                            <small class="text-muted">SCCs, subprocessors, and GDPR safeguards.</small>
                                        </div>
                                        <a class="legal-link" href="https://webmakerr.com/dpa" target="_blank" rel="noopener">Download</a>
                                    </div>
                                    <div class="item">
                                        <div>
                                            <p class="fw-bold mb-1">Subprocessors</p>
                                            <small class="text-muted">Current vendors and notification cadence.</small>
                                        </div>
                                        <a class="legal-link" href="https://webmakerr.com/subprocessors" target="_blank" rel="noopener">See list</a>
                                    </div>
                                    <div class="item">
                                        <div>
                                            <p class="fw-bold mb-1">Security overview</p>
                                            <small class="text-muted">Infrastructure, encryption, certifications.</small>
                                        </div>
                                        <a class="legal-link" href="https://webmakerr.com/security" target="_blank" rel="noopener">Overview</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cta-banner h-100">
                    <p class="text-uppercase text-muted mb-1" style="letter-spacing:0.08em;">Need an agreement?</p>
                    <h4 class="mb-2">MSAs, NDAs &amp; vendor forms</h4>
                    <p class="mb-3">Request signatures, security questionnaires, or procurement forms. Response in one business day.</p>
                    <div class="d-grid gap-2">
                        <a class="btn btn-success" href="mailto:legal@webmakerr.com">Contact legal team</a>
                        <a class="btn btn-outline-light" href="https://cal.com/webmakerr/legal" target="_blank" rel="noopener">Book a call</a>
                        <small class="text-muted">Coverage: EU, UK, US</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="legal-card h-100">
                    <div class="card-header">
                        <p class="text-uppercase text-muted fw-bold mb-1" style="letter-spacing:0.06em;">Compliance</p>
                        <h4 class="section-heading mb-0">Transparency library</h4>
                    </div>
                    <div class="card-body">
                        <ul class="bullet-list">
                            <li>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bullet-dot"></span>
                                    <div>
                                        <p class="fw-bold mb-0">Responsible disclosure</p>
                                        <small class="text-muted">Report vulnerabilities to our security team.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/security#vdp" target="_blank" rel="noopener">Report</a>
                            </li>
                            <li>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bullet-dot"></span>
                                    <div>
                                        <p class="fw-bold mb-0">Data subject rights</p>
                                        <small class="text-muted">Submit DSAR and deletion requests.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/privacy-policy#rights" target="_blank" rel="noopener">Submit</a>
                            </li>
                            <li>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bullet-dot"></span>
                                    <div>
                                        <p class="fw-bold mb-0">Platform integrity</p>
                                        <small class="text-muted">Content moderation and enforcement policies.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/legal#integrity" target="_blank" rel="noopener">View</a>
                            </li>
                            <li>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bullet-dot"></span>
                                    <div>
                                        <p class="fw-bold mb-0">Trademark &amp; IP</p>
                                        <small class="text-muted">Brand usage and IP protection requests.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/legal#ip" target="_blank" rel="noopener">Learn</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="legal-card h-100">
                    <div class="card-header">
                        <p class="text-uppercase text-muted fw-bold mb-1" style="letter-spacing:0.06em;">Guides &amp; templates</p>
                        <h4 class="section-heading mb-0">Procurement ready</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-success bg-opacity-10 rounded-3 text-success fw-bold">FAQ</div>
                                    <div>
                                        <p class="fw-bold mb-0">Compliance FAQ</p>
                                        <small class="text-muted">Answers to common vendor review questions.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/legal#faq" target="_blank" rel="noopener">View</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-success bg-opacity-10 rounded-3 text-success fw-bold">SOC</div>
                                    <div>
                                        <p class="fw-bold mb-0">Security posture</p>
                                        <small class="text-muted">Availability, business continuity, and SOC alignment.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/security" target="_blank" rel="noopener">Overview</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-success bg-opacity-10 rounded-3 text-success fw-bold">IP</div>
                                    <div>
                                        <p class="fw-bold mb-0">DMCA &amp; brand use</p>
                                        <small class="text-muted">Report misuse or request brand permissions.</small>
                                    </div>
                                </div>
                                <a class="legal-link" href="https://webmakerr.com/legal#ip" target="_blank" rel="noopener">Learn</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="legal-card p-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <p class="text-uppercase text-muted fw-bold mb-1" style="letter-spacing:0.06em;">Trust center</p>
                    <h4 class="section-heading mb-2">Stay informed</h4>
                    <p class="mb-3">Subscribe to receive updates when we refresh policies, add subprocessors, or publish new security resources.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="policy-chip">Change logs</span>
                        <span class="policy-chip">Incident response</span>
                        <span class="policy-chip">Status</span>
                        <span class="policy-chip">Data residency</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border p-3 rounded-3 bg-light h-100">
                        <form action="https://webmakerr.com/newsletter" method="get" class="d-flex flex-column gap-2">
                            <label class="fw-bold">Get email updates</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <input class="form-control" type="email" name="email" placeholder="you@company.com" required>
                                <button class="btn btn-success" type="submit">Notify me</button>
                            </div>
                            <small class="text-muted">We only email for material legal changes.</small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
