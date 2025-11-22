<?php
do_action('cz_menu_init');

if (function_exists('init_cz')) {
    init_cz();
}

add_action( 'template_redirect', function(){

    if (
        ( defined( 'ADS_ERROR' ) && ADS_ERROR ) ||
        ( defined( 'SLV_ERROR' ) && SLV_ERROR )
    ) {
        get_template_part( 'empty' );
        exit;
    }
}, 1 );

remove_action('wp_head', 'wp_print_scripts');
remove_action('wp_head', 'wp_print_head_scripts', 9);
//remove_action('wp_head', 'wp_enqueue_scripts', 1);

add_action('wp_footer', 'wp_print_scripts', 5);
add_action('wp_footer', 'wp_enqueue_scripts', 5);
add_action('wp_footer', 'wp_print_head_scripts', 5);

include( __DIR__ . '/adstm/init.php' );

include( __DIR__ . '/hooks/init.hooks.php' );

include( __DIR__ . '/inc/breadcrumbs.php' );
include( __DIR__ . '/inc/review.php' );
include( __DIR__ . '/inc/instagram.php' );

add_action( 'wp_enqueue_scripts', function () {
        $coupon_fix_path = get_template_directory() . '/js/fluentcart-fixes.js';

        wp_enqueue_script(
                'webmakerr-fluentcart-fixes',
                get_template_directory_uri() . '/js/fluentcart-fixes.js',
                [],
                file_exists( $coupon_fix_path ) ? filemtime( $coupon_fix_path ) : null,
                true
        );
} );


if ( ! function_exists( 'raphael_is_item_permalink' ) ) {
        /**
         * Check whether the current singular permalink begins with /item/.
         *
         * @return bool
         */
        function raphael_is_item_permalink() {
                if ( ! is_singular() ) {
                        return false;
                }

                $permalink = get_permalink();

                if ( empty( $permalink ) ) {
                        return false;
                }

                $path = wp_parse_url( $permalink, PHP_URL_PATH );

                if ( ! is_string( $path ) ) {
                        return false;
                }

                return strpos( $path, '/item/' ) === 0;
        }
}

add_action( 'wp_head', function () {
        if ( ! raphael_is_item_permalink() ) {
                return;
        }

        ?>
        <style>
                .blog-single-data__local-info-author,
                .blog-single-data__local-info-date-and-comments,
                .blog-single-data__local-info-views-count,
                .blog-single-data__social-info,
                .blog-breadcrumbs,
                [class*="breadcrumb"],
                .blog-top-full-screen-block__inner,
                .blog-top-category-menu-wrap,
                #comments,
                .comments-area,
                .comment-respond,
                #respond,
                #commentform,
                .comment-form,
                .comment-form-comment,
                .comment-list,
                .comment,
                .comment-body,
                .comment-meta,
                .comment-notes,
                .comment-reply-title,
                .comments-title,
                .logged-in-as,
                .blog-content__sidebar {
                        display: none !important;
                }

                .blog-content__main {
                        width: 100% !important;
                        max-width: 100% !important;
                }
        </style>
        <?php
}, 99 );


add_action('cz_change_options', function (){
    //adstm_instagram::clearCache();
});


/**
 * Remove adminbar for subscribers
 */

if ( is_user_logged_in() && ! current_user_can( "level_2" ) ) {
	add_filter( 'show_admin_bar', '__return_false' );
}

/**
 * Enable responsive video (bootstrap only)
 *
 * @param $html
 * @param $url
 * @param $attr
 * @param $post_ID
 *
 * @return string
 */
function adstm_oembed_filter( $html, $url, $attr, $post_ID ) {
        return '<div class="ratio ratio-16x9">' . $html . '</div>';
}

add_filter( 'embed_oembed_html', 'adstm_oembed_filter', 10, 4 );


/**
 * Convert post_content \n
 *
 * @param $content
 *
 * @return mixed
 */
if ( ! function_exists( 'nl2br_content' ) ) {
	function nl2br_content( $content ) {
		$content = apply_filters( 'the_content', $content );

		return str_replace( ']]>', ']]>', $content );
	}
}

/* Lazy load of images for product's single page */

function getFullUrlImg_tm( $url ) {

    return preg_replace( '/_\d+x\d+\.jpg$/', '', $url );
}

function getFullUrlImgWebp_tm( $url ) {

    return preg_replace( '/_\d+x\d+q80\.webp$/', '', $url );
}

function getFullUrlImgJpgWebp_tm( $url ) {

    return preg_replace( '/_\d+x\d+q80\.jpg\.webp$/', '', $url );
}

/* Lazy load of images for product's single page */

// To edit single product's description's content for images lazy loading
function change_single_product_content_for_img_lazy_load( $content ) {

    global $post;

    $use_regex = false;

    // only edit specific post types
    $types = [ 'product' ];
    if ( $post && in_array( $post->post_type, $types, true ) ) {

        if( $use_regex ) {

            $content = preg_replace_callback(
                "|src=|",
                function ($match) {
                    return "data-src=";
                },
                $content
            );

            $content = preg_replace_callback(
                "|srcset=|",
                function ($match) {
                    return "data-srcset=";
                },
                $content
            );
        } else {

            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            foreach ($dom->getElementsByTagName('img') as $node) {
                $src_attr = $node->getAttribute('src');
                if(stristr($src_attr, 'alicdn.com')){
                    $src_attr = getFullUrlImg_tm( $src_attr );
                    $src_attr = getFullUrlImgWebp_tm( $src_attr );
                    $src_attr = getFullUrlImgJpgWebp_tm( $src_attr );
                    $src_attr = str_replace ('.jpg', '.jpg_.webp', $src_attr);
                }
                $node->setAttribute("data-src", $src_attr );
                $node->removeAttribute("src");
            }
            foreach ($dom->getElementsByTagName('iframe') as $node) {
                $src_attr = $node->getAttribute('src');
                $node->setAttribute("data-src", $src_attr );
                $node->removeAttribute("src");
            }
            $content = $dom->saveHtml();
            $content = preg_replace_callback(
                "|<body>|",
                function ($match) {
                    return "";
                },
                $content
            );
            $content = preg_replace_callback(
                "|</body>|",
                function ($match) {
                    return "";
                },
                $content
            );
            $content = preg_replace_callback(
                "|<html>|",
                function ($match) {
                    return "";
                },
                $content
            );
            $content = preg_replace_callback(
                "|</html>|",
                function ($match) {
                    return "";
                },
                $content
            );
            $content = preg_replace_callback(
                "|<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">|",
                function ($match) {
                    return "";
                },
                $content
            );

        }
    }

    return $content;
}

function change_single_product_content_for_img_lazy_load_srcset( $content ) {

    global $post;


    // only edit specific post types
    $types = [ 'product' ];
    if ( $post && in_array( $post->post_type, $types, true ) ) {

        $content = preg_replace_callback(
            "|srcset=|",
            function ($match) {
                return "data-srcset=";
            },
            $content
        );
    }

    return $content;
}

add_action( 'init', function() {
    $enable_single_page_optimize_content = cz( 'tp_item_imgs_lazy_load' );

    if ( ! $enable_single_page_optimize_content ) {
        return;
    }

    // add the filter when main loop starts
    add_action( 'loop_start', function( WP_Query $query ) {
        if ( $query->is_main_query() ) {
            add_filter( 'the_content', 'change_single_product_content_for_img_lazy_load', -10 );
            add_filter( 'the_content', 'change_single_product_content_for_img_lazy_load_srcset', 11 );
        }
    } );

    // remove the filter when main loop ends
    add_action( 'loop_end', function( WP_Query $query ) {
        if ( has_filter( 'the_content', 'change_single_product_content_for_img_lazy_load' ) ) {
            remove_filter( 'the_content', 'change_single_product_content_for_img_lazy_load' );
            remove_filter( 'the_content', 'change_single_product_content_for_img_lazy_load_srcset' );
        }
    } );
} );

/* END Lazy load of images for product's single page */


function theme_get_icon( $name, $color ) {

    $file = dirname( __FILE__ ) . '/images/svg-icons/' . $name . '.svg';

    if ( file_exists( $file ) ) {
        ob_start();
        include( $file );
        $text = ob_get_contents();
        ob_end_clean();

        return $text;
    }

    return '';

}

function tmpCz($name, $tmp){
	$value = cz($name);
	if(!$value){
		return;
	}

	printf( $tmp, $value );
}

// return title for img
function lcb_restore_image_title( $html, $id ) {

    $attachment = get_post($id);
    $mytitle = $attachment->post_title;
    return str_replace('<img', '<img title="' . $mytitle . '" ' , $html);
}
add_filter( 'media_send_to_editor', 'lcb_restore_image_title', 15, 2 );


function image_host_remove() {
    $theme               = wp_get_theme();
    $field_options = 'cz_' . $theme->get( 'Name' );
    $data = get_option( $field_options );
    $cur_website = parse_url(ADSTM_HOME, PHP_URL_HOST);
    $new_data = json_decode(str_replace([ 'http:\/\/'.$cur_website, 'https:\/\/'.$cur_website,'\/\/'.$cur_website], '', json_encode($data)), true);
    $new_data['image_clean'] = 1;
    $rez  = update_option( $field_options, $new_data );
}

add_action( 'after_switch_theme', 'image_host_remove',40  );

function ads_load_countries() {
    echo '<select name="Addreview[from]" id="ads_review_shipping_countries" class="form-select" data-ttselect data-search >';
    ads_get_list_contries();
    echo '</select>';
    die();
}
add_action( 'wp_ajax_load_countries', 'ads_load_countries' );
add_action( 'wp_ajax_nopriv_load_countries', 'ads_load_countries' );


function ads_select_currency_page() {

    if(isset($_POST['page'])){
        $ads_get_list_currency = ads_get_list_currency();
        $args = array_intersect_key(
            $ads_get_list_currency,
            array_flip( ads_list_currency() )
        );

        $template = '<li><a href="%1$s"><b class=""><img src="%3$s" alt=""></b>%2$s</a></li>';

        $template = apply_filters( 'ads_select_currency_template', $template );
        $args     = apply_filters( 'ads_select_currency', $args );

        $list = '';
        foreach ( $args as $key => $val ) {
            $list .= sprintf( $template, esc_url( add_query_arg( 'cur', $key, $_POST['page'] ) ), $val[ 'title' ] , pachFlag($ads_get_list_currency[$key]['flag']) );
        }

        echo $list;
    }else{
        echo '';
    }
    die;

}
add_action( 'wp_ajax_get_currency_select', 'ads_select_currency_page' );
add_action( 'wp_ajax_nopriv_get_currency_select', 'ads_select_currency_page' );



function do_webp_to_png() {
    $media_query = new WP_Query(
        array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        )
    );
    $list = array();
    foreach ($media_query->posts as $post) {
        $list[] = wp_get_attachment_url($post->ID);
    }
    $cur_website = parse_url(ADSTM_HOME, PHP_URL_HOST);

    foreach ($list as $list_one) {
        if(strpos($list_one, 'webp')){

            $local_list_one = str_replace([ 'http://'.$cur_website, 'https://'.$cur_website,'//'.$cur_website,'https:','http:'], '', $list_one);

            $min_50 = '-50x50';
            $min_150 = '-150x150';
            $min_220 = '-220x220';
            $min_300 = '-300x300';
            $min_350 = '-350x350';
            $min_640 = '-640x640';

            $normal_path = str_replace("//", "/", ABSPATH.$local_list_one);

            $normal_path_50 = str_replace(".webp", $min_50.".webp", $normal_path);
            $normal_path_150 = str_replace(".webp", $min_150.".webp", $normal_path);
            $normal_path_220 = str_replace(".webp", $min_220.".webp", $normal_path);
            $normal_path_300 = str_replace(".webp", $min_300.".webp", $normal_path);
            $normal_path_350 = str_replace(".webp", $min_350.".webp", $normal_path);
            $normal_path_640 = str_replace(".webp", $min_640.".webp", $normal_path);


            $new_png = str_replace("webp", "png", $normal_path);
            $new_png_50 = str_replace("webp", "png", $normal_path_50);
            $new_png_150 = str_replace("webp", "png", $normal_path_150);
            $new_png_220 = str_replace("webp", "png", $normal_path_220);
            $new_png_300 = str_replace("webp", "png", $normal_path_300);
            $new_png_350 = str_replace("webp", "png", $normal_path_350);
            $new_png_640 = str_replace("webp", "png", $normal_path_640);




            if (!file_exists( $new_png ) ) {
                $im = imagecreatefromwebp($normal_path);
                imagepng($im, $new_png);
                imagedestroy($im);

                $im_50 = imagecreatefromwebp($normal_path_50);
                imagepng($im_50, $new_png_50);
                imagedestroy($im_50);

                $im_150 = imagecreatefromwebp($normal_path_150);
                imagepng($im_150, $new_png_150);
                imagedestroy($im_150);

                $im_220 = imagecreatefromwebp($normal_path_220);
                imagepng($im_220, $new_png_220);
                imagedestroy($im_220);

                $im_300 = imagecreatefromwebp($normal_path_300);
                imagepng($im_300, $new_png_300);
                imagedestroy($im_300);

                $im_350 = imagecreatefromwebp($normal_path_350);
                imagepng($im_350, $new_png_350);
                imagedestroy($im_350);

                $im_640 = imagecreatefromwebp($normal_path_640);
                imagepng($im_640, $new_png_640);
                imagedestroy($im_640);
            }


        }
    }

    echo 'done';
    die;

}

add_action( 'wp_ajax_load_webp_png', 'do_webp_to_png' );
add_action( 'wp_ajax_nopriv_load_webp_png', 'do_webp_to_png' );

// Disable the Gutenberg block editor and related features globally.
// Disable Gutenberg only for pages and posts
function wm_disable_gutenberg_for_posts_pages( $use_block_editor, $post ) {
    if ( $post->post_type === 'page' || $post->post_type === 'post' ) {
        return false; // Force Classic Editor
    }
    return $use_block_editor; // Keep Gutenberg for everything else
}
add_filter( 'use_block_editor_for_post', 'wm_disable_gutenberg_for_posts_pages', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'wm_disable_gutenberg_for_posts_pages', 10, 2 );

