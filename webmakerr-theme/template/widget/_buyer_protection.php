<?php if ( cz( 'tp_single_buyer_protection' ) ): ?>
<div class="col-sm-12 d-none d-sm-block">
        <div class="buyer_protection">
            <div class="item_1 col-sm-10 offset-sm-1 offset-lg-0 col-lg-7">
                <div class="cart">
                    <?php _e( 'Buyer Protection', 'rap' ) ?>
                </div>
                <div class="list">
                    <div class="list-i"><i class="fa fa-check-square-o" aria-hidden="true"></i>
                        <strong><?php _e( "Full Refund", 'rap' ) ?></strong>
                        <?php _e( "if you don't receive your order.", 'rap' ) ?>
                    </div>
                    <div class="list-i"><i class="fa fa-check-square-o" aria-hidden="true"></i>
                        <strong><?php _e( 'Refund & Keep', 'rap' ) ?></strong>
                        <?php _e( 'items, if not as described.', 'rap' ) ?>
                    </div>
                </div>
            </div>
            <div class="item_2 col-sm-7 offset-sm-3 col-lg-4 offset-lg-1">
                <div class="ship"><?php _e( 'Free Shipping Worldwide', 'rap' ) ?></div>
            </div>
        </div>
	</div>
<?php endif; ?>