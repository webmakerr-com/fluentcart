<?php
/**
 * FluentCart Single Product Template
 */

if (!defined('ABSPATH')) {
    exit;
}

use FluentCart\Api\Resource\ShopResource;
use FluentCart\Api\StoreSettings;
use FluentCart\App\Modules\Data\ProductDataSetup;
use FluentCart\App\Services\Renderer\ProductListRenderer;
use FluentCart\App\Services\Renderer\ProductRenderer;
use FluentCart\Framework\Support\Arr;

global $post;

$product = $GLOBALS['fct_product'] ?? null;

if (!$product && $post instanceof WP_Post) {
    $product = ProductDataSetup::getProductModel($post->ID);
}

if (!$product || !$product->detail) {
    the_content();
    return;
}

$storeSettings = new StoreSettings();

$renderer = new ProductRenderer($product, [
    'view_type'   => $storeSettings->get('variation_view', 'both'),
    'column_type' => $storeSettings->get('variation_columns', 'masonry'),
]);

$description = '';
if ($post instanceof WP_Post) {
    $description = apply_filters('the_content', $post->post_content);
}

$reviewHighlights = [
    [
        'name'   => 'Sofia R.',
        'avatar' => 'https://i.pravatar.cc/80?img=12',
        'rating' => '&#9733;&#9733;&#9733;&#9733;&#9733;',
        'score'  => '5.0',
        'text'   => __('Outstanding experience. Clear communication from start to finish and the final delivery exceeded our brand standards.', 'fluent-cart'),
    ],
    [
        'name'   => 'Daniel K.',
        'avatar' => 'https://i.pravatar.cc/80?img=32',
        'rating' => '&#9733;&#9733;&#9733;&#9733;&#9734;',
        'score'  => '4.8',
        'text'   => __('Fast delivery and thoughtful revisions. The process felt like working with an in-house pro.', 'fluent-cart'),
    ],
    [
        'name'   => 'Maya L.',
        'avatar' => 'https://i.pravatar.cc/80?img=47',
        'rating' => '&#9733;&#9733;&#9733;&#9733;&#9733;',
        'score'  => '5.0',
        'text'   => __('Detail-oriented, proactive, and truly invested in our goals—perfect partner for our launch campaign.', 'fluent-cart'),
    ],
    [
        'name'   => 'Liam T.',
        'avatar' => 'https://i.pravatar.cc/80?img=24',
        'rating' => '&#9733;&#9733;&#9733;&#9733;&#9733;',
        'score'  => '5.0',
        'text'   => __('Communication was effortless and the results matched our brief perfectly. Highly recommend.', 'fluent-cart'),
    ],
    [
        'name'   => 'Priya S.',
        'avatar' => 'https://i.pravatar.cc/80?img=18',
        'rating' => '&#9733;&#9733;&#9733;&#9733;&#9734;',
        'score'  => '4.9',
        'text'   => __('Dependable partner for every sprint—keeps us on timeline and on brand every single time.', 'fluent-cart'),
    ],
];

$initialHighlightReview = $reviewHighlights[0];
?>
<div class="fc-single-product-page fc-single-product-page--fiverr" data-fluent-cart-single-product-page>
    <div class="fc-container py-4">
        <div class="row g-4">
            <div class="col-lg-7 col-xl-8">
                <div class="d-flex flex-column gap-4">
                    <section class="card shadow-sm">
                        <div class="card-body d-flex flex-column gap-4">
                            <div class="border rounded-3 overflow-hidden">
                                <?php $renderer->renderGallery(['thumb_position' => 'bottom']); ?>
                            </div>

                            <?php $renderer->renderTitle(); ?>

                            <div class="card shadow-sm border-0 bg-light" data-fluent-cart-review-highlight>
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="text-uppercase text-muted small fw-semibold"><?php esc_html_e('Reviews Highlight', 'fluent-cart'); ?></div>
                                        <div class="text-muted small"><?php esc_html_e('Real client wins on FluentCart', 'fluent-cart'); ?></div>
                                    </div>
                                    <div class="fct-review-highlight-card d-flex align-items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <img src="<?php echo esc_url($initialHighlightReview['avatar']); ?>" alt="<?php echo esc_attr($initialHighlightReview['name']); ?>" class="rounded-circle shadow-sm" width="56" height="56" data-review-avatar />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div class="fw-semibold text-dark" data-review-name><?php echo esc_html($initialHighlightReview['name']); ?></div>
                                                <div class="text-warning small" data-review-rating><?php echo wp_kses_post($initialHighlightReview['rating']); ?><span class="text-muted ms-1"><?php echo esc_html($initialHighlightReview['score']); ?></span></div>
                                            </div>
                                            <p class="mb-0 text-muted" data-review-text><?php echo esc_html($initialHighlightReview['text']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="h6 text-uppercase text-muted mb-3"><?php esc_html_e('Description', 'fluent-cart'); ?></h2>
                            <div class="fc-product-description">
                                <?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                        </div>
                    </section>

                    <section class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="h6 text-uppercase text-muted mb-3"><?php esc_html_e('Features', 'fluent-cart'); ?></h2>
                            <?php $renderer->renderFeatureList(); ?>
                        </div>
                    </section>

                    <section class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h2 class="h6 text-uppercase text-muted mb-0"><?php esc_html_e('FAQ', 'fluent-cart'); ?></h2>
                                <span class="text-muted small"><?php esc_html_e('Answers to common questions', 'fluent-cart'); ?></span>
                            </div>
                            <?php $renderer->renderFaqSection(); ?>
                        </div>
                    </section>

                    <section class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h2 class="h6 text-uppercase text-muted mb-0"><?php esc_html_e('Reviews', 'fluent-cart'); ?></h2>
                            </div>
                            <?php comments_template(); ?>
                        </div>
                    </section>
                </div>
            </div>

            <aside class="col-lg-5 col-xl-4">
                <div class="position-sticky" style="top: 90px;">
                    <div class="fc-product-summary-card card shadow-sm" id="fc-product-summary" data-fluent-cart-sticky-summary data-fluent-cart-product-summary>
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="text-muted small mb-1"><?php esc_html_e('Packages from', 'fluent-cart'); ?></div>
                                    <?php $renderer->renderPrices(); ?>
                                </div>
                                <div class="text-end small">
                                    <?php $renderer->renderStockAvailability('class="text-success fw-semibold"'); ?>
                                </div>
                            </div>
                            <?php $renderer->renderExcerpt(); ?>
                            <?php $renderer->renderBuySection(); ?>
                            <div class="text-muted small d-flex align-items-center gap-2">
                                <span class="text-success lh-1">&#10003;</span>
                                <span><?php esc_html_e('Fast delivery and satisfaction guarantee included.', 'fluent-cart'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <?php
    $relatedProducts = ShopResource::getSimilarProducts($product->ID, false);
    $relatedList = $relatedProducts ? Arr::get($relatedProducts, 'products') : null;
    if ($relatedList && $relatedList->count()) :
        ob_start();
        (new ProductListRenderer($relatedList, __('Related Products', 'fluent-cart'), 'fc-similar-product-list-container fc-product-section--related'))->render();
        $relatedMarkup = ob_get_clean();
        ?>
        <div class="fc-container mt-4">
            <section class="fc-product-section fc-product-section--related" id="fc-product-related">
                <?php echo $relatedMarkup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </section>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const highlight = document.querySelector('[data-fluent-cart-review-highlight]');
    if (!highlight) {
        return;
    }

    const reviewData = <?php echo wp_json_encode($reviewHighlights); ?>;

    if (!Array.isArray(reviewData) || !reviewData.length) {
        return;
    }

    const card = highlight.querySelector('.fct-review-highlight-card');
    const nameEl = highlight.querySelector('[data-review-name]');
    const ratingEl = highlight.querySelector('[data-review-rating]');
    const textEl = highlight.querySelector('[data-review-text]');
    const avatarEl = highlight.querySelector('[data-review-avatar]');

    if (!card || !nameEl || !ratingEl || !textEl || !avatarEl) {
        return;
    }

    const setReview = (review) => {
        nameEl.textContent = review.name;
        ratingEl.innerHTML = review.rating + '<span class="text-muted ms-1">' + review.score + '</span>';
        textEl.textContent = review.text;
        avatarEl.src = review.avatar;
        avatarEl.alt = review.name;
    };

    const minHeight = card.offsetHeight;
    if (minHeight) {
        card.style.minHeight = minHeight + 'px';
    }
    card.style.transition = 'opacity 0.35s ease';

    let index = 0;
    setReview(reviewData[index]);

    if (reviewData.length > 1) {
        setInterval(() => {
            index = (index + 1) % reviewData.length;
            card.style.opacity = '0';
            setTimeout(() => {
                setReview(reviewData[index]);
                card.style.opacity = '1';
            }, 250);
        }, 3000);
    }
});
</script>
