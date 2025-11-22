<?php
/**
 * Template Name: Contact Thank You
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

$theme_dir   = get_template_directory_uri();
$booking_url = 'https://webmakerr.com/?booking=calendar&host=webmakerr&event=30min';

get_header();
?>

<style>
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

    .thankyou-ambient {
        background:
            radial-gradient(circle at 20% 20%, rgba(96, 165, 250, 0.08), transparent 45%),
            radial-gradient(circle at 80% 0%, rgba(167, 139, 250, 0.08), transparent 35%),
            radial-gradient(circle at 50% 80%, rgba(16, 185, 129, 0.08), transparent 40%);
    }

    .thankyou-card,
    .thankyou-step-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
    }

    .thankyou-card {
        padding: clamp(24px, 3vw, 36px);
    }

    .thankyou-card h1 {
        font-size: clamp(1.8rem, 1.4rem + 1vw, 2.4rem);
        margin-bottom: 0.35rem;
    }

    .thankyou-card p {
        color: #475569;
        margin-bottom: 1.25rem;
        max-width: 520px;
    }

    .thankyou-step-card {
        padding: 18px 20px;
        height: 100%;
    }

    @media (min-width: 992px) {
        .thankyou-step-card {
            padding: 22px 24px;
        }
    }

    .thankyou-step-icon {
        width: 44px;
        height: 44px;
        border-radius: 4px;
        background: linear-gradient(135deg, #f9fafb, #eef2f7);
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .thankyou-step-icon svg {
        width: 22px;
        height: 22px;
        stroke: #0f172a;
    }

    @media (max-width: 575.98px) {
        .thankyou-primary-actions {
            justify-content: center;
            text-align: center;
        }

        .thankyou-primary-actions .btn {
            margin-left: auto;
            margin-right: auto;
        }

        .thankyou-primary-actions .text-muted {
            width: 100%;
        }
    }

    .thankyou-header {
        gap: 12px;
    }

    .thankyou-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        color: #0f172a;
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
</style>

<div class="thankyou-ambient py-5 py-lg-6">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="thankyou-card h-100 d-flex flex-column justify-content-center">
                    <div class="position-relative border rounded-3 p-3 shadow-sm hero-animation-shell mb-3">
                        <div class="ratio ratio-16x9 w-100">
                            <video
                                class="w-100 h-100 rounded-3"
                                data-src="https://cdn.webmakerr.com/website/thank-you.mp4"
                                autoplay
                                muted
                                playsinline
                                loop
                                controls
                                preload="none"
                            ></video>
                        </div>
                    </div>
                    <h1 class="fw-bold"><?php esc_html_e('We received your message', 'rap'); ?></h1>
                    <p class="mb-0">
                        <?php esc_html_e('Thanks for reaching out. Our team is reviewing your note and will reply shortly with next steps tailored to your request.', 'rap'); ?>
                    </p>
                    <div class="mt-4 d-flex flex-wrap align-items-center gap-2 thankyou-primary-actions">
                        <a class="btn btn-dark btn-lg fw-semibold" href="<?php echo esc_url($booking_url); ?>">
                            <?php esc_html_e('Book an appointment', 'rap'); ?>
                        </a>
                        <span class="text-muted small ms-1"><?php esc_html_e('Secure a time that works for you.', 'rap'); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-3 h-100">
                    <div class="d-flex align-items-center thankyou-header flex-wrap">
                        <div class="thankyou-step-icon me-2 me-lg-3">
                            <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="m9 12.75 2 2 4-4.5M12 3 3 7v10l9 4 9-4V7l-9-4Z" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-uppercase text-muted small mb-0 fw-semibold"><?php esc_html_e('What happens next?', 'rap'); ?></p>
                            <h3 class="h5 mb-0 fw-bold"><?php esc_html_e('A quick, organized follow-up', 'rap'); ?></h3>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="thankyou-step-card">
                                <div class="thankyou-step-icon">
                                    <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 9V4.5m0 4.5 5-5m-5 5-5-5M6.75 15l-2.25 3M17.25 15l2.25 3M9 20l3-8 3 8M4 19.5h16" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-bold mb-2"><?php esc_html_e('We confirm the details', 'rap'); ?></h4>
                                <p class="mb-0 text-secondary">
                                    <?php esc_html_e('A specialist reviews your message to understand your goals, timeline, and any resources you shared.', 'rap'); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="thankyou-step-card">
                                <div class="thankyou-step-icon">
                                    <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M3 8.5c0-2 1.792-3.5 4-3.5h10c2.208 0 4 1.5 4 3.5v7c0 2-1.792 3.5-4 3.5H7c-2.208 0-4-1.5-4-3.5v-7Zm12.5 5.167L19 16.5m-7-4.833L5 16.5" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-bold mb-2"><?php esc_html_e('We respond quickly', 'rap'); ?></h4>
                                <p class="mb-0 text-secondary">
                                    <?php esc_html_e('Expect a prompt reply with answers, clarifying questions, or the best next step to keep things moving.', 'rap'); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="thankyou-step-card">
                                <div class="thankyou-step-icon">
                                    <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M8.5 6h-2A1.5 1.5 0 0 0 5 7.5v9A1.5 1.5 0 0 0 6.5 18h11A1.5 1.5 0 0 0 19 16.5v-9A1.5 1.5 0 0 0 17.5 6h-2l-.879-1.757A1 1 0 0 0 13.723 4h-3.446a1 1 0 0 0-.898.243L8.5 6Z" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 10.5h2.5m3 0H16M8 13.5h4.5" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-bold mb-2"><?php esc_html_e('You can book now', 'rap'); ?></h4>
                                <p class="mb-3 text-secondary">
                                    <?php esc_html_e('Pick a slot on our calendar to secure time. We will come prepared with tailored recommendations.', 'rap'); ?>
                                </p>
                                <a class="btn btn-outline-dark fw-semibold" href="<?php echo esc_url($booking_url); ?>">
                                    <?php esc_html_e('Book an appointment', 'rap'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<?php get_footer(); ?>
