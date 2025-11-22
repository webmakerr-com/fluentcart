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

                            <div class="fct-review-highlight my-3"></div>

                            <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const container = document.querySelector('.fct-review-highlight');
                                if (!container) return;

                                const cards = [
                                    {
                                        name: "Sofia R.",
                                        avatar: "https://i.pravatar.cc/80?img=12",
                                        rating: "5.0",
                                        comment: "Outstanding experience. Clear communication and flawless delivery."
                                    },
                                    {
                                        name: "Daniel K.",
                                        avatar: "https://i.pravatar.cc/80?img=32",
                                        rating: "4.8",
                                        comment: "Fast delivery and thoughtful revisions. Very professional."
                                    },
                                    {
                                        name: "Maya L.",
                                        avatar: "https://i.pravatar.cc/80?img=47",
                                        rating: "5.0",
                                        comment: "Great partner for our campaign. Detail-oriented and reliable."
                                    }
                                ];

                                let index = 0;

                                function renderCard() {
                                    const c = cards[index];
                                    container.innerHTML = `
                                        <div class="card border-0 shadow-sm p-3">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <img src="${c.avatar}" width="56" height="56" class="rounded-circle shadow-sm">
                                                <div>
                                                    <div class="fw-semibold text-dark">${c.name}</div>
                                                    <div class="text-warning small">&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-muted ms-1">${c.rating}</span></div>
                                                </div>
                                            </div>
                                            <p class="mb-0 text-muted">${c.comment}</p>
                                        </div>
                                    `;
                                }

                                renderCard();
                                setInterval(() => {
                                    index = (index + 1) % cards.length;
                                    renderCard();
                                }, 3000);
                            });
                            </script>
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
