<?php

if ( have_posts() ) : while ( have_posts() ) :
	the_post();
	do_action('adstm_iterator_loop_product');

	echo '<div class="col-6 col-sm-3 col-md-3 col-lg-3 item"><div class="wrap_product">';

	do_action('adstm_product_item', 'ads-big');

	echo '</div></div>';
endwhile; endif;

do_action('adstm_end_loop_product');