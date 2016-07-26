<?php 

get_header(); ?>

		<section class="errorPage boldSection topExtraSpaced bottomExtraSpaced gutter wBackground" style = "background-image: url(<?php echo esc_url_raw( get_template_directory_uri() . '/gfx/plug.png' ) ;?>); background-position: 30% 40px;">
			<div class="port">
				<div class="boldCell">
					<div class="boldRow ">
						<div class="rowItem col-ms-12 cellCenter">
							<div class="rowItemContent">
								<header>
									<h3 class="errorCode">404</h3>
									<h2><?php _e( 'we are sorry', 'bt_theme' ); ?><br><?php _e( 'page not found', 'bt_theme' ); ?></h2>
								</header>
								<a href="/" class="btn chubby"><?php _e( 'Back to homepage', 'bt_theme' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

<?php get_footer();