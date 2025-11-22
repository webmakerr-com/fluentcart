<?php
/**
 * Created by PhpStorm.
 * User: sunfun
 * Date: 21.04.17
 * Time: 12:40
 */

function adstm_search(){

    $search_id = uniqid('codex-search-');
    $search_placeholder = __('Search for products, posts or collections', 'rap');
    $search_label = __('Search the store', 'rap');
    $search_button = __('Search', 'rap');
    $search_action = adstm_home_url('/');

    printf(
        '<form method="GET" action="%1$s" class="search-post" role="search" aria-label="%2$s">'
        .'<label class="search-post__label" for="%3$s">%2$s</label>'
        .'<div class="search-post__controls">'
            .'<span class="search-post__icon" aria-hidden="true">'
                .'<svg viewBox="0 0 24 24" role="presentation" focusable="false"><path d="M15.5 14h-.79l-.28-.27A6 6 0 1 0 14 15.5l.27.28v.79L21 22.5 22.5 21zm-6 0a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9z"/></svg>'
            .'</span>'
            .'<input class="js-autocomplete-search search-post__input" autocomplete="off" type="text" id="%3$s" value="" name="s" placeholder="%4$s" required>'
            .'<button type="submit" class="btn btn-primary search-post__submit"><span>%5$s</span></button>'
        .'</div>'
        .'</form>',
        esc_url($search_action),
        esc_html($search_label),
        esc_attr($search_id),
        esc_attr($search_placeholder),
        esc_html($search_button)
    );
}

add_action('adstm_search', 'adstm_search');



function adstm_loginButton(){

    adstm_login_button();
}

add_action('adstm_loginButton', 'adstm_loginButton');

function adstm_cart_quantity_link(){

    $svg_ico = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 34 34" xml:space="preserve">
<g>
	<path d="M31.8,19.8L34,6.5c0.1-0.3,0-0.7-0.2-0.9c-0.2-0.2-0.5-0.4-0.8-0.4H8.2L7.4,0.9C7.3,0.4,6.8,0,6.3,0H1.1
		C0.5,0,0,0.5,0,1.1s0.5,1.1,1.1,1.1h4.3l0.8,4.3l0,0l1.3,6.7l2.1,11.1c0.1,0.5,0.6,0.9,1.1,0.9h20c0.6,0,1.1-0.5,1.1-1.1
		s-0.5-1.1-1.1-1.1H11.6l-0.4-2.2h19.5C31.3,20.7,31.7,20.3,31.8,19.8z M10.8,18.5L8.6,7.4h23l-1.9,11.1
		C29.8,18.5,10.8,18.5,10.8,18.5z"/>
	<path class="icon-cart" d="M14.4,27.4c-1.8,0-3.3,1.5-3.3,3.3s1.5,3.3,3.3,3.3s3.3-1.5,3.3-3.3S16.3,27.4,14.4,27.4z M14.4,31.8
		c-0.6,0-1.1-0.5-1.1-1.1s0.5-1.1,1.1-1.1s1.1,0.5,1.1,1.1S15,31.8,14.4,31.8z"/>
	<path class="icon-cart" d="M27,27.4c-1.8,0-3.3,1.5-3.3,3.3S25.2,34,27,34s3.3-1.5,3.3-3.3S28.8,27.4,27,27.4z M27,31.8
		c-0.6,0-1.1-0.5-1.1-1.1s0.5-1.1,1.1-1.1s1.1,0.5,1.1,1.1S27.6,31.8,27,31.8z"/>
	<path class="icon-cart" d="M20.3,9.6c-0.6,0-1.1,0.5-1.1,1.1v4.4c0,0.6,0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1v-4.4C21.5,10.1,21,9.6,20.3,9.6z
		"/>
	<path class="icon-cart" d="M27,9.6c-0.6,0-1.1,0.5-1.1,1.1v4.4c0,0.6,0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1v-4.4C28.1,10.1,27.6,9.6,27,9.6z"
		/>
	<path class="icon-cart" d="M13.7,9.6c-0.6,0-1.1,0.5-1.1,1.1v4.4c0,0.6,0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1v-4.4
		C14.8,10.1,14.3,9.6,13.7,9.6z"/>
</g>
</svg>';

    printf(
        '<div class="cart">
                    <a href="%1$s">
                        <span class="cart__icon">
                            %2$s
                        </span>
                        <span style="display: none" class="count_item" data-cart="quantity"></span>
                        <span data-cart="pluralize_items"></span>
                    </a>
               </div>',
        esc_url(adstm_home_url('/cart/')),
        $svg_ico
    );

}

add_action('adstm_cart_quantity_link', 'adstm_cart_quantity_link');


add_action('adstm_logo_header', function(){
    $logo_src = cz('tp_logo_img');
    $logo_src = $logo_src ? set_url_scheme($logo_src, is_ssl() ? 'https' : 'http') : '';
    $logo_src = esc_url($logo_src);

    printf(
        ' <div class="logo-box">
                    <div class="logo">
                        <a href="%1$s"><img src="%2$s" alt="%3$s" loading="eager" decoding="async"></a>
                    </div>
                </div>',
        esc_url(adstm_home_url()),
        $logo_src,
        esc_attr(get_bloginfo('name'))
    );
});


add_action('adstm_dropdown_currency', function (){
    ?>
    <div class="ttdropdown dropdown_currency" >
        <span class="ttdropdown-toggle load_currency" ajax_update="currency"></span>
        <ul class="ttdropdown-menu load_currency_target" role="menu"></ul>
    </div>
    <?php
});


add_action('ads_pages_menu', function (){
    $locations = get_nav_menu_locations();
    if(isset($locations['top_menu'])) {
        wp_nav_menu(
            array(
                'menu' => 'Top Menu',
                'theme_location' => 'top_menu',
                'container' => '',
                'menu_class' => '',
                'items_wrap' => '<ul>%3$s</ul>'
            )
        );
    }
});

add_action('ads_menu_product', function(){

    $depth = 3;

    $locations = get_nav_menu_locations();

    if(isset($locations['category']) && $locations['category']){

        $menuProduct = wp_nav_menu(
            array(
	            'container'  => false,
	            'container_class'   => false,
                'theme_location' => 'category',
                'name'=>'Category menu',
                'menu_class'     => '',
                'items_wrap'     => '%3$s',
                'depth'          => $depth,
                'echo'          => false
            )
        );
        $menuProduct = preg_replace( "#<a([^>]*)>([^>]*)<\/a>#", "<a$1><span>$2</span></a>", $menuProduct );
        echo $menuProduct;
    }else {

        $menuProduct = wp_list_categories(
            array(
                'child_of'            => 0,
                'current_category'    => 0,
                'depth'               => $depth,
                'echo'                => 0,
                'exclude'             => '',
                'exclude_tree'        => '',
                'feed'                => '',
                'feed_image'          => '',
                'feed_type'           => '',
                'hide_empty'          => 1,
                'hide_title_if_empty' => false,
                'hierarchical'        => true,
                'order'               => 'ASC',
                'orderby'             => 'name',
                'separator'           => '<br />',
                'show_count'          => 1,
                'show_option_all'     => '',
                'show_option_none'    => '',
                'style'               => 'list',
                'taxonomy'            => 'product_cat',
                'title_li'            => '',
                'use_desc_for_title'  => 0
            )
        );
        $menuProduct = preg_replace( "#<a([^>]*)>([^>]*)<\/a>\s*\(([^>]*)\)#", "<a$1><span>$2</span><i class=\"count\">($3)</i></a>", $menuProduct );

        echo $menuProduct;
    }
});

function ads_menu_product($depth = 3) {

	$locations = get_nav_menu_locations();

	if(isset($locations['category']) && $locations['category']){

		$menuProduct = wp_nav_menu(
			array(
				'container'  => false,
				'container_class'   => false,
				'theme_location' => 'category',
				'name'=>'Category menu',
				'menu_class'     => '',
				'items_wrap'     => '%3$s',
				'depth'          => $depth,
				'echo'          => false
			)
		);
		$menuProduct = preg_replace( "#<a([^>]*)>([^>]*)<\/a>#", "<a$1><span>$2</span></a>", $menuProduct );
		echo $menuProduct;
	}else {

		$menuProduct = wp_list_categories(
			array(
				'child_of'            => 0,
				'current_category'    => 0,
				'depth'               => $depth,
				'echo'                => 0,
				'exclude'             => '',
				'exclude_tree'        => '',
				'feed'                => '',
				'feed_image'          => '',
				'feed_type'           => '',
				'hide_empty'          => 1,
				'hide_title_if_empty' => false,
				'hierarchical'        => true,
				'order'               => 'ASC',
				'orderby'             => 'name',
				'separator'           => '<br />',
				'show_count'          => 1,
				'show_option_all'     => '',
				'show_option_none'    => '',
				'style'               => 'list',
				'taxonomy'            => 'product_cat',
				'title_li'            => '',
				'use_desc_for_title'  => 0
			)
		);
		$menuProduct = preg_replace( "#<a([^>]*)>([^>]*)<\/a>\s*\(([^>]*)\)#", "<a$1><span>$2</span><i class=\"count\">($3)</i></a>", $menuProduct );

		echo $menuProduct;
	}
};


add_action('adstm_phone_shop', function($icon = true){

    if ( cz( 'tp_header_phone' ) )
        printf('<a href="tel:%1$s" class="tel">%2$s%1$s</a>',
            cz( 'tp_header_phone' ),
            $icon ? '<i class="fa '.cz('tp_icon_phone').'" aria-hidden="true"></i>':'' //custom icon
        );
});

add_action('adstm_email_shop', function(){
    if ( cz( 'tp_header_email' ) )
        printf('<a href="mailto:%1$s" class="email">%2$s%1$s</a>',
            cz( 'tp_header_email' ),
            true ? '<i class="fa fa-envelope-o" aria-hidden="true"></i>':'' //custom icon
        );
});

add_action('adstm_shipping_icon', function(){
    if(cz('shipping_icon')){
        echo '<img src="'.cz('shipping_icon').'" alt="">';
    }else{
        echo '<i class="icon-truck"></i>';
    }
});
