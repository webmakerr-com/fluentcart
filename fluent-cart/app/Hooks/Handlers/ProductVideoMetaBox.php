<?php

namespace FluentCart\App\Hooks\Handlers;

use FluentCart\App\CPT\FluentProducts;
use FluentCart\App\Models\Product;

class ProductVideoMetaBox
{
    public function register()
    {
        add_action('add_meta_boxes', [$this, 'registerMetaBox']);
        add_action('save_post', [$this, 'saveMetaBox']);
    }

    public function registerMetaBox()
    {
        add_meta_box(
            'fluent_cart_product_video',
            __('Product Video', 'fluent-cart'),
            [$this, 'renderMetaBox'],
            FluentProducts::CPT_NAME,
            'side',
            'default'
        );
    }

    public function renderMetaBox($post)
    {
        wp_nonce_field('fluent_cart_product_video', '_fct_product_video_nonce');

        $product = Product::find($post->ID);
        $videoUrl = '';

        if ($product) {
            $videoUrl = (string)$product->getProductMeta('embedded_video_url', 'product_video', '');
        }

        ?>
        <p>
            <label for="fluent_cart_product_video_url" class="screen-reader-text">
                <?php esc_html_e('Video URL', 'fluent-cart'); ?>
            </label>
            <input type="url" name="fluent_cart_product_video_url" id="fluent_cart_product_video_url" class="widefat"
                   placeholder="<?php esc_attr_e('https://example.com/video', 'fluent-cart'); ?>"
                   value="<?php echo esc_attr($videoUrl); ?>" />
        </p>
        <p class="description">
            <?php esc_html_e('Add a YouTube, Vimeo, or any embeddable video link to highlight your product on the single product page.', 'fluent-cart'); ?>
        </p>
        <?php
    }

    public function saveMetaBox($postId)
    {
        if (!isset($_POST['_fct_product_video_nonce']) || !wp_verify_nonce($_POST['_fct_product_video_nonce'], 'fluent_cart_product_video')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (get_post_type($postId) !== FluentProducts::CPT_NAME) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        $videoUrl = isset($_POST['fluent_cart_product_video_url']) ? esc_url_raw($_POST['fluent_cart_product_video_url']) : '';

        $product = Product::find($postId);

        if ($product) {
            $product->updateProductMeta('embedded_video_url', $videoUrl ?: '', 'product_video');
        }
    }
}
