<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 03.06.2016
 * Time: 15:11
 */
$reviews = adsTmpl::review();
$posts_per_page = ( isset( $wp_query->query_vars[ 'comments_per_page' ] ) &&
                    intval( $wp_query->query_vars[ 'comments_per_page' ] ) ) ?
	$wp_query->query_vars[ 'comments_per_page' ] :
	intval( get_option( 'comments_per_page' ) );

?>

<?php if ( comments_open() ): ?>
    <div class="">
        <div class="wrap-review_list">
            <?php if (cz('tp_enable_leave_review_box') && class_exists('models\review\RenderForm')):?>
                <div class="review-form" style="display: none;">
                    <div class="head"><?php _e('We want to hear from you!', 'rap');?></div>
                    <div class="row g-4" id="addReviewDiv">
                        <div class="col-12 col-lg-9">
                                <p class="h3 heading-title" style="margin-top:10px !important;">
                                    <?php _e('Leave a Review', 'ads');?>
                                </p>
                            <form class="addReviewForm row g-3" enctype="multipart/form-data">
                                <div class="col-12 col-md-6">
                                    <label for="review-name" class="form-label visually-hidden"><?php _e('*Name', 'ads');?></label>
                                    <input id="review-name" type="text" class="form-control form-control-lg" placeholder="<?php _e('*Name', 'ads');?>" name="Addreview[name]">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="review-email" class="form-label visually-hidden"><?php _e('*Email', 'ads');?></label>
                                    <input id="review-email" type="email" class="form-control form-control-lg" placeholder="<?php _e('*Email', 'ads');?>" name="Addreview[email]">
                                </div>
                                <div class="col-12">
                                    <div class="form-control-select country_list_select"></div>
                                </div>
                                <div class="col-12">
                                    <label for="review-message" class="form-label visually-hidden"><?php _e('*Message', 'ads');?></label>
                                    <textarea id="review-message" rows="5" class="form-control" placeholder="<?php _e('*Message', 'ads');?>" name="Addreview[message]"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="stars_set" role="radiogroup" aria-label="<?php esc_attr_e('Rating', 'ads');?>">
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                    </div>
                                    <input name="Addreview[rating]" type="hidden" value="">
                                </div>
                                <?php if( cz( 'cm_readonly' ) ) : ?>
                                    <div class="col-12">
                                        <div class="form-check conditions-review">
                                            <input class="form-check-input in-conditions-review" id="add-review-terms" type="checkbox">
                                            <label class="form-check-label" for="add-review-terms">
                                                <?php echo cz( 'cm_readonly_text'); ?>
                                            </label>
                                        </div>
                                        <div style="display: none;color: red" class="conditions-help"><?php echo cz( 'cm_readonly_notify'); ?></div>
                                    </div>
                                <?php endif;?>
                                <div class="col-12 d-flex flex-wrap align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary submit-review"><?php _e('Submit a Review', 'ads');?></button>
                                    <input hidden="hidden" name="action" value="ads_add_user_review">
                                    <input hidden="hidden" name="Addreview[post_id]" value="<?php echo get_the_ID();?>">
                                    <span class="btn btn-outline-secondary btn-legacy fileinput-button" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php _e('Attach file(s)', 'ads');?>">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        <input id="review-file-upload" type="file" name="review_files[]" multiple>
                                    </span>
                                </div>
                                <div class="col-12 list-file"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="write-reviews"><div class="write js-write"><?php _e('Write a review', 'rap');?></div></div>
                <?php if (ADS_URL){ ?>
                    <script type="text/javascript">
                        addreview_script=[
                            '<?php echo ADS_URL; ?>/assets/front/js/jqueryFileUpload/jquery.ui.widget.js',
                            '<?php echo ADS_URL; ?>/assets/front/js/jqueryFileUpload/jquery.fileupload.min.js',
                            '<?php echo ADS_URL; ?>/assets/front/js/rating-stars/rating.min.js',
                            '<?php echo ADS_URL; ?>/assets/front/js/addReview.min.js',
                        ]
                    </script>
                <?php } ?>
            <?php endif;?>
            <div class="review_list">
                        <?php

                        $args = array(
                            'walker'            => null,
                            'max_depth'         => '',
                            'style'             => 'tr',
                            'callback'          => 'list_review',
                            'end-callback'      => null,
                            'type'              => 'all',
                            'reply_text'        => 'Reply',
                            'page'              => 1,
                            'per_page'          => $posts_per_page,
                            'avatar_size'       => 32,
                            'reverse_top_level' => null,
                            'reverse_children'  => '',
                            'format'            => 'html5',
                            'echo'              => true,
                            'status'            => 'approve'
                        );

                        wp_list_comments( $args, $reviews->comments );
                        ?>
                    </div>
                        <?php if( get_comment_pages_count() > 1 ): ?>
                        <div class="more-reviews wrap-load_reviews"><div class="load_reviews"><?php _e('More reviews', 'rap');?></div></div>
                        <?php endif; ?>
                        <div class="inner-box-comment-form">
                            <div class="wrap-pagination js-pagination pagination" style="display: none;">
                                <div class="pagination">
                                    <?php
                                    paginate_comments_links(
                                        array(
                                            'prev_text' => '&laquo;',
                                            'next_text' => '&raquo;',
                                            'current'   => $reviews->getPage()
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
        </div>
    </div>
<?php endif; ?>

