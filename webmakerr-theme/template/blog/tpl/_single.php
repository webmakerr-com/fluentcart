<?php while ( have_posts() ) : the_post(); ?>

    <?php

    global $post;

    $post_id              = get_the_ID();
    $post_category_links  = get_the_category_list( ', ', 1 );
    $post_author          = get_the_author();
    $post_date            = date_i18n( 'M j, Y', strtotime( get_the_date() ) );
    $post_comments_number = get_comments_number( $post_id );
    $post_views_count     = getPostViews($post->ID);
    $is_item_single_post  = get_query_var( 'raphael_is_item_single_post', null );

    if ( null === $is_item_single_post || '' === $is_item_single_post ) {
        $is_item_single_post = (
            is_single()
            && $post instanceof WP_Post
            && $post->post_name === 'item'
        );
    }

    $blog_single_classes = array( 'blog-single' );

    if ( $is_item_single_post ) {
        $blog_single_classes[] = 'blog-single--item-single';
    }

    $blog_single_class_attr = esc_attr( implode( ' ', array_map( 'sanitize_html_class', $blog_single_classes ) ) );

    ?>

    <div class="<?php echo $blog_single_class_attr; ?>">

        <div class="blog-single__title">
            <?php the_title(); ?>
        </div>

        <div class="blog-single-data">

            <?php if ( ! $is_item_single_post ): ?>
                <div class="blog-single-data__local-info">

                    <div class="blog-single-data__local-info-author">
                        <span class="blog-single-data__author-span-by">
                            <?php _e( 'by', 'rap' ) ?>
                        </span>
                        <?php echo $post_author; ?>
                    </div>

                    <div class="blog-single-data__local-info-date-and-comments">

                        <span class="blog-single-data__local-info-date">
                            <?php echo $post_date; ?> /
                        </span>

                        <a href="#blog-single-comments-section" class="blog-single-data__local-info-comments-count">
                            <?php echo $post_comments_number; ?> <?php _e( 'comments', 'rap' ) ?>
                        </a>

                    </div>

                </div>
            <?php endif; ?>

            <div class="blog-single-data__social-info">

                <div class="blog-single-data__share-and-views">

                    <div class="blog-share blog-page__social-share-bottom">
	                    <?php do_action('adstm_single_share') ?>
                    </div>

                    <div class="blog-single-data__local-info-views-count">
                        <i class="fa fa-eye"></i> <?php echo $post_views_count; ?>
                    </div>

                </div>

            </div>

        </div>

        <div class="blog-single__user-content">

            <?php the_content(); ?>

        </div>

        <?php if ( ! $is_item_single_post ): ?>
            <div class="blog-single-comments-section" id="blog-single-comments-section">

                <?php if( ( comments_open() || get_comments_number() ) ) { comments_template(); } ?>

            </div>
        <?php endif; ?>

        <?php get_template_part( 'template/blog/tpl/_related_posts' ); ?>

    </div>

<?php endwhile; ?>
