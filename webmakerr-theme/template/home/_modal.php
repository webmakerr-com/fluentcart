<?php

if(cz('id_video_youtube_home')):?>

        <div class="modal fade b-modal_cart" id="prHome_video" tabindex="-1" aria-labelledby="prModalCartLabel"
             aria-hidden="true" role="dialog">
                <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e( 'Close', 'rap' ); ?>">
                                                <span class="times" aria-hidden="true"></span>
                                        </button>
                                        <h4 class="modal-title" id="prModalCartLabel">
                                        </h4>
                                </div>
				<div class="modal-body">

					<div class="video-responsive">
						<div id="player_modal"></div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="<?php echo esc_url( home_url( '/product/' ) ) ?>" class="btn_model_video">
						<?php _e( 'Start Shopping Now', 'rap' ) ?></a>
				</div>
			</div>
		</div>
	</div>
	<script src='https://www.youtube.com/iframe_api'></script>
	<script>


		var player ={};

		function onPlayerReady(event) {
			event.target.playVideo();
		}

		function onYouTubeIframeAPIReady() {

			player = new YT.Player('player_modal', {
				height: '100%',
				width: '100%',
				videoId: '<?php echo cz('id_video_youtube_home');?>',
				playerVars: {
					HD:0,
					rel:0,
					showinfo:0,
					controls:0,
					modestbranding:1 },
				events: {
					//'onReady': onPlayerReady
				}
			});

		}

                document.addEventListener('DOMContentLoaded', function () {
                        var modalEl = document.getElementById('prHome_video');
                        if (!modalEl) {
                                return;
                        }

                        modalEl.addEventListener('show.bs.modal', function () {
                                if (player && typeof player.playVideo === 'function') {
                                        player.playVideo();
                                }
                        });

                        modalEl.addEventListener('hidden.bs.modal', function () {
                                if (player && typeof player.stopVideo === 'function') {
                                        player.stopVideo();
                                }
                        });
                });

        </script>

<?php endif; ?>