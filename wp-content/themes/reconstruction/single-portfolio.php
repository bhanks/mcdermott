<?php

get_header();

global $date_format;

if ( have_posts() ) {

	the_post();

	$images = bt_rwmb_meta( BTPFX . '_images', 'type=image' );
	if ( $images == null ) $images = array();
	$video = bt_rwmb_meta( BTPFX . '_video' );
	$audio = bt_rwmb_meta( BTPFX . '_audio' );
	
	$permalink = get_permalink();

	$first_img = '';

	$subheading = bt_rwmb_meta( BTPFX . '_subheading' );
	
	$cf = array_values( bt_rwmb_meta( BTPFX . '_custom_fields' ) );
	$cf_right_html = '';
	if ( $cf != '' ) {
		for ( $i = 0; $i < 1; $i++ ) {
			if ( $i < count( $cf ) ) {
				$item = $cf[ $i ];
				$item_key = substr( $item, 0, strpos( $item, ':' ) );
				$item_value = substr( $item, strpos( $item, ':' ) + 1 );
				$cf_right_html .= '<dt>' . $item_key . '</dt>';
				$cf_right_html .= '<dd>' . $item_value . '</dd>';
			}
		}
	}
	
	$categories = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
	$categories_html = '';
	if ( $categories ) {
		foreach ( $categories as $cat ) {
			$categories_html .= $cat->name . ', '; 
		}
	}
	
	$categories_html = trim( $categories_html, ', ' );	

	$meta_right_html = '';
	
	if ( $categories_html != '' ) {
		$meta_right_html .= '<dt>' . __( 'Category', 'bt_theme' ) . '</dt>';
		$meta_right_html .= '<dd>' . $categories_html . '</dd>';
	}
	
	$meta_right_html = $meta_right_html . $cf_right_html;
	
	$slider_images = array();
			
	if ( has_post_thumbnail() ) {

		$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		$first_img = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
		$first_img = $first_img[0];
		
		$first_thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'medium' );
		$first_thumb = $first_thumb[0];
		
		$first_post = get_post( $post_thumbnail_id );
		$first_text = $first_post->post_excerpt;
		$first_description = $first_post->post_content;
		
		if ( count( $images ) > 0 ) {
			$n = 0;
			foreach ( $images as $img ) {
				$src = wp_get_attachment_image_src( $img['ID'], 'full' );
				$src = $src[0];
				
				$thumb = wp_get_attachment_image_src( $img['ID'], 'medium' );
				$thumb = $thumb[0];
				
				$img_post = get_post( $img['ID'] );
				$text = $img_post->post_excerpt;
				
				$slider_images[ $n ]['src'] = $src;
				$slider_images[ $n ]['thumb'] = $thumb;
				$slider_images[ $n ]['text'] = $text;
				$slider_images[ $n ]['description'] = $img_post->post_content;
				
				$n++;
			}
		}
		
	} else if ( count( $images ) > 0 ) {
		$n = 0;
		foreach ( $images as $img ) {
		
			if ( $n == 0 ) {
			
				$first_img = wp_get_attachment_image_src( $img['ID'], 'full' );
				$first_img = $first_img[0];
				
				$first_thumb = wp_get_attachment_image_src( $img['ID'], 'medium' );
				$first_thumb = $first_thumb[0];
					
				$first_post = get_post( $img['ID'] );
				$first_text = $first_post->post_excerpt;
				$first_description = $first_post->post_content;

			} else {
		
				$src = wp_get_attachment_image_src( $img['ID'], 'full' );
				$src = $src[0];
				
				$thumb = wp_get_attachment_image_src( $img['ID'], 'medium' );
				$thumb = $thumb[0];

				$this_post = get_post( $img['ID'] );

				$text = $this_post->post_excerpt;

				$slider_images[ $n ]['src'] = $src;
				$slider_images[ $n ]['thumb'] = $thumb;
				$slider_images[ $n ]['text'] = $text;
				$slider_images[ $n ]['description'] = $this_post->post_content;
			
			}
			
			$n++;
		}
	
	}

	if ( $first_img != '' ) {
		if ( bt_get_option( 'pf_ghost_slider' ) ) {
			$gr_class = '';
		} else {
			$gr_class = ' ' . 'btNoInitGhost';
		}
		$st = 'background-image:url(' . $first_img . ');';
	
	?>
		<section class="boldSection fullScreen ghost dark">
			<div class="port">
				<div class="closeGhost ico accent"><a href="#"></a></div>
				<div class="boldCell">
					<div class="slidedVariable largeSliderHeight"<?php if ( count( $slider_images ) == 0 ) echo ' ' . 'data-nocenter="yes"'; ?>>
						<div class="slidedItem firstItem <?php if ( count( $slider_images ) == 0 ) echo 'onlyItem'; ?>" data-thumb="<?php echo esc_attr( $first_thumb ); ?>" data-text="<?php echo esc_attr( $first_text ); ?>" data-description="<?php echo esc_attr( $first_description ); ?>">
							<div class="port wBackground cover<?php echo esc_attr( $gr_class ); ?>" style="<?php echo esc_attr( $st ); ?>">
								<div class="boldCell">
								</div><!-- /.boldCell-->
								<article class="portfolioArticle">
									<header class="header large btDash topDash">
										<h1><span class="dash"><span class="h2content"><?php echo get_the_title(); ?></span></span></h1>
										<p class="boldSubTitle"><?php echo wp_kses_post( $subheading ); ?></p>
									</header>
								</article><!-- /boldBlogArticle -->
								<dl class="articleMeta">
									<?php echo wp_kses_post( $meta_right_html ); ?>
								</dl>
							</div><!-- /.fullScreen -->
						</div><!-- /.slidedItem -->
	
						<?php foreach( $slider_images as $slider_image ) {
							echo '<div class="slidedItem" data-thumb="' . esc_attr( $slider_image['thumb'] ) . '" data-text="' . esc_attr( $slider_image['text'] ) . '" data-description="' . esc_attr( $slider_image['description'] ) . '">';
								echo '<div class="variableImg"><img src="' . esc_attr( $slider_image['src'] ) . '" alt="' . esc_attr( $slider_image['src'] ) . '"></div>';
							echo '</div><!-- /.slidedItem -->';
						} ?>
						
					</div><!-- /slided -->
					
					<span class="boldGetInfo"></span>
					<div class="boldInfoBar">
						<div class="boldInfoBarMeta">
							<p><strong><?php _e( 'Project:', 'bt_theme' ); ?></strong> <?php echo get_the_title(); ?></p>
							<p><strong><?php _e( 'Title:', 'bt_theme' ); ?></strong> <span class="btPortfolioSliderCaption"></span></p>
							<p><strong><?php _e( 'Description:', 'bt_theme' ); ?></strong> <span class="btPortfolioSliderDescription"></span></p>
						</div><!-- /boldInfoBarMeta -->
						<div class="socialRow">
							<?php echo bt_get_share_html( $permalink, 'pf' ); ?>
						</div><!-- /socialRow -->
					</div><!-- /boldInfoBar -->
				</div><!-- /boldCell -->
			</div><!-- /port -->
		</section>
		
	<?php } ?>

	<?php
	
	$permalink = get_permalink();

	$media_html = '';
	
	$has_thumb = 'no';
	
	if ( has_post_thumbnail() ) {
	
		$has_thumb = 'yes';
		
	}
	
	if ( count( $images ) == 1 ) {
	
		foreach ( $images as $img ) {
			$img = wp_get_attachment_image_src( $img['ID'], 'large' );
			$media_html = '<div class="boldPhotoBox"><div class="bpbItem"><a href="' . esc_url_raw( $permalink ) . '"><img src="' . esc_url_raw( $img[0] ) . '" alt="' . esc_attr( basename( $img[0] ) ) . '"></a></div></div>';
			break;
		}
		
	} else if ( count( $images ) > 1 ) {
	
		$images_ids = array();
		foreach ( $images as $img ) {
			$images_ids[] = $img['ID'];
		}			
		if ( intval( bt_rwmb_meta( BTPFX . '_grid_gallery' ) ) != 0 ) {
			$media_html = '<div class="boldPhotoBox">' . do_shortcode( '[bt_grid_gallery columns="' . bt_get_option( 'pf_grid_gallery_columns' ) . '" has_thumb="' . esc_attr( $has_thumb ) . '" format="' . bt_rwmb_meta( BTPFX . '_grid_gallery_format' ) . '" ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
		} else {
			$media_html = '<div class="boldPhotoBox">' . do_shortcode( '[gallery ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
		}
		
	} else if ( $video != '' ) {
		
		$media_html = '<div class="boldPhotoBox video"><img class="aspectVideo" src="' . esc_url_raw( get_template_directory_uri() . '/gfx/video-16.9.png' ) . '" alt="" role="presentation" aria-hidden="true">';

		if ( strpos( $video, 'vimeo.com/' ) > 0 ) {
			$video_id = substr( $video, strpos( $video, 'vimeo.com/' ) + 10 );
			$media_html .= '<ifra' . 'me src="' . esc_url_raw( 'http://player.vimeo.com/video/' . $video_id ) . '" allowfullscreen></ifra' . 'me>';
		} else {
			$yt_id_pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
			$youtube_id = ( preg_replace( $yt_id_pattern, '$1', $video ) );
			if ( strlen( $youtube_id ) == 11 ) {
				$media_html .= '<ifra' . 'me width="560" height="315" src="' . esc_url_raw( 'http://www.youtube.com/embed/' . $youtube_id ) . '" allowfullscreen></ifra' . 'me>';
			} else {
				$media_html = '<div class="boldPhotoBox video">';
				$media_html .= do_shortcode( $video );
			}
		}
		
		$media_html .= '</div>';
		
		if ( $video == '' ) {
			$media_html = '';
		}
		
	} else if ( $audio != '' ) {
		
		if ( strpos( $audio, '</ifra' . 'me>' ) > 0 ) {
			$media_html = '<div class="boldPhotoBox audio">' . $audio . '</div>';
		} else {
			$media_html = '<div class="boldPhotoBox audio">' . do_shortcode( $audio ) . '</div>';
		}
		
		if ( $audio == '' ) {
			$media_html = '';
		}
		
	}

	$content_html = apply_filters( 'the_content', get_the_content( '', false ) );
	$content_html = str_replace( ']]>', ']]&gt;', $content_html );
	
	$prev_next_html = '';
	$prev = get_adjacent_post( false, '', true );
	if ( '' != $prev ) {
		$prev_next_html .= '<div class="neighbor onLeft">';
		$prev_next_html .= '<h4 class="nbs nsPrev"><a href="' . esc_url_raw( get_permalink( $prev ) ) . '">';
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $prev->ID ), 'thumbnail' );
		$url = $thumb[0];
		
		$prev_next_html .= '<span class="nbsImage"><span class="nbsImgHolder" style="background-image:url(\'' . $url . '\');"></span></span>';
		
		$prev_next_html .= '<span class="nbsItem"><span class="nbsDir">' . __( 'previous', 'bt_theme' ) . '</span><span class="nbsTitle">' . esc_html( $prev->post_title ) . '</span></span>';
		$prev_next_html .= '</a></h4>';
		$prev_next_html .= '</div>';
	}
	$next = get_adjacent_post( false, '', false );
	if ( '' != $next ) {
		$prev_next_html .= '<div class="neighbor onRight">';
		$prev_next_html .= '<h4 class="nbs nsNext"><a href="' . esc_url_raw( get_permalink( $next ) ) . '">';
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'thumbnail' );
		$url = $thumb[0];
		$prev_next_html .= '<span class="nbsItem"><span class="nbsDir">' . __( 'next', 'bt_theme' ) . '</span><span class="nbsTitle">' . esc_html( $next->post_title ) . '</span></span>';
		
		$prev_next_html .= '<span class="nbsImage"><span class="nbsImgHolder" style="background-image:url(\'' . $url . '\');"></span></span>';
		
		$prev_next_html .= '</a></h4>';
		$prev_next_html .= '</div>';
	}
	
	$class_array = array( 'boldArticle', 'portfolioItem', 'gutter' );
	if ( $content_html != '' ) $class_array[] = 'divider';
	
	echo '<article class="' . implode( ' ', get_post_class( $class_array ) ) . '">';
		echo '<div class="port">';
			echo '<div class="boldRow boldArticleHeader">';
				echo '<div class="rowItem col-sm-12">';
					echo '<header class="header large btDash topDash">';
                        echo '<div class="btPortfolioHeaderPort">';

                            echo '<h1><span class="dash"><span class="h2content">' . get_the_title() . '</span></span></h1>';
                            echo '<p class="boldSubTitle">' . $subheading . '</p>';
                        echo '</div><!-- /btPortfolioHeaderPort -->';

						echo '<dl class="articleMeta">';
							if ( $categories_html != '' ) {
								echo '<dt>' . __( 'Category', 'bt_theme' ) . '</dt>';
								echo '<dd>' . $categories_html . '</dd>';
							}
							echo wp_kses_post( $cf_right_html );
						echo '</dl>';
                        echo '<div class="socialRow">' . bt_get_share_html( $permalink, 'pf' ) . '</div>';
					echo '</header>';
				echo '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';
			echo '<div class="boldRow">';
				echo '<div class="rowItem col-sm-12">' . $media_html . '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';
			echo '<div class="boldRow">';
				echo '<div class="rowItem col-sm-12">';
			
					$extra_class = '';
					if ( $cf != '' && count( $cf ) > 2 ) {
						$extra_class = ' ' . 'wArticleMeta';
						echo '<dl class="articleMeta onBottom">';
						for ( $i = 1; $i < count( $cf ); $i++ ) {
							$item = $cf[ $i ];
							$item_key = substr( $item, 0, strpos( $item, ':' ) );
							$item_value = substr( $item, strpos( $item, ':' ) + 1 );
							echo '<dt>' . $item_key . '</dt>';
							echo '<dd>' . $item_value . '</dd>';
						}
						echo '</dl>';
					}
			
					echo '<div class="boldArticleBody portfolioBody' . esc_attr( $extra_class ) . '">' . $content_html . '</div>';

				echo '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';

			echo '<div class="boldRow">';
				echo '<div class="rowItem col-sm-12">';
					
				wp_link_pages( array( 
					'before'      => '<p class="btLinkPages">' . __( 'Pages:', 'bt_theme' ),
					'separator'   => ' ',
					'after'       => '</p>'
				));
				
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				
				echo '<div class="neighboringArticles">' . $prev_next_html . '</div>';

				echo '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';			
		echo '</div><!-- /port -->';
	echo '</article>';
	

}

?>

<?php

get_footer();

?>