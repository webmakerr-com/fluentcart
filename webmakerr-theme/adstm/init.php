<?php

if ( ! function_exists( 'adstm_get_theme_defaults' ) ) {
    function adstm_get_theme_defaults() {
        if ( ! defined( 'ADSTM_HOME' ) ) {
            define( 'ADSTM_HOME', home_url() );
        }

        if ( ! defined( 'TEMPLATE_URL' ) ) {
            define( 'TEMPLATE_URL', get_template_directory_uri() );
        }

        if ( ! defined( 'IMG_DIR' ) ) {
            define( 'IMG_DIR', trailingslashit( get_template_directory_uri() ) . 'images/' );
        }

        $defaults = include __DIR__ . '/customization/defaults.php';

        return is_array( $defaults ) ? $defaults : [];
    }
}

if ( ! function_exists( 'adstm_get_theme_options' ) ) {
    function adstm_get_theme_options( $refresh = false ) {
        static $options;

        if ( isset( $options ) && ! $refresh ) {
            return $options;
        }

        $saved_options = get_option( 'raphael_theme_settings', [] );
        $defaults      = adstm_get_theme_defaults();

        if ( ! is_array( $saved_options ) ) {
            $saved_options = [];
        }

        $options = wp_parse_args( $saved_options, $defaults );

        return $options;
    }
}

if ( ! function_exists( 'adstm_setup_theme_options' ) ) {
    function adstm_setup_theme_options() {
        $options  = adstm_get_theme_options();
        $defaults = adstm_get_theme_defaults();

        update_option( 'raphael_theme_settings', wp_parse_args( $options, $defaults ) );
    }
}
add_action( 'after_switch_theme', 'adstm_setup_theme_options' );

if ( ! function_exists( 'cz' ) ) {
    function cz( $name, $default = '' ) {
        $options = adstm_get_theme_options();

        return isset( $options[ $name ] ) ? $options[ $name ] : $default;
    }
}

if ( ! function_exists( '_cz' ) ) {
    function _cz( $name, $default = '' ) {
        echo cz( $name, $default );
    }
}


if(!defined('ADSTM_HOME')){
	define('ADSTM_HOME', home_url());
}

if(!defined('ADSTM_T_DOMAIN')){
        define('ADSTM_T_DOMAIN', 'rap');
}

require_once __DIR__ . '/customization/czOptions.php';

include( __DIR__ . '/update.php' );
include( __DIR__ . '/core.php' );

include( __DIR__ . '/adsTmpl.php' );

include( __DIR__ . '/widget/countdown.php' );

include( __DIR__ . '/account.php' );
include( __DIR__ . '/blog.php' );
include( __DIR__ . '/handler_contact_form.php' );

include( __DIR__ . '/shortcodes/theme_shortcodes.php' );

if ( defined( 'ADS_ERROR' ) && ! ADS_ERROR ) {
	include( __DIR__ . '/alids.php' );
}

if( defined( 'SLV_ERROR' ) && ! SLV_ERROR ) {
    include( __DIR__ . '/alids.php' );
}

if ( is_admin() ) {
        include( __DIR__ . '/setup/create_page_template.php' );
}

function adstm_register_theme_menu() {
    add_theme_page(
        __( 'Raphael Theme Settings', 'rap' ),
        __( 'Raphael Settings', 'rap' ),
        'manage_options',
        'raphael-theme-settings',
        'adstm_render_theme_settings_page'
    );
}
add_action( 'admin_menu', 'adstm_register_theme_menu' );

function adstm_register_additional_settings() {
    register_setting(
        'raphael_theme_settings_group',
        'raphael_webhook_url',
        [
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
        ]
    );
}
add_action( 'admin_init', 'adstm_register_additional_settings' );

function adstm_render_theme_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $json_error = false;

    if ( isset( $_POST['adstm_save_settings'] ) && check_admin_referer( 'adstm_save_settings_action' ) ) {
        $options  = adstm_get_theme_options();
        $updated  = [];

        foreach ( $options as $key => $value ) {
            $field_key = 'adstm_option_' . $key;

            if ( is_bool( $value ) ) {
                $updated[ $key ] = isset( $_POST[ $field_key ] );
                continue;
            }

            if ( is_array( $value ) ) {
                $raw_value = isset( $_POST[ $field_key ] ) ? wp_unslash( $_POST[ $field_key ] ) : '';
                $decoded   = json_decode( $raw_value, true );

                if ( JSON_ERROR_NONE === json_last_error() && is_array( $decoded ) ) {
                    $updated[ $key ] = $decoded;
                } else {
                    $updated[ $key ] = $value;
                    $json_error       = true;
                }

                continue;
            }

            $raw_value       = isset( $_POST[ $field_key ] ) ? wp_unslash( $_POST[ $field_key ] ) : '';
            $updated[ $key ] = sanitize_text_field( $raw_value );
        }

        $webhook_url = isset( $_POST['raphael_webhook_url'] ) ? wp_unslash( $_POST['raphael_webhook_url'] ) : '';
        update_option( 'raphael_webhook_url', esc_url_raw( $webhook_url ) );

        $defaults = adstm_get_theme_defaults();
        update_option( 'raphael_theme_settings', wp_parse_args( $updated, $defaults ) );
        adstm_get_theme_options( true );

        echo '<div class="updated"><p>' . esc_html__( 'Theme settings were saved.', 'rap' ) . '</p></div>';

        if ( $json_error ) {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Some complex settings could not be saved because the JSON format is invalid. Please check your entries and try again.', 'rap' ) . '</p></div>';
        }
    }

    if ( isset( $_POST['adstm_reset_defaults'] ) && check_admin_referer( 'adstm_reset_defaults_action' ) ) {
        adstm_setup_theme_options();
        echo '<div class="updated"><p>' . esc_html__( 'Theme settings were refreshed from defaults.', 'rap' ) . '</p></div>';
    }

    $options = adstm_get_theme_options( true );
    $webhook_url = get_option( 'raphael_webhook_url', '' );

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Raphael Theme Settings', 'rap' ); ?></h1>
        <p><?php esc_html_e( 'These values are loaded automatically when the theme is activated. Use the Customizer to adjust them or reload the defaults below.', 'rap' ); ?></p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
                <?php esc_html_e( 'Open Customizer', 'rap' ); ?>
            </a>
        </p>
        <form method="post">
            <?php wp_nonce_field( 'adstm_reset_defaults_action' ); ?>
            <input type="hidden" name="adstm_reset_defaults" value="1" />
            <?php submit_button( __( 'Reload Default Settings', 'rap' ), 'secondary' ); ?>
        </form>

        <h2><?php esc_html_e( 'Update Settings', 'rap' ); ?></h2>
        <form method="post">
            <?php settings_fields( 'raphael_theme_settings_group' ); ?>
            <?php wp_nonce_field( 'adstm_save_settings_action' ); ?>
            <input type="hidden" name="adstm_save_settings" value="1" />

            <h3><?php esc_html_e( 'Webhook Settings', 'rap' ); ?></h3>
            <table class="widefat striped" style="margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <td style="width: 25%;">
                            <label for="raphael_webhook_url"><code>raphael_webhook_url</code></label>
                        </td>
                        <td>
                            <input type="text" id="raphael_webhook_url" name="raphael_webhook_url" value="<?php echo esc_attr( $webhook_url ); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e( 'Webhook endpoint used by the Contact Us form.', 'rap' ); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Option', 'rap' ); ?></th>
                        <th><?php esc_html_e( 'Value', 'rap' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $options as $key => $value ) : ?>
                        <tr>
                            <td style="width: 25%;"><label for="<?php echo esc_attr( 'adstm_option_' . $key ); ?>"><code><?php echo esc_html( $key ); ?></code></label></td>
                            <td>
                                <?php if ( is_bool( $value ) ) : ?>
                                    <label>
                                        <input type="checkbox" id="<?php echo esc_attr( 'adstm_option_' . $key ); ?>" name="<?php echo esc_attr( 'adstm_option_' . $key ); ?>" <?php checked( $value ); ?> />
                                        <?php esc_html_e( 'Enabled', 'rap' ); ?>
                                    </label>
                                <?php elseif ( is_array( $value ) ) : ?>
                                    <textarea id="<?php echo esc_attr( 'adstm_option_' . $key ); ?>" name="<?php echo esc_attr( 'adstm_option_' . $key ); ?>" rows="6" class="large-text code"><?php echo esc_textarea( wp_json_encode( $value, JSON_PRETTY_PRINT ) ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Edit array values using JSON format.', 'rap' ); ?></p>
                                <?php else : ?>
                                    <input type="text" id="<?php echo esc_attr( 'adstm_option_' . $key ); ?>" name="<?php echo esc_attr( 'adstm_option_' . $key ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" class="regular-text" />
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php submit_button( __( 'Save Settings', 'rap' ) ); ?>
        </form>
    </div>
    <?php
}

function adstm_lang_init() {
    load_theme_textdomain( 'rap' );
}

add_action( 'init', 'adstm_lang_init' );

/**
 * Add theme support for Featured Images
 */
add_theme_support( 'post-thumbnails' );

/**
 * Register primary menu
 */
register_nav_menus( array(
	'top_menu' => 'Top Menu',
	'category' => 'Category menu',
	'footer-company'  => 'Company Info',
	'footer-help'  => 'Need Some Help?',
) );


/**
 * Filter to name pages
 *
 * @param $pagename
 *
 * @return string
 */
add_filter( 'ads_template_pagename', function ( $pagename ) {
	return str_replace('page.', 'page-', $pagename);
}, 1000 );


/**
 * Enqueue script
 */
function adstm_enqueue_script() {
	$adstm_theme = wp_get_theme();
	$version = $adstm_theme->get( 'Version' );

	wp_register_script( 'socials', get_template_directory_uri() . '/assets/js/socials.js', array( 'jquery' ), $version, true );
	// Facebook SDK
	wp_register_script( 'facebook', sprintf( '//connect.facebook.net/%1$s/sdk.js#xfbml=1&version=v2.5&appId=1049899748393568', get_bloginfo( 'language' ) ), array(), $version, true );

    wp_register_script( 'bootstrap-tmpl', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true );
    wp_register_script( 'bootstrap-init', get_template_directory_uri() . '/assets/js/bootstrap-init.js', array( 'bootstrap-tmpl' ), $version, true );

    wp_register_script( 'ttgallery', get_template_directory_uri() . '/assets/js/ttgallery.js', array( 'jquery' ), $version, true );
    wp_register_script( 'selects', get_template_directory_uri() . '/assets/js/selects.js', array( 'jquery' ), $version, true );

	wp_register_script('baguetteBox', get_template_directory_uri() . '/assets/js/baguetteBox.js', array(), $version, true);

    wp_register_script( 'slideout', get_template_directory_uri() . '/frontend-libs/slideout/dist/slideout.min.js', array( 'jquery' ), '1.0.1', true );

    wp_register_script( 'ttlazy', get_template_directory_uri() . '/assets/js/ttlazy.min.js', array( 'jquery' ), $version, true );

    wp_register_script('common-tmpl', get_template_directory_uri() . '/assets/js/common.js', array('jquery', 'common'),$version, true);
    wp_register_script('home-tmpl', get_template_directory_uri() . '/js/home.js',array('jquery', 'common','ttgallery'), $version, true);
    wp_register_script('header-tmpl', get_template_directory_uri() . '/js/header.js',array('jquery', 'slideout', 'front-cart', 'common'), $version, true);
    wp_register_script('single-product-tmpl', get_template_directory_uri() . '/js/single_product.js',array('jquery', 'common', 'front-cart','ttgallery','baguetteBox'), $version, true);
    wp_register_script('blog-tmpl', get_template_directory_uri() . '/js/blog.js',array('jquery'), $version, true);
    wp_register_script( 'rap_lity', get_template_directory_uri() . '/assets/js/lity.min.js','' , $version, true );

    /**/
    wp_register_script( 'adstm', get_template_directory_uri() . '/js/script.js', array(
                'jquery',
                'ttlazy',
                'bootstrap-init',
                'common-tmpl',
                'front-cart',
                'selects',
                'header-tmpl',

        ), $version, true );

	wp_localize_script( 'adstm', 'tmplLang',
		array(
			'readonly_checkbox' => __('Please tick on Terms & Conditions box', 'rap')
		));

	wp_localize_script( 'adstm', 'adstmCustomize',
		adsTmpl::customizeJsParams( ) );



	if(cz('s_link_fb')){
        wp_enqueue_script( 'facebook');
    }

    $pageName = get_query_var( 'pagename' );

        if($pageName !== 'cart'){
        wp_enqueue_script( 'slideout' );
        wp_enqueue_script( 'adstm' );
    }else{
        wp_enqueue_script( 'bootstrap-tmpl' );
        wp_enqueue_script( 'bootstrap-init' );
        }

	enabledJsCurrentPage();
}

function adstm_enqueue_style_header() {

	if ( adsTmpl::is_home() ) {
		wp_enqueue_style( 'home-css-tmpl', get_template_directory_uri() . '/assets/css/home.css' );
        wp_enqueue_style( 'rap_lity_css', get_template_directory_uri() . '/assets/css/lity.min.css' );
        wp_enqueue_style( 'ttgallery', get_template_directory_uri() . '/assets/css/ttgallery.css' );
	}  

	/*category*/
	if ( is_post_type_archive('product') || is_tax( 'product_cat' ) ){
		wp_enqueue_style( 'category-css-tmpl', get_template_directory_uri() . '/assets/css/category.css' );
	}elseif( is_archive() ){
		wp_enqueue_style( 'blog-css-tmpl', get_template_directory_uri() . '/assets/css/blog.css' );
	}

	/*search*/
	if(is_search()){
		/*blog*/
		if(isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] == 'post'){
			wp_enqueue_style( 'blog-css-tmpl', get_template_directory_uri() . '/assets/css/blog.css' );
		}else{
			wp_enqueue_style( 'category-css-tmpl', get_template_directory_uri() . '/assets/css/category.css' );
		}
	}

	/*single*/
	if ( is_singular( 'product' ) ) {
        wp_enqueue_style( 'ttgallery', get_template_directory_uri() . '/assets/css/ttgallery.css' );
		wp_enqueue_style( 'single-product-tmpl', get_template_directory_uri() . '/assets/css/single-product.css' );
        wp_enqueue_style( 'rap_lity_css', get_template_directory_uri() . '/assets/css/lity.min.css' );
		/*blog*/
	}elseif(is_singular('post')|| is_home()){
		wp_enqueue_style( 'blog-css-tmpl', get_template_directory_uri() . '/assets/css/blog.css' );
	}

	$foo = array(
		'cart'=> array('cart' ),
	);

	$pageName = get_query_var( 'pagename' );
	if(isset($foo[$pageName])){

		$script = $foo[$pageName];

		foreach ($script as $value){
			wp_enqueue_style( 'style-tmpl-'.$value, get_template_directory_uri() . '/assets/css/'.$value.'.css' );
		}
	}

	if(!isset($_GET['redirect']) && in_array($pageName, array('account','confirmation', 'userlogin', 'register', 'orders'))){
		wp_enqueue_style( 'style-tmpl-account', get_template_directory_uri() . '/assets/css/account.css' );
	}

	if(isset($_GET['redirect']) && in_array($pageName, array('account','confirmation', 'userlogin', 'register', 'orders'))){
		wp_enqueue_style( 'style-tmpl-account-cart', get_template_directory_uri() . '/assets/css/account-cart.css' );
	}

};
add_action( 'get_header', 'adstm_enqueue_style_header', 10);

function enabledJsCurrentPage(){

	if (  adsTmpl::is_home() ) {
		wp_enqueue_script('home-tmpl');
        wp_enqueue_script('rap_lity');

	}

	if ( is_singular( 'product' ) ) {
        wp_enqueue_script('ttlazy');
		wp_enqueue_script('single-product-tmpl');
		wp_enqueue_script('socials');
        wp_enqueue_script('rap_lity');
		//wp_enqueue_script( 'front-add-review' );
		/*blog*/
	} elseif(is_singular('post') || is_home()) {
		wp_enqueue_script( 'blog-tmpl');
		wp_enqueue_script( 	'socials');
	}
	global $wp_query;

	if(is_post_type_archive('product') || is_tax( 'product_cat' ) ){

	}elseif(is_archive()){
		wp_enqueue_script( 'blog-tmpl');
	}


	$foo = array(
		'account'=> array('front-account' ),
		'userlogin'=> array('front-userlogin' ),
		'orders'=> array('front-pagination', 'front-orders' ),
		'register'=> array(array('name' => 'front-recaptcha-script'), array( 'name' => 'front-register-account', 'footer'=>true) ),
		'contact-us'=> ['front-recaptcha-script']
	);

	$pageName = get_query_var( 'pagename' );
	if(isset($foo[$pageName])){

		$script = $foo[$pageName];

		foreach ($script as $value){
			if(!is_array($value)){
				wp_enqueue_script( $value , '', '', '', true);
			}else{
                if (isset($value['footer'])) {
                    wp_enqueue_script( $value['name'] , '', '', '', $value['footer']);
                }
			}
		}
		do_action('adstm_enable_'.$pageName);
	}

}

add_action( 'wp_enqueue_scripts', 'adstm_enqueue_script' );



/**
 * Filter to excerpt
 *
 * @param $length
 *
 * @return int
 */
function adstm_excerpt_length( $length ) {
	return 50;
}

add_filter( 'excerpt_length', 'adstm_excerpt_length' );

/**
 * Excerpt after text
 *
 * @param $more
 *
 * @return string
 */
function adstm_excerpt_more( $more ) {
	return "...";
}

add_filter( 'excerpt_more', 'adstm_excerpt_more' );

/**
 * @param $classes
 *
 * @return array
 */
function adstm_body_classes( $classes ) {
	$pagename = get_query_var( 'pagename' );
	$classes[] = $pagename;
	return $classes;
}

add_filter( 'body_class', 'adstm_body_classes' );


add_filter( 'get_the_archive_title', function ( $title ) {
	if ( is_category() || is_tax() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_search() ) {
		$title = sprintf( '%1$s: "%2$s"', __( 'Search', 'rap'), get_search_query() );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = sprintf( '<span class="vcard">%s</span>', get_the_author() );
	}

	return $title;
} );

function top_product() {

    $category = intval($_POST[ 'category' ]);
	$link = isset($_POST[ 'link' ]) ? strip_tags($_POST[ 'link' ]) : false;

    $terms = basename(parse_url($link, PHP_URL_PATH));
    $terms = str_replace('#', '', $terms);

    $args = array(
        'post_type'      => 'product',
        '_orderby'       => 'promotionVolume',
        '_order'         => 'DESC',
        'posts_per_page'    => 4,
        'post_status'      => 'publish',
    );

	if($category > 0){
		$args['tax_query'] = [
			[
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $category
			]
		];
	}elseif($terms){
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $terms
            ]
        ];
    }

    $foo = [];

    query_posts( $args );

    if ( have_posts() ) : while ( have_posts() ) :
        the_post();

    $info = new adsProductTM();

    $product = $info->singleProductMin('ads-medium');

    $foo[] = [
        'thumb'    => $product['thumb'],
        'permalink' => $info->getLink(),
        'title' => stripslashes( html_entity_decode( $info->getTitle(), ENT_QUOTES ))
    ];

    endwhile; endif;

    wp_reset_query();

    wp_send_json( $foo );
}

add_action( 'wp_ajax_nopriv_top_product', 'top_product' );
add_action( 'wp_ajax_top_product', 'top_product' );


function top_product2() {

    $foo = [];
    foreach ($_POST['prepare_prod'] as $key => $prod_cat) {
        $category = intval($prod_cat[0]);
        $link = isset($prod_cat[1]) ? strip_tags($prod_cat[1]) : false;
        $foo[$key] = [];


        $terms = basename(parse_url($link, PHP_URL_PATH));
        $terms = str_replace('#', '', $terms);

        $args = array(
            'post_type'      => 'product',
            '_orderby'       => 'promotionVolume',
            '_order'         => 'DESC',
            'posts_per_page'    => 4,
            'post_status'      => 'publish',
        );

        if($category > 0){
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category
                ]
            ];
        }elseif($terms){
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $terms
                ]
            ];
        }



        query_posts( $args );

        if ( have_posts() ) : while ( have_posts() ) :
            the_post();

            $info = new adsProductTM();

            $product = $info->singleProductMin('ads-medium');

            $foo[$key][] = [
                'thumb'    => $product['thumb'],
                'permalink' => $info->getLink(),
                'title' => stripslashes( html_entity_decode( $info->getTitle(), ENT_QUOTES ))
            ];

        endwhile; endif;

        wp_reset_query();

    }

    wp_send_json( $foo );
}

add_action( 'wp_ajax_nopriv_top_product2', 'top_product2' );
add_action( 'wp_ajax_top_product2', 'top_product2' );


/* Disable emoji icons */

/**
 * Disable the emoji's
 */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

        $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }

    return $urls;
}


function my_menu_notitle( $menu ){
    return $menu = preg_replace('/title=\"(.*?)\"/Uui', '', $menu );

}
add_filter( 'wp_nav_menu', 'my_menu_notitle', 999 );
add_filter( 'wp_page_menu', 'my_menu_notitle', 999 );
add_filter( 'wp_list_categories', 'my_menu_notitle', 999 );

add_filter('comment_form_defaults', function ($fields){
    $fields['label_submit'] = __('Submit a review', 'rap');
    return $fields;
});

add_action('wp_head', 'adstm_blog_og');

function adstm_blog_og(){

    if(!is_single() || get_post_type() !== 'post'){
        return;
    }

    $url = get_the_post_thumbnail_url();

    if(!$url){
        return;
    }

    printf('<meta property="og:image" content="%s" />', $url);

}