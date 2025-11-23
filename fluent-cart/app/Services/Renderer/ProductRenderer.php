<?php

namespace FluentCart\App\Services\Renderer;

use FluentCart\Api\ModuleSettings;
use FluentCart\Api\StoreSettings;
use FluentCart\App\Helpers\Helper;
use FluentCart\App\Models\Product;
use FluentCart\App\Models\ProductVariation;
use FluentCart\App\Vite;
use FluentCart\Framework\Support\Arr;
use FluentCart\App\App;
use FluentCart\Framework\Support\Collection;

class ProductRenderer
{
    protected $product;

    protected $variants;

    protected $defaultVariant = null;

    protected $hasOnetime = false;

    protected $hasSubscription = false;

    protected $viewType = '';

    protected $columnType = '';

    protected $defaultVariationId = '';

    protected $paymentTypes = [];

    protected $variantsByPaymentTypes = [];

    protected $activeTab = 'onetime';

    protected $images = [];

    protected $defaultImageUrl = null;

    protected $defaultImageAlt = null;

    public function __construct(Product $product, $config = [])
    {
        $this->product = $product;
        $this->variants = $product->variants;
        $this->viewType = $config['view_type'] ?? 'both';
        $this->columnType = $config['column_type'] ?? 'masonry';
        $defaultVariationId = $config['default_variation_id'] ?? '';

        if (!$defaultVariationId) {
            $variationIds = $product->variants->pluck('id')->toArray();
            $defaultVariationId = $product->detail->default_variation_id;

            if (!$defaultVariationId || !in_array($defaultVariationId, $variationIds)) {
                $defaultVariationId = Arr::get($variationIds, '0');
            }

            $this->defaultVariationId = $defaultVariationId;
        }

        foreach ($this->product->variants as $variant) {
            if ($variant->id == $this->defaultVariationId) {
                $this->defaultVariant = $variant;
            }
            $paymentType = Arr::get($variant->other_info, 'payment_type');
            if ($paymentType === 'onetime') {
                $this->hasOnetime = true;
            } else if ($paymentType === 'subscription') {
                $this->hasSubscription = true;
            }
        }

        $this->buildProductGroups();
    }

    public function buildProductGroups()
    {
        $groupKey = 'repeat_interval';
        $otherInfo = (array)Arr::get($this->product->detail, 'other_info');
        $groupBy = Arr::get($otherInfo, 'group_pricing_by', 'repeat_interval'); //repeat_interval,payment_type,none


        if ($groupBy !== 'none') {
            if ($groupBy === 'payment_type') {
                $groupKey = 'payment_type';
            }

            $paymentTypes = [];

            if ($groupBy === 'repeat_interval') {
                foreach ($this->variants as $key => $variant) {
                    $paymentType = 'onetime';
                    $type = Arr::get($variant, 'payment_type');
                    if ($type === 'subscription') {
                        $isInstallment = Arr::get($variant, 'other_info.installment', 'no');
                        if ($isInstallment === 'yes' && App::isProActive()) {
                            $paymentType = 'installment';
                        } else {
                            $paymentType = Arr::get($variant, 'other_info.repeat_interval', 'onetime');;
                        }
                    }

                    $paymentTypes[] = $paymentType;

                    if (!isset($this->variantsByPaymentTypes[$paymentType])) {
                        $this->variantsByPaymentTypes[$paymentType] = [];
                    }

                    $this->variantsByPaymentTypes[$paymentType][] = $variant;

                    if ($this->defaultVariationId == $variant['id']) {
                        $this->activeTab = $paymentType;
                    }

                }
            } else {
                foreach ($this->variants as $key => $variant) {
                    $paymentType = 'onetime';
                    $type = Arr::get($variant, 'payment_type');
                    if ($type === 'subscription') {
                        $isInstallment = Arr::get($variant, 'other_info.installment');
                        if ($isInstallment === 'yes' && App::isProActive()) {
                            $paymentType = 'installment';
                        } else {
                            $paymentType = 'subscription';
                        }
                    }
                    $paymentTypes[] = $paymentType;

                    if (!isset($this->variantsByPaymentTypes[$paymentType])) {
                        $this->variantsByPaymentTypes[$paymentType] = [];
                    }

                    $this->variantsByPaymentTypes[$paymentType][] = $variant;

                    if ($this->defaultVariationId == $variant['id']) {
                        $this->activeTab = $paymentType;
                    }

                }
            }

            $paymentTypes = array_unique($paymentTypes);


            $intervalOptions = Helper::getAvailableSubscriptionIntervalOptions();
           
            $groupLanguageMap = [
                    'onetime'      => __('One Time', 'fluent-cart'),
                    'subscription' => __('Subscription', 'fluent-cart'),
                    'installment'  => __('Installment', 'fluent-cart'),
            ];

            foreach ($intervalOptions as $interval) {
                $groupLanguageMap[$interval['value']] = $interval['label'];
            }

            foreach ($paymentTypes as $paymentType) {
                $this->paymentTypes[$paymentType ?: 'onetime'] = Arr::get($groupLanguageMap, $paymentType ?: 'onetime');
            }
        }
    }

    public function render()
    {
        ?>
        <div class="fct-single-product-page" data-fluent-cart-single-product-page>
            <div class="container fct-gig-body py-4">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="d-flex flex-column gap-4">
                            <section class="card shadow-sm">
                                <div class="card-body d-flex flex-column gap-4">
                                    <div class="border rounded-3 overflow-hidden">
                                        <?php $this->renderGallery(); ?>
                                    </div>
                                    <?php $this->renderTitle(); ?>
                                    <?php $this->renderReviewRotator(); ?>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted small fct-gig-meta">
                                        <?php $this->renderStockAvailability('class="text-success fw-semibold"'); ?>
                                    </div>
                                </div>
                            </section>

                            <section class="card shadow-sm">
                                <div class="card-body">
                                    <h3 class="h5 mb-3"><?php esc_html_e('About this service', 'fluent-cart'); ?></h3>
                                    <?php $this->renderEmbeddedVideo(); ?>
                                    <div class="fct-product-description">
                                        <?php echo wp_kses_post(wpautop($this->getFormattedContent())); ?>
                                    </div>

                                    <div class="fct-how-it-works mt-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h4 class="h6 text-uppercase text-muted mb-0"><?php esc_html_e('How it works', 'fluent-cart'); ?></h4>
                                            <span class="small text-muted"><?php esc_html_e('Fast and frictionless', 'fluent-cart'); ?></span>
                                        </div>
                                        <div class="row g-3 g-md-4">
                                            <div class="col-12 col-md-4">
                                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-white shadow-sm h-100">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:linear-gradient(135deg,#eef2ff,#edf7ff);box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                                            <path d="M6 6h14l-1.2 7H8" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <circle cx="9" cy="19" r="1.25" stroke="#4f46e5" stroke-width="1.5"/>
                                                            <circle cx="17" cy="19" r="1.25" stroke="#4f46e5" stroke-width="1.5"/>
                                                            <path d="M6 6l-1 0" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round"/>
                                                        </svg>
                                                    </span>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-body">Add to Cart</span>
                                                        <span class="text-muted small">Select your perfect option</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-white shadow-sm h-100">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:linear-gradient(135deg,#ecfdf3,#e0f2f1);box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                                            <path d="M7 9l5 5 5-5" stroke="#10b981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <rect x="4" y="4" width="16" height="16" rx="4" stroke="#10b981" stroke-width="1.5"/>
                                                        </svg>
                                                    </span>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-body">Place the Order</span>
                                                        <span class="text-muted small">Secure checkout in seconds</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-white shadow-sm h-100">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:linear-gradient(135deg,#eef2ff,#e0f2fe);box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                                            <path d="M7 12l3 3 7-7" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M12 4a8 8 0 100 16 8 8 0 000-16z" stroke="#2563eb" stroke-width="1.5"/>
                                                        </svg>
                                                    </span>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-body">Instant Delivery</span>
                                                        <span class="text-muted small">Confirmation right away</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="card shadow-sm">
                                <div class="card-body">
                                    <h3 class="h5 mb-3"><?php esc_html_e('What you get', 'fluent-cart'); ?></h3>
                                    <?php $this->renderFeatureList(); ?>
                                </div>
                            </section>

                            <section class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h6 text-uppercase text-muted mb-0"><?php esc_html_e('FAQ', 'fluent-cart'); ?></h3>
                                        <span class="text-muted small"><?php esc_html_e('Common questions', 'fluent-cart'); ?></span>
                                    </div>
                                    <?php $this->renderFaqSection(); ?>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="position-sticky" style="top: 90px;">
                            <div class="card shadow-sm fct-gig-purchase-box">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <div class="text-muted small"><?php esc_html_e('Starting at', 'fluent-cart'); ?></div>
                                            <?php $this->renderPrices(); ?>
                                        </div>
                                        <div class="text-end text-muted small">
                                            <?php $this->renderRatingSummary(); ?>
                                        </div>
                                    </div>
                                    <?php $this->renderBuySection(); ?>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="<?php echo esc_url(site_url('/contact')); ?>"
                                   class="btn w-100"
                                   style="border:1px solid #e0e0e0;background-color:#fff;color:#6c757d;border-radius:4px;font-size:0.875rem;"
                                   aria-label="<?php esc_attr_e('Contact Us', 'fluent-cart'); ?>">
                                    <?php esc_html_e('Contact Us', 'fluent-cart'); ?>
                                </a>
                                <p class="text-muted small text-center mb-0 mt-2">
                                    <?php esc_html_e('Get answers fast — we respond within minutes.', 'fluent-cart'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-0 pb-0">
                                <ul class="nav nav-tabs fct-gig-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="fct-tab-reviews" data-bs-toggle="tab" data-bs-target="#fct-tab-pane-reviews" type="button" role="tab" aria-controls="fct-tab-pane-reviews" aria-selected="true"><?php esc_html_e('Reviews', 'fluent-cart'); ?></button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="fct-single-product-tabs">
                                    <div class="tab-pane fade show active" id="fct-tab-pane-reviews" role="tabpanel" aria-labelledby="fct-tab-reviews">
                                        <?php $this->renderReviewPlaceholder(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->renderMobileCtaBar(); ?>
        </div>
        <?php
    }

    protected function renderMobileCtaBar()
    {
        $buttonConfig = $this->preparePurchaseButtonData();
        $pricing = $this->getPrimaryPriceSummary();

        $buttonLabel = $buttonConfig['buy_button_text'];
        if ($pricing && isset($pricing['price'])) {
            $buttonLabel = sprintf(
                /* translators: %s: product price */
                __('Buy Now (%s)', 'fluent-cart'),
                Helper::toDecimal($pricing['price'])
            );
        }

        $savingsText = '';
        if ($pricing && Arr::get($pricing, 'savings', 0) > 0) {
            $savingsText = sprintf(
                /* translators: %s: savings amount */
                __('Save %s — limited time only', 'fluent-cart'),
                Helper::toDecimal($pricing['savings'])
            );
        }

        ?>
        <div class="fct-mobile-cta-bar d-md-none" data-fct-mobile-cta>
            <a <?php $this->renderAttributes(array_merge($buttonConfig['buy_now_attributes'], [
                    'class' => trim($buttonConfig['buy_now_attributes']['class'] . ' fct-mobile-cta-button d-flex align-items-center justify-content-center gap-2 fw-semibold'),
                    'style' => 'background-color:#000;color:#fff;border:1px solid #000;border-radius:4px 4px 0 0;text-align:center;height:48px;padding:0 16px;',
            ])); ?> aria-label="<?php echo esc_attr($buttonConfig['buy_button_text']); ?>" role="button">
                <span class="fct-mobile-cta-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h7.72a2 2 0 0 0 2-1.61L21 6H6"></path>
                    </svg>
                </span>
                <span class="fct-button-text"><?php echo wp_kses_post($buttonLabel); ?></span>
            </a>
            <?php if ($savingsText): ?>
                <div class="fct-mobile-cta-savings fct-saving-badge-bubble" role="note">
                    <span class="fct-mobile-cta-savings-text"><?php echo esc_html($savingsText); ?></span>
                    <span class="fct-mobile-cta-savings-icon" aria-hidden="true">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 14.667A6.667 6.667 0 1 0 8 1.333a6.667 6.667 0 0 0 0 13.334Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 5.333h.005" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.667 8h1.333v3.333" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.667 11.333h2.666" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <script>
            (() => {
                const bar = document.querySelector('[data-fct-mobile-cta]');
                if (!bar) {
                    return;
                }

                const ua = navigator.userAgent || '';
                const isInAppBrowser = /FBAN|FBAV|FB_IAB|Instagram/i.test(ua);
                const computed = window.getComputedStyle(bar);
                const stylesMissing = computed && (computed.position === 'static' || computed.display === 'none');
                if (!isInAppBrowser && !stylesMissing) {
                    return;
                }

                bar.setAttribute('data-fct-mobile-cta-fallback', 'true');
                const fallbackStyles = {
                    position: 'fixed',
                    left: '0',
                    right: '0',
                    bottom: '0',
                    background: '#fff',
                    padding: '10px 16px calc(12px + env(safe-area-inset-bottom, 0px))',
                    zIndex: '9999',
                    boxShadow: '0 -4px 18px rgba(0, 0, 0, 0.08)',
                    borderTopLeftRadius: '4px',
                    borderTopRightRadius: '4px',
                    backfaceVisibility: 'hidden',
                    transform: 'translateZ(0)'
                };

                Object.entries(fallbackStyles).forEach(([key, value]) => {
                    if (!bar.style[key]) {
                        bar.style[key] = value;
                    }
                });

                // Provide iOS WebView safe-area fallback when env() is unavailable
                bar.style.padding = bar.style.padding || '10px 16px calc(12px + constant(safe-area-inset-bottom, 0px))';

                const button = bar.querySelector('.fct-mobile-cta-button');
                if (button) {
                    button.style.display = 'flex';
                    button.style.alignItems = 'center';
                    button.style.justifyContent = 'center';
                    button.style.gap = button.style.gap || '8px';
                    button.style.textDecoration = 'none';
                }

                const savings = bar.querySelector('.fct-mobile-cta-savings');
                if (savings) {
                    savings.style.display = 'flex';
                    savings.style.justifyContent = 'center';
                    savings.style.margin = savings.style.margin || '6px auto 0';
                }
            })();
        </script>
        <?php
    }

    public function renderRatingSummary()
    {
        $rating = apply_filters('fluent_cart/product/rating_value', 4.5, $this->product);
        $ratingCount = apply_filters('fluent_cart/product/rating_count', 5147, $this->product);

        ?>
        <div class="fct-rating-summary fct-amazon-rating d-inline-flex position-relative align-items-center" aria-label="Product rating summary">
            <style>
                .fct-amazon-rating {
                    gap: 8px;
                    cursor: pointer;
                    font-size: 14px;
                    line-height: 1.2;
                }

                .fct-amazon-rating__score {
                    font-weight: 600;
                    color: #0F1111;
                }

                .fct-amazon-rating__stars {
                    color: #ff9900;
                    letter-spacing: 1px;
                    font-size: 15px;
                }

                .fct-amazon-rating__count {
                    color: #007185;
                    text-decoration: none;
                    font-weight: 500;
                    white-space: nowrap;
                }

                .fct-amazon-rating__count:hover,
                .fct-amazon-rating__count:focus {
                    text-decoration: underline;
                }

                .fct-amazon-rating__popover {
                    position: absolute;
                    top: calc(100% + 10px);
                    left: 50%;
                    transform: translate(-50%, 12px);
                    width: min(360px, calc(100vw - 32px));
                    max-width: calc(100% + 120px);
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
                    border-radius: 6px;
                    padding: 14px 16px;
                    z-index: 10;
                    opacity: 0;
                    visibility: hidden;
                    transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
                }

                .fct-amazon-rating:hover .fct-amazon-rating__popover,
                .fct-amazon-rating:focus-within .fct-amazon-rating__popover {
                    opacity: 1;
                    visibility: visible;
                    transform: translate(-50%, 0);
                }

                .fct-amazon-rating__heading {
                    font-size: 14px;
                    font-weight: 600;
                    color: #0F1111;
                    margin-bottom: 6px;
                }

                .fct-amazon-rating__subtext {
                    color: #565959;
                    font-size: 12px;
                    margin-bottom: 10px;
                }

                .fct-amazon-rating__global {
                    color: #0F1111;
                    font-size: 12px;
                    margin-bottom: 12px;
                    font-weight: 600;
                }

                .fct-amazon-rating__breakdown {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                }

                .fct-amazon-rating__row {
                    display: grid;
                    grid-template-columns: 28px 1fr 42px;
                    align-items: center;
                    gap: 10px;
                    font-size: 12px;
                    color: #0F1111;
                }

                .fct-amazon-rating__bar {
                    position: relative;
                    height: 12px;
                    background: #f3f4f6;
                    border-radius: 999px;
                    overflow: hidden;
                }

                .fct-amazon-rating__bar-fill {
                    position: absolute;
                    top: 0;
                    left: 0;
                    height: 100%;
                    background: linear-gradient(90deg, #ffce00, #ffa700);
                    border-radius: 999px;
                }

                .fct-amazon-rating__footer {
                    margin-top: 14px;
                    font-size: 12px;
                }

                .fct-amazon-rating__footer a {
                    color: #007185;
                    text-decoration: none;
                    font-weight: 600;
                }

                .fct-amazon-rating__footer a:hover,
                .fct-amazon-rating__footer a:focus {
                    text-decoration: underline;
                }

                @media (max-width: 420px) {
                    .fct-amazon-rating__popover {
                        width: 90%;
                        max-width: 90%;
                        left: 50%;
                        transform: translate(-50%, 12px);
                        right: auto;
                    }

                    .fct-amazon-rating:hover .fct-amazon-rating__popover,
                    .fct-amazon-rating:focus-within .fct-amazon-rating__popover {
                        transform: translate(-50%, 0);
                    }
                }
            </style>

            <div class="d-flex align-items-center gap-2">
                <span class="fct-amazon-rating__score"><?php echo esc_html(number_format_i18n($rating, 1)); ?></span>
                <span class="fct-amazon-rating__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                <a class="fct-amazon-rating__count" href="#comments">
                    <?php echo esc_html(number_format_i18n(max(0, $ratingCount))); ?> <?php esc_html_e('ratings', 'fluent-cart'); ?>
                </a>
            </div>

            <div class="fct-amazon-rating__popover" role="note">
                <div class="fct-amazon-rating__heading"><?php echo esc_html(number_format_i18n($rating, 1)); ?> <?php esc_html_e('out of 5', 'fluent-cart'); ?></div>
                <div class="fct-amazon-rating__subtext"><?php esc_html_e('Average rating: ', 'fluent-cart'); ?><?php echo esc_html(number_format_i18n($rating, 1)); ?> <?php esc_html_e('out of 5 stars', 'fluent-cart'); ?></div>
                <div class="fct-amazon-rating__global"><?php echo esc_html(number_format_i18n(max(0, $ratingCount))); ?> <?php esc_html_e('global ratings', 'fluent-cart'); ?></div>

                <div class="fct-amazon-rating__breakdown" aria-label="Rating breakdown">
                    <?php
                    $ratingBreakdown = [
                        5 => 76,
                        4 => 11,
                        3 => 5,
                        2 => 2,
                        1 => 6,
                    ];
                    foreach ($ratingBreakdown as $star => $percent) :
                        ?>
                        <div class="fct-amazon-rating__row">
                            <span><?php echo esc_html($star); ?> star</span>
                            <div class="fct-amazon-rating__bar" aria-hidden="true">
                                <span class="fct-amazon-rating__bar-fill" style="width: <?php echo esc_attr($percent); ?>%;"></span>
                            </div>
                            <span><?php echo esc_html($percent); ?>%</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="fct-amazon-rating__footer">
                    <a href="#comments"><?php esc_html_e('See customer reviews', 'fluent-cart'); ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    public function renderReviewRotator()
    {
        $reviews = [
            [
                'avatar' => 'https://i.pravatar.cc/80?img=12',
                'name'   => 'Sofia R.',
                'country' => 'Germany',
                'flag'    => 'https://flagcdn.com/de.svg',
                'time'   => __('2 days ago', 'fluent-cart'),
                'stars'  => '&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-muted ms-1">5.0</span>',
                'quote'  => __('Outstanding experience. Clear communication from start to finish and the final delivery exceeded our brand standards.', 'fluent-cart')
            ],
            [
                'avatar' => 'https://i.pravatar.cc/80?img=32',
                'name'   => 'Daniel K.',
                'country' => 'United States',
                'flag'    => 'https://flagcdn.com/us.svg',
                'time'   => __('5 days ago', 'fluent-cart'),
                'stars'  => '&#9733;&#9733;&#9733;&#9733;&#9734; <span class="text-muted ms-1">4.8</span>',
                'quote'  => __('Fast delivery and thoughtful revisions. The process felt like working with an in-house pro.', 'fluent-cart')
            ],
            [
                'avatar' => 'https://i.pravatar.cc/80?img=47',
                'name'   => 'Maya L.',
                'country' => 'Australia',
                'flag'    => 'https://flagcdn.com/au.svg',
                'time'   => __('1 week ago', 'fluent-cart'),
                'stars'  => '&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-muted ms-1">5.0</span>',
                'quote'  => __('Great partner for our launch campaign. Detail-oriented, proactive, and truly invested in our goals.', 'fluent-cart')
            ],
            [
                'avatar' => 'https://i.pravatar.cc/80?img=24',
                'name'   => 'Liam T.',
                'country' => 'United Kingdom',
                'flag'    => 'https://flagcdn.com/gb.svg',
                'time'   => __('1 week ago', 'fluent-cart'),
                'stars'  => '&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-muted ms-1">5.0</span>',
                'quote'  => __('Communication was effortless and the results matched our brief perfectly. Highly recommend.', 'fluent-cart')
            ],
            [
                'avatar' => 'https://i.pravatar.cc/80?img=55',
                'name'   => 'Elena M.',
                'country' => 'Canada',
                'flag'    => 'https://flagcdn.com/ca.svg',
                'time'   => __('2 weeks ago', 'fluent-cart'),
                'stars'  => '&#9733;&#9733;&#9733;&#9733;&#9734; <span class="text-muted ms-1">4.9</span>',
                'quote'  => __('Thoughtful strategy, clean deliverables, and proactive updates every step of the way.', 'fluent-cart')
            ]
        ];

        $initialReview = $reviews[0];
        ?>
        <div class="card shadow-sm border border-light rounded-1" data-fct-review-rotator style="border-radius:4px;">
            <div class="card-body" data-fct-review-card style="opacity:1; transition: opacity 300ms ease;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?php echo esc_url($initialReview['avatar']); ?>" alt="<?php echo esc_attr($initialReview['name']); ?>" class="rounded-circle shadow-sm border border-light" width="56" height="56" data-fct-review-avatar />
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold text-dark" data-fct-review-name><?php echo esc_html($initialReview['name']); ?></span>
                            <span class="d-inline-flex align-items-center gap-1 text-muted small">
                                <img src="<?php echo esc_url($initialReview['flag']); ?>" alt="<?php echo esc_attr($initialReview['country']); ?>" width="18" height="12" data-fct-review-flag class="border rounded-1 shadow-sm" />
                                <span class="fw-semibold text-dark" data-fct-review-country><?php echo esc_html($initialReview['country']); ?></span>
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <span class="text-warning fw-semibold" data-fct-review-stars><?php echo $initialReview['stars']; ?></span>
                            <span class="text-secondary">•</span>
                            <span class="fw-semibold" data-fct-review-time><?php echo esc_html($initialReview['time']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3 p-3 bg-light rounded-1 border border-light" style="border-radius:4px;">
                    <div class="text-primary-emphasis fs-2 lh-1">“</div>
                    <p class="mb-0 text-muted" data-fct-review-quote><?php echo esc_html($initialReview['quote']); ?></p>
                </div>
            </div>
        </div>

        <script>
            (function() {
                const container = document.querySelector('[data-fct-review-rotator]');
                if (!container) {
                    return;
                }

                const card = container.querySelector('[data-fct-review-card]');
                const getRefs = (root) => ({
                    avatar: root.querySelector('[data-fct-review-avatar]'),
                    name: root.querySelector('[data-fct-review-name]'),
                    stars: root.querySelector('[data-fct-review-stars]'),
                    time: root.querySelector('[data-fct-review-time]'),
                    quote: root.querySelector('[data-fct-review-quote]'),
                    country: root.querySelector('[data-fct-review-country]'),
                    flag: root.querySelector('[data-fct-review-flag]'),
                });

                const refs = getRefs(container);

                const reviews = <?php echo wp_json_encode($reviews); ?>;
                let currentIndex = 0;
                const fadeDuration = 300;
                const intervalMs = 3000;

                const renderReview = (targetRefs, review) => {
                    targetRefs.avatar.src = review.avatar;
                    targetRefs.avatar.alt = review.name;
                    targetRefs.name.textContent = review.name;
                    targetRefs.stars.innerHTML = review.stars;
                    targetRefs.time.textContent = review.time;
                    targetRefs.quote.textContent = review.quote;
                    targetRefs.country.textContent = review.country;
                    targetRefs.flag.src = review.flag;
                    targetRefs.flag.alt = review.country;
                };

                const setStableHeight = () => {
                    const cardWidth = card.offsetWidth || card.getBoundingClientRect().width;
                    if (!cardWidth) {
                        return;
                    }

                    const tempCard = card.cloneNode(true);
                    tempCard.style.position = 'absolute';
                    tempCard.style.left = '-9999px';
                    tempCard.style.visibility = 'hidden';
                    tempCard.style.pointerEvents = 'none';
                    tempCard.style.opacity = '1';
                    tempCard.style.transition = 'none';
                    tempCard.style.width = `${cardWidth}px`;

                    container.appendChild(tempCard);

                    const tempRefs = getRefs(tempCard);
                    let maxHeight = 0;

                    reviews.forEach((review) => {
                        renderReview(tempRefs, review);
                        tempCard.style.height = 'auto';
                        maxHeight = Math.max(maxHeight, tempCard.offsetHeight);
                    });

                    container.removeChild(tempCard);

                    if (maxHeight) {
                        card.style.minHeight = `${maxHeight}px`;
                    }
                };

                setStableHeight();
                window.addEventListener('resize', setStableHeight);

                setInterval(() => {
                    const nextIndex = (currentIndex + 1) % reviews.length;
                    card.style.opacity = '0';

                    setTimeout(() => {
                        currentIndex = nextIndex;
                        renderReview(refs, reviews[currentIndex]);
                        card.style.opacity = '1';
                    }, fadeDuration);
                }, intervalMs);
            })();
        </script>
        <?php
    }

    public function renderFeatureList()
    {
        $features = $this->getFeatures();

        if (empty($features)) {
            ?>
            <p class="text-muted mb-0"><?php esc_html_e('Detailed deliverables will appear here.', 'fluent-cart'); ?></p>
            <?php
            return;
        }

        ?>
        <ul class="list-unstyled row row-cols-1 row-cols-md-2 g-2 mb-0">
            <?php foreach ($features as $feature): ?>
                <li class="col d-flex align-items-start gap-2">
                    <span class="text-success">&#10003;</span>
                    <span class="text-dark"><?php echo esc_html($feature); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

    public function renderFaqSection()
    {
        $faqs = [
            [
                'question' => __('What happens if I’m not satisfied with the order?', 'fluent-cart'),
                'answer'   => __('If something isn’t quite right, I’ll fix it quickly—simply send your feedback within 7 days and I’ll provide adjustments or a refund according to the project scope.', 'fluent-cart')
            ],
            [
                'question' => __('Do I get an invoice for my purchase?', 'fluent-cart'),
                'answer'   => __('Yes. You’ll receive a professional invoice automatically after checkout, and you can download it anytime from your account.', 'fluent-cart')
            ],
            [
                'question' => __('How long does delivery really take?', 'fluent-cart'),
                'answer'   => __('Standard delivery is typically completed within 2–3 business days. You’ll receive updates as I work and can request expedited delivery when needed.', 'fluent-cart')
            ],
            [
                'question' => __('Is my payment secure?', 'fluent-cart'),
                'answer'   => __('Payments are processed through encrypted gateways, and I never store your card details. Your checkout is fully secured.', 'fluent-cart')
            ],
            [
                'question' => __('What if I have questions before or after ordering?', 'fluent-cart'),
                'answer'   => __('Message me anytime—I respond quickly before and after delivery to ensure you’re confident with every step.', 'fluent-cart')
            ],
            [
                'question' => __('Can I request revisions?', 'fluent-cart'),
                'answer'   => __('Absolutely. I include thoughtful revisions that align with the package you select so you receive exactly what you need.', 'fluent-cart')
            ],
            [
                'question' => __('Do you accept custom requests?', 'fluent-cart'),
                'answer'   => __('Yes. Share your requirements and I’ll create a tailored plan with pricing and delivery that fits your goals.', 'fluent-cart')
            ]
        ];

        ?>
        <div class="accordion accordion-flush" id="fct-product-faq">
            <?php foreach ($faqs as $index => $faq):
                $headingId = 'fct-faq-heading-' . $index;
                $collapseId = 'fct-faq-collapse-' . $index;
                ?>
                <div class="accordion-item border rounded-3 mb-2 shadow-sm">
                    <h2 class="accordion-header" id="<?php echo esc_attr($headingId); ?>">
                        <button class="accordion-button <?php echo $index ? 'collapsed' : ''; ?> fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr($collapseId); ?>" aria-expanded="<?php echo $index ? 'false' : 'true'; ?>" aria-controls="<?php echo esc_attr($collapseId); ?>">
                            <?php echo esc_html($faq['question']); ?>
                        </button>
                    </h2>
                    <div id="<?php echo esc_attr($collapseId); ?>" class="accordion-collapse collapse <?php echo $index ? '' : 'show'; ?>" aria-labelledby="<?php echo esc_attr($headingId); ?>" data-bs-parent="#fct-product-faq">
                        <div class="accordion-body text-muted">
                            <?php echo wp_kses_post(wpautop($faq['answer'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function renderReviewPlaceholder()
    {
        ?>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
            <div class="fw-semibold text-dark mb-1"><?php esc_html_e('What our customers say', 'fluent-cart'); ?></div>
            <div class="text-muted small d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2 border border-success-subtle">4.9 ★</span>
                <span><?php esc_html_e('Real feedback from global clients', 'fluent-cart'); ?></span>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <?php
            $placeholderReviews = [
                [
                    'avatar'  => 'https://i.pravatar.cc/80?img=12',
                    'name'    => 'Sofia R.',
                    'country' => 'Germany',
                    'flag'    => 'https://flagcdn.com/de.svg',
                    'rating'  => '5.0',
                    'quote'   => __('Outstanding experience. Clear communication from start to finish and the final delivery exceeded our brand standards.', 'fluent-cart')
                ],
                [
                    'avatar'  => 'https://i.pravatar.cc/80?img=32',
                    'name'    => 'Daniel K.',
                    'country' => 'United States',
                    'flag'    => 'https://flagcdn.com/us.svg',
                    'rating'  => '4.8',
                    'quote'   => __('Fast delivery and thoughtful revisions. The process felt like working with an in-house pro.', 'fluent-cart')
                ],
                [
                    'avatar'  => 'https://i.pravatar.cc/80?img=47',
                    'name'    => 'Maya L.',
                    'country' => 'Australia',
                    'flag'    => 'https://flagcdn.com/au.svg',
                    'rating'  => '5.0',
                    'quote'   => __('Great partner for our launch campaign. Detail-oriented, proactive, and truly invested in our goals.', 'fluent-cart')
                ],
                [
                    'avatar'  => 'https://i.pravatar.cc/80?img=24',
                    'name'    => 'Liam T.',
                    'country' => 'United Kingdom',
                    'flag'    => 'https://flagcdn.com/gb.svg',
                    'rating'  => '5.0',
                    'quote'   => __('Communication was effortless and the results matched our brief perfectly. Highly recommend.', 'fluent-cart')
                ],
                [
                    'avatar'  => 'https://i.pravatar.cc/80?img=55',
                    'name'    => 'Elena M.',
                    'country' => 'Canada',
                    'flag'    => 'https://flagcdn.com/ca.svg',
                    'rating'  => '4.9',
                    'quote'   => __('Thoughtful strategy, clean deliverables, and proactive updates every step of the way.', 'fluent-cart')
                ],
            ];

            foreach ($placeholderReviews as $review):
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border border-light-subtle rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo esc_url($review['avatar']); ?>" alt="<?php echo esc_attr($review['name']); ?>" class="rounded-circle shadow-sm" width="56" height="56" />
                                    <div>
                                        <div class="fw-semibold text-dark"><?php echo esc_html($review['name']); ?></div>
                                        <div class="text-warning small">&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-muted ms-1"><?php echo esc_html($review['rating']); ?></span></div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-muted small bg-light rounded-pill px-3 py-1 border border-light">
                                    <img src="<?php echo esc_url($review['flag']); ?>" alt="<?php echo esc_attr($review['country']); ?>" width="20" height="14" class="rounded shadow-sm" />
                                    <span class="fw-semibold text-dark"><?php echo esc_html($review['country']); ?></span>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="text-primary-emphasis fs-2 lh-1">“</div>
                                <p class="mb-0 text-muted"><?php echo esc_html($review['quote']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function getFormattedContent()
    {
        $content = $this->product->post_content ?: '';

        if (!$content) {
            return __('Add a detailed gig description to inform buyers.', 'fluent-cart');
        }

        return $content;
    }

    protected function renderEmbeddedVideo()
    {
        $videoUrl = $this->product->getProductMeta('embedded_video_url', 'product_video');

        if (!$videoUrl) {
            return;
        }

        $embed = wp_oembed_get($videoUrl);

        if (!$embed) {
            $embed = sprintf(
                '<iframe src="%1$s" allowfullscreen loading="lazy" title="%2$s"></iframe>',
                esc_url($videoUrl),
                esc_attr(get_bloginfo('name'))
            );
        }

        $embed = apply_filters('fluent_cart/product/embedded_video', $embed, $videoUrl, $this->product);

        if (!$embed) {
            return;
        }
        ?>
        <div class="fct-product-video ratio ratio-16x9 mb-3">
            <?php echo wp_kses_post($embed); ?>
        </div>
        <?php
    }

    protected function getFeatures()
    {
        $otherInfo = (array)Arr::get($this->product->detail, 'other_info', []);
        $features = Arr::get($otherInfo, 'features');

        if (is_array($features)) {
            return array_values(array_filter(array_map('trim', $features)));
        }

        $excerpt = wp_strip_all_tags($this->product->post_excerpt);
        if ($excerpt) {
            $parts = array_filter(array_map('trim', preg_split('/[\.\n]+/', $excerpt)));
            return array_slice($parts, 0, 6);
        }

        return [];
    }

    protected function getFaqs()
    {
        $otherInfo = (array)Arr::get($this->product->detail, 'other_info', []);
        $faqs = Arr::get($otherInfo, 'faqs');

        if (is_array($faqs)) {
            $sanitized = [];
            foreach ($faqs as $faq) {
                $question = Arr::get($faq, 'question');
                $answer = Arr::get($faq, 'answer');
                if ($question && $answer) {
                    $sanitized[] = [
                        'question' => $question,
                        'answer'   => $answer
                    ];
                }
            }

            if (!empty($sanitized)) {
                return $sanitized;
            }
        }

        return [];
    }

    public function renderBuySection($atts = [])
    {
        $otherInfo = (array)Arr::get($this->product->detail, 'other_info');
        $groupBy = Arr::get($otherInfo, 'group_pricing_by', 'repeat_interval'); //repeat_interval,payment_type,none

        echo '<div aria-labelledby="fct-product-summary-title" data-fluent-cart-product-pricing-section data-product-id="' . esc_attr($this->product->ID) . '" class="fct_buy_section">';

        if (count($this->paymentTypes) === 1 || $groupBy === 'none') {
            $this->renderVariants(Arr::get($atts, 'variation_atts', []));
        } else {
            $this->renderTab(Arr::get($atts, 'variation_atts', []));
        }

        $this->renderItemPrice();
        $this->renderSavingsBadge();
        $this->renderQuantity();
        ?>
        <div class="fct-product-buttons-wrap d-flex flex-column gap-2">
            <?php $this->renderPurchaseButtons(Arr::get($atts, 'button_atts', [])); ?>
        </div>
        </div>
        <?php
    }

    public function renderGalleryThumb()
    {
        $thumbnails = [];

        $featuredMedia = $this->product->thumbnail ?? Vite::getAssetUrl('images/placeholder.svg');

        if (!$featuredMedia) {
            $featuredMedia = [];
        }

        $galleryImage = get_post_meta($this->product->ID, 'fluent-products-gallery-image', true);

        if (!empty($galleryImage)) {
            $thumbnails[0] = [
                    'media' => $galleryImage,
            ];
        }

        foreach ($this->variants as $variant) {
            if (!empty($variant['media']['meta_value'])) {
                $thumbnails[$variant['id']] = [
                        'media' => $variant['media']['meta_value'],
                ];
            } else {
                $this->defaultImageUrl = $featuredMedia;
                $this->defaultImageAlt = Arr::get($variant, 'variation_title', '');
            }
        }

        $images = empty($thumbnails) ? [] : $thumbnails;

        $this->images = $images;

        if (!empty($images)) {
            $variationId = $this->defaultVariationId;
            $imageId = $variationId;

            if (isset($images[$imageId])) {
                $imageMetaValue = $images[$imageId];
                $this->defaultImageUrl = Arr::get($imageMetaValue, 'media.0.url', '');
                $this->defaultImageAlt = Arr::get($imageMetaValue, 'media.0.title', '');
            }
        }

        ?>
        <div class="fct-product-gallery-thumb" role="region"
             aria-label="<?php echo esc_attr($this->product->post_title . ' gallery'); ?>">
            <img
                    src="<?php echo esc_url($this->defaultImageUrl ?? '') ?>"
                    alt="<?php echo esc_attr($this->defaultImageAlt) ?>"
                    data-fluent-cart-single-product-page-product-thumbnail
                    data-default-image-url="<?php echo esc_url($featuredMedia) ?>"
            />
        </div>
        <?php
    }

    public function renderGalleryThumbControls()
    {
        ?>

        <div class="fct-gallery-thumb-controls" data-fluent-cart-single-product-page-product-thumbnail-controls>

            <?php $this->renderGalleryThumbControl(); ?>

        </div>

        <?php

    }

    public function renderGalleryThumbControl()
    {
        foreach ($this->images as $imageId => $image) {
            if (empty($image['media']) || !is_array($image['media'])) {
                continue;
            }

            foreach ($image['media'] as $item) {
                if (empty(Arr::get($item, 'url', ''))) {
                    continue;
                }

                $this->renderGalleryThumbControlButton($item, $imageId);

            }

        }

    }

    public function renderGalleryThumbControlButton($item, $imageId)
    {

        $isHidden = ''; //$imageId != $this->defaultVariationId ? 'is-hidden' : '';
        $itemUrl = Arr::get($item, 'url', '');
        $itemTitle = Arr::get($item, 'title', '');
        $isSelected = $imageId == $this->defaultVariationId ? 'true' : 'false';
        ?>

        <button
                type="button"
                class="fct-gallery-thumb-control-button <?php echo esc_attr($isHidden); ?>"
                data-fluent-cart-thumb-control-button
                data-url="<?php echo esc_url($itemUrl); ?>"
                data-variation-id="<?php echo esc_attr($imageId); ?>"
                aria-label="<?php echo
                    /* translators: %s image title */
                esc_attr(sprintf(__('View %s image', 'fluent-cart'), $itemTitle));
                ?>"
                aria-pressed="<?php echo esc_attr($isSelected); ?>"
        >
            <img
                    class="fct-gallery-control-thumb"
                    data-fluent-cart-single-product-page-product-thumbnail-controls-thumb
                    src="<?php echo esc_url($itemUrl); ?>"
                    alt="<?php echo esc_attr($itemTitle); ?>"
            />
        </button>

        <?php


    }

    public function renderGallery($args = [])
    {

        $defaults = [
                'thumbnail_mode' => 'all', // horizontal, vertical
                'thumb_position' => 'bottom' // bottom, left, right, top
        ];

        $atts = wp_parse_args($args, $defaults);

        $thumbnailMode = $atts['thumbnail_mode'];

        $wrapperAtts = [
                'class'                                    => 'fct-product-gallery-wrapper ' . 'thumb-pos-' . $atts['thumb_position'] . ' thumb-mode-' . $thumbnailMode,
                'data-fct-product-gallery'                 => '',
                'data-fluent-cart-product-gallery-wrapper' => '',
                'data-thumbnail-mode'                      => $thumbnailMode,
                'data-product-id'                          => $this->product->ID,
        ];

        ?>

        <div <?php RenderHelper::renderAtts($wrapperAtts); ?>>
            <?php $this->renderGalleryThumb(); ?>
            <?php $this->renderGalleryThumbControls(); ?>
        </div>

        <?php
    }

    public function renderTitle()
    {
        ?>
        <div class="fct-product-title">
            <h1 id="fct-product-summary-title"><?php echo esc_html($this->product->post_title); ?></h1>
        </div>
        <?php
    }

    public function renderStockAvailability($wrapper_attributes = '')
    {
        if (!ModuleSettings::isActive('stock_management')) {
            return '';
        }

        $stockAvailability = $this->product->detail->getStockAvailability();

        if (!Arr::get($stockAvailability, 'manage_stock')) {
            return '';
        }

        $stockLabel = $stockAvailability['availability'];

        $hasInStock = $this->product->variants()
                ->where('stock_status', Helper::IN_STOCK)->exists();

        if (!$hasInStock) {
            $stockLabel = __('Out of stock', 'fluent-cart');
        }

        $statusClass = $stockAvailability['class'] ?? '';

        echo sprintf(
                '<div class="fct-product-stock %1$s" role="status" aria-live="polite">
                    <div %2$s>
                        <span class="fct-stock-status fct_status_badge_%1$s" data-fluent-cart-product-stock>
                            %3$s
                        </span>
                    </div>
                </div>',
                esc_attr($statusClass),
                $wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                esc_html($stockLabel)
        );
    }

    public function renderExcerpt()
    {
        $excerpt = $this->product->post_excerpt;
        if (!$excerpt) {
            return;
        }
        ?>
        <div class="fct-product-excerpt" aria-labelledby="fct-product-summary-title">
            <p><?php echo wp_kses_post($excerpt); ?></p>
        </div>
        <?php

    }

    public function renderPrices()
    {
        if ($this->product->detail->variation_type === 'simple') {
            // we have to render for the simple product

            $first_price = $this->product->variants()->first();

            $itemPrice = $first_price ? $first_price->item_price : 0;
            $comparePrice = $first_price ? $first_price->compare_price : 0;
            if ($comparePrice <= $itemPrice) {
                $comparePrice = 0;
            }
            do_action('fluent_cart/product/single/before_price_block', [
                    'product'       => $this->product,
                    'current_price' => $itemPrice,
                    'scope'         => 'price_range'
            ]);
            ?>
            <?php

            if ($comparePrice) {
                $aria_label = sprintf(
                /* translators: 1: Original price, 2: Current item price */
                        __('Original Price: %1$s, Price: %2$s', 'fluent-cart'),
                        Helper::toDecimal($comparePrice),
                        Helper::toDecimal($itemPrice)
                );
            } else {
                $aria_label = sprintf(
                /* translators: 1: Current item price */
                        __('Price: %1$s', 'fluent-cart'),
                        Helper::toDecimal($itemPrice)
                );
            }

            ?>
            <div class="fct-price-range fct-product-prices" role="term"
                 aria-label="<?php echo esc_attr($aria_label); ?>">

                <?php if ($comparePrice): ?>
                    <span class="fct-compare-price">
                        <del aria-label="<?php echo esc_attr(__('Original price', 'fluent-cart')); ?>"><?php echo esc_html(Helper::toDecimal($comparePrice)); ?></del>
                    </span>
                <?php endif; ?>
                <span class="fct-item-price" aria-label="<?php echo esc_attr(__('Current price', 'fluent-cart')); ?>">
                    <?php echo esc_html(Helper::toDecimal($itemPrice)); ?>
                    <?php do_action('fluent_cart/product/after_price', [
                            'product'       => $this->product,
                            'current_price' => $itemPrice,
                            'scope'         => 'price_range'
                    ]); ?>
                </span>
            </div>
            <?php
            do_action('fluent_cart/product/single/after_price_block', [
                    'product'       => $this->product,
                    'current_price' => $itemPrice,
                    'scope'         => 'price_range'
            ]);
            return;
        }
        $min_price = $this->product->detail->min_price;
        $max_price = $this->product->detail->max_price;

        do_action('fluent_cart/product/single/before_price_range_block', [
                'product'       => $this->product,
                'current_price' => $min_price,
                'scope'         => 'price_range'
        ]);
        ?>
        <?php
        $aria_label = sprintf(
        /* translators: 1: Minimum price, 2: Maximum price */
                __('Price range: %1$s - %2$s', 'fluent-cart'),
                Helper::toDecimal($min_price),
                Helper::toDecimal($max_price)
        );
        ?>
        <div class="fct-product-prices fct-price-range" role="term" aria-label="<?php echo esc_attr($aria_label); ?>">

            <?php if ($max_price && $max_price != $min_price && $max_price > $min_price): ?>
                <span class="fct-min-price"><?php echo esc_html(Helper::toDecimal($min_price)); ?></span>
                <span class="fct-price-separator" aria-hidden="true">-</span>
            <?php endif; ?>
            <span class="fct-max-price">
                <?php echo esc_html(Helper::toDecimal($max_price)); ?>
            </span>

            <?php do_action('fluent_cart/product/after_price', [
                    'product'       => $this->product,
                    'current_price' => $min_price,
                    'scope'         => 'price_range'
            ]); ?>

        </div>
        <?php
        do_action('fluent_cart/product/single/after_price_range_block', [
                'product'       => $this->product,
                'current_price' => $min_price,
                'scope'         => 'price_range'
        ]);
    }

    public function renderVariants($atts = [])
    {
        if ($this->product->detail->variation_type === 'simple') {
            return;
        }

        $variants = $this->product->variants;
        if (!$variants || $variants->isEmpty()) {
            return;
        }

        // Sort by serial_index ascending
        $variants = $variants->sortBy('serial_index')->values();

        $classes = array_filter([
                'fct-product-variants',
                'column-type-' . $this->columnType,
                Arr::get($atts, 'wrapper_class', ''),
        ]);

        ?>
        <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" role="radiogroup"
             aria-label="<?php esc_attr_e('Product Variants', 'fluent-cart'); ?>">
            <?php foreach ($variants as $variant) {
                do_action('fluent_cart/product/single/before_variant_item', [
                        'product' => $this->product,
                        'variant' => $variant,
                        'scope'   => 'product_variant_item'
                ]);
                $this->renderVariationItem($variant, $this->defaultVariationId);
                do_action('fluent_cart/product/single/after_variant_item', [
                        'product' => $this->product,
                        'variant' => $variant,
                        'scope'   => 'product_variant_item'
                ]);
            } ?>
        </div>
        <?php
    }

    public function renderItemPrice()
    {
        if ($this->product->detail->variation_type === 'simple' && !$this->hasSubscription) {
            return; // for simple product we already rendered the price
        }

        $defaultPaymentType = $this->defaultVariant ? Arr::get($this->defaultVariant->other_info, 'payment_type', 'onetime') : 'onetime';


        if ($defaultPaymentType !== 'subscription') {

        }
        do_action('fluent_cart/product/single/before_price_block', [
                'product'       => $this->product,
                'current_price' => $this->defaultVariant ? $this->defaultVariant->item_price : 0,
                'scope'         => 'product_variant_price'
        ]);
        ?>
        <?php if ($this->viewType !== 'text' || $this->columnType !== 'one'): ?>

        <?php
        foreach ($this->product->variants as $variant): ?>
            <div
                    class="fct-product-item-price fluent-cart-product-variation-content <?php echo $this->defaultVariant->id != $variant->id ? ' is-hidden' : '' ?>"
                    data-fluent-cart-product-item-price
                    data-variation-id="<?php echo esc_attr($variant->id); ?>"
            >

                <?php if ($this->defaultVariant && !$this->hasSubscription) {
                    if ($variant->compare_price): ?>
                        <span class="fct-compare-price">
                            <del><?php echo esc_html(Helper::toDecimal($variant->compare_price)); ?></del>
                        </span>
                    <?php endif;

                    echo wp_kses_post(apply_filters('fluent_cart/single_product/variation_price', esc_html(Helper::toDecimal($variant->item_price)), [
                            'product' => $this->product,
                            'variant' => $variant,
                            'scope'   => 'product_variant_price'
                    ]));
                    do_action('fluent_cart/product/after_price', [
                            'product'       => $this->product,
                            'current_price' => $variant->item_price,
                            'scope'         => 'product_variant_price'
                    ]);
                } ?>
            </div>
        <?php endforeach; ?>
    <?php
    endif; ?>
        <?php if ($this->hasSubscription && $this->viewType !== 'text' && $this->columnType !== 'one'): ?>

        <?php
        foreach ($this->product->variants as $variant): ?>
            <?php
            $paymentType = Arr::get($variant->other_info, 'payment_type', 'onetime');
            $atts = [
                    'class'                                 => 'fct-product-payment-type fluent-cart-product-variation-content ' . ($paymentType !== 'subscription' || $this->defaultVariant->id != $variant->id ? ' is-hidden' : ''),
                    'data-fluent-cart-product-payment-type' => '',
                    'data-variation-id'                     => $variant->id
            ];
            ?>
            <div <?php $this->renderAttributes($atts); ?>>
                <?php if ($variant->compare_price): ?>
                    <span class="fct-compare-price">
                        <del><?php echo esc_html(Helper::toDecimal($variant->compare_price)); ?></del>
                    </span>
                <?php endif; ?>
                <?php


                if ($paymentType === 'onetime') {
                    echo esc_html(Helper::toDecimal($variant->item_price));

                } else {
                    echo wp_kses_post(apply_filters('fluent_cart/single_product/variation_price', esc_html($variant->getSubscriptionTermsText(true)), [
                            'product' => $this->product,
                            'variant' => $variant,
                            'scope'   => 'product_variant_price'
                    ]));
                }

                ?>
            </div>
        <?php endforeach; ?>
    <?php endif;

        do_action('fluent_cart/product/single/after_price_block', [
                'product'       => $this->product,
                'current_price' => $this->defaultVariant ? $this->defaultVariant->item_price : 0,
                'scope'         => 'product_variant_price'
        ]);
    }

    public function renderSavingsBadge()
    {
        if (!$this->product->variants || $this->product->variants->isEmpty()) {
            return;
        }

        $badges = [];

        foreach ($this->product->variants as $variant) {
            $regularPrice = $variant->compare_price;
            $salePrice = $variant->item_price;

            if (!is_numeric($regularPrice) || !is_numeric($salePrice)) {
                continue;
            }

            $saving = floatval($regularPrice) - floatval($salePrice);

            if ($saving <= 0) {
                continue;
            }

            $badges[$variant->id] = [
                    'text' => sprintf(__('Save %s — Limited time offer', 'fluent-cart'), Helper::toDecimal($saving))
            ];
        }

        if (empty($badges)) {
            return;
        }

        $defaultVariationId = $this->defaultVariant ? $this->defaultVariant->id : key($badges);
        $defaultBadgeText = Arr::get($badges, $defaultVariationId . '.text', '');

        ?>
        <div class="fct-savings-badge-wrap mb-3"
             data-fluent-cart-product-savings
             data-default-variation-id="<?php echo esc_attr($defaultVariationId); ?>"
             data-savings-map="<?php echo esc_attr(wp_json_encode($badges)); ?>">
            <div class="fct-savings-badge fct-saving-badge-bubble" data-fluent-cart-product-savings-badge
                 <?php echo $defaultBadgeText ? '' : 'style="display:none;"'; ?>>
                <span class="fct-savings-badge-text"><?php echo esc_html($defaultBadgeText); ?></span>
                <span class="fct-savings-badge-icon" aria-hidden="true">
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 14.667A6.667 6.667 0 1 0 8 1.333a6.667 6.667 0 0 0 0 13.334Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 5.333h.005" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.667 8h1.333v3.333" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.667 11.333h2.666" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
            <div class="fct-savings-trust-line" data-fluent-cart-product-savings-trust <?php echo $defaultBadgeText ? '' : 'style="display:none;"'; ?>>
                <span aria-hidden="true">🛡️</span>
                <span><?php esc_html_e('100% Money-Back Guarantee — No Risk', 'fluent-cart'); ?></span>
            </div>
        </div>
        <script>
            (function() {
                const wrapper = document.querySelector('[data-fluent-cart-product-savings]');
                if (!wrapper) {
                    return;
                }

                const badge = wrapper.querySelector('[data-fluent-cart-product-savings-badge]');
                const badgeText = wrapper.querySelector('.fct-savings-badge-text');
                const trustLine = wrapper.querySelector('[data-fluent-cart-product-savings-trust]');
                let savingsMap = {};

                try {
                    savingsMap = JSON.parse(wrapper.getAttribute('data-savings-map')) || {};
                } catch (e) {
                    savingsMap = {};
                }

                const toggleBadge = (variationId) => {
                    const info = savingsMap[variationId];

                    if (!info || !info.text) {
                        if (badge) {
                            badge.style.display = 'none';
                        }
                        if (trustLine) {
                            trustLine.style.display = 'none';
                        }
                        return;
                    }

                    if (badge && badgeText) {
                        badgeText.textContent = info.text;
                        badge.style.display = 'inline-flex';
                    }
                    if (trustLine) {
                        trustLine.style.display = 'flex';
                    }
                };

                const handleSelection = (event) => {
                    const variant = event.target.closest('[data-fluent-cart-product-variant]');
                    if (!variant) {
                        return;
                    }

                    const variationId = variant.getAttribute('data-cart-id');
                    toggleBadge(variationId);
                };

                const variantContainer = document.querySelector('.fct-product-variants');
                if (variantContainer) {
                    variantContainer.addEventListener('click', handleSelection);
                    variantContainer.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            handleSelection(event);
                        }
                    });
                }

                toggleBadge(wrapper.getAttribute('data-default-variation-id'));
            })();
        </script>
        <?php
    }

    public function renderQuantity()
    {
        $soldIndividually = $this->product->soldIndividually();

        if (!$this->hasOnetime || $soldIndividually) {
            return;
        }

        $attributes = [
                'data-fluent-cart-product-quantity-container' => '',
                'data-cart-id'                                => $this->defaultVariant ? $this->defaultVariant->id : '',
                'data-variation-type'                         => $this->product->detail->variation_type,
                'data-payment-type'                           => 'onetime',
                'class'                                       => 'fct-product-quantity-container mb-3'
        ];

        $defaultVariantData = $this->getDefaultVariantData();

        if ($this->hasSubscription && Arr::get($defaultVariantData, 'payment_type') !== 'onetime') {
            $attributes['class'] .= ' is-hidden';
        }

        do_action('fluent_cart/product/single/before_quantity_block', [
                'product' => $this->product,
                'scope'   => 'product_quantity_block'
        ]);
        ?>
        <div <?php $this->renderAttributes($attributes); ?>>
            <label for="fct-product-qty-input" class="quantity-title">
                <?php esc_html_e('Quantity', 'fluent-cart'); ?>
            </label>

            <div class="fct-product-quantity">
                <button class="fct-quantity-decrease-button"
                        data-fluent-cart-product-qty-decrease-button
                        title="<?php esc_html_e('Decrease Quantity', 'fluent-cart'); ?>"
                        aria-label="<?php esc_attr_e('Decrease Quantity', 'fluent-cart'); ?>"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="2" viewBox="0 0 14 2" fill="none">
                        <path d="M12.3333 1L1.66659 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"></path>
                    </svg>
                </button>

                <input
                        id="fct-product-qty-input"
                        min="1"
                        <?php echo $soldIndividually ? 'max="1"' : ''; ?>
                        class="fct-quantity-input"
                        data-fluent-cart-single-product-page-product-quantity-input
                        type="text"
                        placeholder="<?php esc_attr_e('Quantity', 'fluent-cart'); ?>"
                        value="1"
                        aria-label="<?php esc_attr_e('Product quantity', 'fluent-cart'); ?>"
                />

                <button class="fct-quantity-increase-button"
                        data-fluent-cart-product-qty-increase-button
                        title="<?php esc_attr_e('Increase Quantity', 'fluent-cart'); ?>"
                        aria-label="<?php esc_attr_e('Increase Quantity', 'fluent-cart'); ?>"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M6.99996 1.66666L6.99996 12.3333M12.3333 6.99999L1.66663 6.99999" stroke="currentColor"
                              stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
        <?php
        do_action('fluent_cart/product/single/after_quantity_block', [
                'product' => $this->product,
                'scope'   => 'product_quantity_block'
        ]);
    }

    protected function preparePurchaseButtonData($atts = [])
    {
        if (ModuleSettings::isActive('stock_management')) {
            if ($this->product->detail->variation_type === 'simple' && $this->defaultVariant) {
                if ($this->product->detail->manage_stock && $this->defaultVariant->stock_status !== Helper::IN_STOCK) {
                    echo '<span aria-disabled="true">' . esc_html__('Out of stock', 'fluent-cart') . '</span>';
                    return [];
                }
            }
        }

        $defaults = [
                'buy_now_text'     => __('Buy Now', 'fluent-cart'),
                'add_to_cart_text' => __('Add To Cart', 'fluent-cart'),
        ];

        $atts = wp_parse_args($atts, $defaults);

        $buyNowAttributes = [
                'data-fluent-cart-direct-checkout-button' => '',
                'data-variation-type'                     => $this->product->detail->variation_type,
                'class'                                   => 'fluent-cart-direct-checkout-button',
                'data-stock-availability'                 => 'in-stock',
                'data-quantity'                           => '1',
                'href'                                    => site_url('?fluent-cart=instant_checkout&item_id=') . ($this->defaultVariant ? $this->defaultVariant->id : '') . '&quantity=1',
                'data-cart-id'                            => $this->defaultVariant ? $this->defaultVariant->id : '',
                'data-url'                                => site_url('?fluent-cart=instant_checkout&item_id='),
        ];

        $cartAttributes = [
                'data-fluent-cart-add-to-cart-button' => '',
                'data-cart-id'                        => $this->defaultVariant ? $this->defaultVariant->id : '',
                'data-product-id'                     => $this->product->ID,
                'class'                               => 'fluent-cart-add-to-cart-button ',
                'data-variation-type'                 => $this->product->detail->variation_type,
        ];

        $defaultVariantData = $this->getDefaultVariantData();

        if ($this->hasSubscription && Arr::get($defaultVariantData, 'payment_type') !== 'onetime') {
            $cartAttributes['class'] .= ' is-hidden';
        }

        $buyButtonText = apply_filters('fluent_cart/product/buy_now_button_text', $atts['buy_now_text'], [
                'product' => $this->product
        ]);

        $addToCartText = apply_filters('fluent_cart/product/add_to_cart_text', $atts['add_to_cart_text'], [
                'product' => $this->product
        ]);

        return [
                'buy_now_attributes' => $buyNowAttributes,
                'cart_attributes'    => $cartAttributes,
                'buy_button_text'    => $buyButtonText,
                'add_to_cart_text'   => $addToCartText,
        ];
    }

    public function renderPurchaseButtons($atts = [])
    {
        $buttonConfig = $this->preparePurchaseButtonData($atts);

        if (empty($buttonConfig)) {
            return;
        }

        $buyNowAttributes = $buttonConfig['buy_now_attributes'];
        $cartAttributes = $buttonConfig['cart_attributes'];
        $buyButtonText = $buttonConfig['buy_button_text'];
        $addToCartText = $buttonConfig['add_to_cart_text'];
        ?>
        <div class="fct-purchase-actions d-flex flex-column gap-3">
            <a <?php $this->renderAttributes(array_merge($buyNowAttributes, [
                    'class' => $buyNowAttributes['class'] . ' btn w-100 fw-semibold text-uppercase',
                    'style' => 'background-color:#000;color:#fff;border:1px solid #000;border-radius:4px;text-align:center;'
            ])); ?> aria-label="<?php echo esc_attr($buyButtonText); ?>" role="button">
                <?php echo wp_kses_post($buyButtonText); ?>
            </a>
            <?php if ($this->hasOnetime): ?>
            <button <?php $this->renderAttributes(array_merge($cartAttributes, [
                    'class' => $cartAttributes['class'] . ' btn w-100 text-uppercase fw-semibold',
                    'style' => 'border-radius:4px;'
            ])); ?> aria-label="<?php echo esc_attr($addToCartText); ?>">
                <span class="text">
                    <?php echo wp_kses_post($addToCartText); ?>
                </span>
                <span class="fluent-cart-loader" role="status">
                        <svg aria-hidden="true"
                             width="20"
                             height="20"
                             class="w-5 h-5 text-gray-200 animate-spin fill-blue-600"
                             viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                      d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                      fill="currentColor"/>
                              <path
                                      d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.10071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                      fill="currentFill"/>
                        </svg>
                    </span>
            </button>
            <?php endif; ?>
        </div>
    <?php
    }


    public static function renderNoProductFound()
    {
        ?>
        <div class="fluent-cart-shop-no-result-found" data-fluent-cart-shop-no-result-found role="status"
             aria-live="polite">
            <p class="has-text-align-center has-large-font-size m-0">
                <?php echo esc_html__('No Product Found!', 'fluent-cart'); ?>
            </p>

            <p class="has-text-align-center">
                <?php echo esc_html__('You can try clearing any filters.', 'fluent-cart'); ?>
            </p>
        </div>
        <?php
    }

    protected function renderVariationItem(ProductVariation $variant, $defaultId = '', $extraClasses = [])
    {
        $availableStocks = $variant->available;
        if (!$variant->manage_stock) {
            $availableStocks = 'unlimited';
        }

        $comparePrice = $variant->compare_price;
        if ($comparePrice <= $variant->item_price) {
            $comparePrice = '';
        }

        if ($comparePrice) {
            $comparePrice = Helper::toDecimal($comparePrice);
        }

        $paymentType = Arr::get($variant->other_info, 'payment_type');

        $itemClasses = [
                'fct-product-variant-item',
                'fct_price_type_' . $paymentType,
                'fct_variation_view_type_' . $this->viewType,
        ];

        if ($variant->media_id) {
            $itemClasses[] = 'fct-item-has-image';
        }

        if ($variant->id == $defaultId) {
            $itemClasses[] = 'selected';
        }

        $priceSuffix = apply_filters('fluent_cart/product/price_suffix_atts', '', [
                'product' => $this->product,
                'variant' => $variant,
                'scope'   => 'variant_item'
        ]);

        $renderingAttributes = [
                'data-fluent-cart-product-variant' => '',
                'data-cart-id'                     => $variant->id,
                'data-item-stock'                  => $variant->stock_status,
                'data-default-variation-id'        => $defaultId,
                'data-payment-type'                => $paymentType,
                'data-available-stock'             => $availableStocks,
                'data-item-price'                  => Helper::toDecimal($variant->item_price),
                'data-compare-price'               => $comparePrice,
                'data-price-suffix'                => $priceSuffix,
                'data-stock-management'            => ModuleSettings::isActive('stock_management') ? 'yes' : 'no',
        ];

        if ($paymentType === 'subscription') {
            $renderingAttributes['data-subscription-terms'] = $variant->getSubscriptionTermsText(true);
            $repeatInterval = Arr::get($variant->other_info, 'repeat_interval', '');
            $hasInstallment = Arr::get($variant->other_info, 'has_installment') === 'yes';

            $itemClasses[] = 'fct_sub_interval_' . $repeatInterval;
            if ($hasInstallment) {
                $itemClasses[] = 'fct_sub_has_installment';
            }
        }

        if ($extraClasses) {
            $itemClasses = array_merge($itemClasses, $extraClasses);
        }

        $itemClasses = array_filter($itemClasses);
        $renderingAttributes['class'] = implode(' ', $itemClasses);

        $itemPrice = $variant->item_price;
        $comparePrice = $variant->compare_price;
        if (!$comparePrice || $comparePrice <= $itemPrice) {
            $comparePrice = 0;
        }

        ?>
        <div
                <?php $this->renderAttributes($renderingAttributes); ?>
                role="radio"
                tabindex="0"
                aria-checked="<?php echo $variant->id == $defaultId ? 'true' : 'false'; ?>"
                aria-label="<?php echo esc_attr($variant->variation_title); ?>"
        >
            <?php if ($this->viewType === 'image'): ?>
                <?php $this->renderTooltip($variant); ?>
            <?php endif; ?>

            <div class="variant-content">
                <?php
                if ($this->viewType === 'both' || $this->viewType === 'image') {
                    $this->renderVariantImage($variant);
                }
                ?>
                <?php
                if ($this->viewType === 'both' || $this->viewType === 'text') {
                    echo '<div class="fct-product-variant-title" aria-label="' . esc_attr(__('Variant title', 'fluent-cart')) . '">' . esc_html($variant->variation_title) . '</div>';
                }
                ?>
            </div>

            <?php if ($this->viewType === 'text' && $paymentType === 'subscription' && $this->columnType === 'one'): ?>
                <?php $this->renderSubscriptionInfo($variant); ?>
            <?php endif; ?>

            <?php if ($this->viewType === 'text' && $this->columnType === 'one'): ?>
                <div class="fct-product-variant-price">
                    <?php if ($comparePrice): ?>
                        <div class="fct-product-variant-compare-price">
                            <del aria-label="<?php echo esc_attr(__('Original price', 'fluent-cart')); ?>">
                                <span><?php echo esc_html(Helper::toDecimal($comparePrice)); ?></span></del>
                        </div>
                    <?php endif; ?>
                    <div class="fct-product-variant-item-price"
                         aria-label="<?php echo esc_attr(__('Current price', 'fluent-cart')); ?>">
                        <span><?php echo esc_html(Helper::toDecimal($itemPrice)); ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function renderTooltip($variant)
    {
        ?>
        <div class="fct-product-variant-tooltip" role="tooltip" id="tooltip-<?php echo esc_attr($variant->id); ?>">
            <?php echo esc_html($variant->variation_title); ?>
        </div>
        <?php
    }

    protected function renderVariantImage($variant)
    {
        $image = $variant->thumbnail;
        if (!$image) {
            $image = Vite::getAssetUrl('images/placeholder.svg');
        }
        ?>
        <div class="fct-product-variant-image">
            <img role="img" alt="<?php echo esc_attr($variant->variation_title); ?>"
                 src="<?php echo esc_url($image); ?>"/>
        </div>
        <?php
    }

    protected function renderSubscriptionInfo($variant)
    {
        $info = $variant->getSubscriptionTermsText(true);

        if (!$info) {
            return '';
        }

        ?>
        <div class="fct-product-variant-payment-type" aria-live="polite">
            <div class="additional-info">
                <span><?php echo esc_html($info); ?></span>
            </div>
        </div>
        <?php
    }

    protected function renderAttributes($atts = [])
    {
        foreach ($atts as $attr => $value) {
            if ($value !== '') {
                echo esc_attr($attr) . '="' . esc_attr((string)$value) . '" ';
            } else {
                echo esc_attr($attr) . ' ';
            }
        }
    }

    protected function renderTab($atts = [])
    {
        ?>
        <div class="fct-product-tab" data-fluent-cart-product-tab>
            <?php $this->renderTabNav(); ?>

            <div class="fct-product-tab-content" data-tab-contents>
                <?php $this->renderTabPane($atts); ?>
            </div>
        </div>
        <?php

    }

    protected function renderTabNav()
    {
        ?>

        <div class="fct-product-tab-nav" role="tablist">
            <div class="tab-active-bar" data-tab-active-bar></div>
            <?php
            foreach ($this->paymentTypes as $typeKey => $typeLabel) : ?>
                <div
                        class="fct-product-tab-nav-item <?php echo esc_attr($this->activeTab === $typeKey ? 'active' : ''); ?>"
                        data-tab="<?php echo esc_attr($typeKey); ?>"
                        role="tab"
                        tabindex="0"
                        aria-selected="<?php echo $this->activeTab === $typeKey ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($typeKey); ?>"
                >
                    <?php echo esc_html($typeLabel); ?>
                </div>
            <?php endforeach;
            ?>
        </div>

        <?php
    }

    protected function renderTabPane($atts = [])
    {
        $variantsClasses = [
                'fct-product-variants',
                'column-type-' . $this->columnType,
                Arr::get($atts, 'wrapper_class', ''),
        ];

        foreach ($this->variantsByPaymentTypes as $variantKey => $variants): ?>
            <div
                    data-tab-content
                    id="<?php echo esc_attr($variantKey); ?>"
                    class="fct-product-tab-pane <?php echo esc_attr($this->activeTab === $variantKey ? 'active' : ''); ?>"
                    role="tabpanel"
                    aria-labelledby="<?php echo esc_attr($variantKey); ?>"
            >
                <div class="<?php echo esc_attr(implode(' ', $variantsClasses)); ?>">
                    <?php
                    //Convert to collection safely before sorting
                    $variants = (new Collection($variants))->sortBy('serial_index')->values();

                    foreach ($variants as $variant) {
                        do_action('fluent_cart/product/single/before_variant_item', [
                                'product' => $this->product,
                                'variant' => $variant,
                                'scope'   => 'product_variant_item'
                        ]);

                        $this->renderVariationItem($variant, $this->defaultVariationId);

                        do_action('fluent_cart/product/single/after_variant_item', [
                                'product' => $this->product,
                                'variant' => $variant,
                                'scope'   => 'product_variant_item'
                        ]);
                    }
                    ?>
                </div>

            </div>
        <?php endforeach; ?>

        <?php
    }

    protected function getPrimaryPriceSummary()
    {
        $variant = $this->defaultVariant ?: ($this->product->variants()->first());

        if (!$variant) {
            return null;
        }

        $price = (float)Arr::get($variant, 'item_price', 0);
        $comparePrice = (float)Arr::get($variant, 'compare_price', 0);

        if ($comparePrice <= $price) {
            $comparePrice = 0;
        }

        return [
            'price'         => $price,
            'compare_price' => $comparePrice,
            'savings'       => $comparePrice ? max(0, $comparePrice - $price) : 0,
        ];
    }

    protected function getDefaultVariantData()
    {
        if (empty($this->variants) || !$this->defaultVariationId) {
            return null;
        }

        foreach ($this->variants as $variant) {
            if ($variant['id'] == $this->defaultVariationId) {
                return $variant;
            }
        }

        return null;
    }
}
