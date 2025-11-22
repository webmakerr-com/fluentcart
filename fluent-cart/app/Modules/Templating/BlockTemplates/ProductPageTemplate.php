<?php

namespace FluentCart\App\Modules\Templating\BlockTemplates;

/**
 * ProductCategoryTemplate class.
 *
 */
class ProductPageTemplate
{

    /**
     * The slug of the template.
     *
     * @var string
     */
    const SLUG = 'single-fluent-products';

    /**
     * Initialization method.
     */
    public function init()
    {
        register_block_template('fluent-cart//single-fluent-products', [
            'title'       => $this->getTitle(),
            'description' => $this->getDescription(),
            'content'     => $this->getDefaultTemplate(),
        ]);
    }

    /**
     * Returns the title of the template.
     *
     * @return string
     */
    public function getTitle()
    {
        return _x('Single Product', 'Template name', 'fluent-cart');
    }

    /**
     * Returns the description of the template.
     *
     * @return string
     */
    public function getDescription()
    {
        return __('Displays Single FluentCart Product.', 'fluent-cart');
    }


    public function getDefaultTemplate()
    {
        ob_start();
        ?>


        <!-- wp:template-part {"slug":"header","tagName":"header","area":"header"} /-->

        <!-- wp:group {"tagName":"main","align":"full","className":"is-style-default","layout":{"type":"constrained"}} -->
        <main class="wp-block-group alignfull is-style-default"><!-- wp:fluent-cart/product-info -->
            <div class="wp-block-fluent-cart-product-info fct-gig-layout"><!-- wp:group {"align":"full","className":"fct-gig-gallery","layout":{"type":"default"}} -->
                <div class="wp-block-group alignfull fct-gig-gallery"><div class="container-fluid px-0"><!-- wp:fluent-cart/product-gallery {"inside_product_info":"yes"} /--></div></div>
                <!-- /wp:group -->

                <!-- wp:group {"align":"full","className":"fct-gig-body","layout":{"type":"default"}} -->
                <div class="wp-block-group alignfull fct-gig-body"><div class="container">
                        <div class="row gy-4">
                            <div class="col-lg-8 order-2 order-lg-1">
                                <div class="mb-3">
                                    <!-- wp:post-title {"level":1} /-->
                                    <!-- wp:fluent-cart/stock /-->
                                </div>

                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-light border fct-seller-avatar" style="width:56px;height:56px;"></div>
                                            <div>
                                                <p class="mb-0 fw-semibold">Seller Name</p>
                                                <small class="text-muted">Seller headline placeholder</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success">Online</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold">★</span>
                                        <span class="fw-semibold">4.9</span>
                                        <small class="text-muted">(rating summary)</small>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Order completion placeholder</div>
                                </div>

                                <div class="mb-4">
                                    <!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false} /-->
                                </div>

                                <div class="mb-5">
                                    <h3 class="h5 mb-3">Description</h3>
                                    <!-- wp:post-content {"align":"wide","lock":{"move":false,"remove":false},"layout":{"type":"default"}} /-->
                                </div>

                                <div class="mb-5">
                                    <h3 class="h5 mb-3">What you get</h3>
                                    <ul class="list-unstyled vstack gap-2">
                                        <li class="d-flex align-items-start gap-2"><span class="text-success">•</span><span>Feature highlight placeholder</span></li>
                                        <li class="d-flex align-items-start gap-2"><span class="text-success">•</span><span>Deliverable placeholder</span></li>
                                        <li class="d-flex align-items-start gap-2"><span class="text-success">•</span><span>Support line placeholder</span></li>
                                    </ul>
                                </div>

                                <div class="mb-5">
                                    <h3 class="h5 mb-3">FAQ</h3>
                                    <div class="accordion" id="fct-product-faq">
                                        <!-- wp:shortcode -->[fluent_cart_product_faq]<!-- /wp:shortcode -->
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <ul class="nav nav-tabs" id="fct-gig-tabs" role="tablist">
                                        <li class="nav-item" role="presentation"><button class="nav-link active" id="fct-tab-overview-tab" data-bs-toggle="tab" data-bs-target="#fct-tab-overview" type="button" role="tab" aria-controls="fct-tab-overview" aria-selected="true">Overview</button></li>
                                        <li class="nav-item" role="presentation"><button class="nav-link" id="fct-tab-seller-tab" data-bs-toggle="tab" data-bs-target="#fct-tab-seller" type="button" role="tab" aria-controls="fct-tab-seller" aria-selected="false">About the Seller</button></li>
                                        <li class="nav-item" role="presentation"><button class="nav-link" id="fct-tab-reviews-tab" data-bs-toggle="tab" data-bs-target="#fct-tab-reviews" type="button" role="tab" aria-controls="fct-tab-reviews" aria-selected="false">Reviews</button></li>
                                    </ul>
                                    <div class="tab-content pt-4" id="fct-gig-tab-content">
                                        <div class="tab-pane fade show active" id="fct-tab-overview" role="tabpanel" aria-labelledby="fct-tab-overview-tab">
                                            <div class="vstack gap-3">
                                                <p class="mb-0">Detailed overview of the service, deliverables, and expectations.</p>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="fct-tab-seller" role="tabpanel" aria-labelledby="fct-tab-seller-tab">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        <div class="rounded-circle bg-light border fct-seller-avatar" style="width:56px;height:56px;"></div>
                                                        <div>
                                                            <p class="mb-0 fw-semibold">Seller Name</p>
                                                            <small class="text-muted">Seller headline placeholder</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0 text-muted">Use this area to highlight seller expertise, response time, and other Fiverr-style details.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="fct-tab-reviews" role="tabpanel" aria-labelledby="fct-tab-reviews-tab">
                                            <!-- wp:shortcode -->[fluent_cart_product_reviews]<!-- /wp:shortcode -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 order-1 order-lg-2">
                                <div class="position-sticky sticky-top" style="top: 80px;">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <!-- wp:fluent-cart/buy-section {"inside_product_info":"yes"} /-->
                                            <p class="mt-3 mb-0 small text-muted text-center">Backed by FluentCart purchase protection.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div></div>
                <!-- /wp:group -->

                <!-- wp:group {"align":"wide","className":"fct-related-products","layout":{"type":"default"}} -->
                <div class="wp-block-group alignwide fct-related-products"><!-- wp:shortcode -->
                    [fluent_cart_related_products]
                    <!-- /wp:shortcode --></div>
                <!-- /wp:group --></div>
            <!-- /wp:fluent-cart/product-info --></main>
        <!-- /wp:group -->

        <!-- wp:template-part {"slug":"footer","tagName":"footer","area":"footer"} /-->
        <?php
        return ob_get_clean();
    }
}
