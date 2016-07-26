<?php

get_header();

?>

<?php bt_breadcrumbs(); ?>

<?php if ( have_posts() ) {
	
	while ( have_posts() ) {
	
		the_post();
		
		$images = bt_rwmb_meta( BTPFX . '_images', 'type=image' );
		if ( $images == null ) $images = array();
		$video = bt_rwmb_meta( BTPFX . '_video' );
		$audio = bt_rwmb_meta( BTPFX . '_audio' );
		
		$link_title = bt_rwmb_meta( BTPFX . '_link_title' );
		$link_url = bt_rwmb_meta( BTPFX . '_link_url' );
		$quote = bt_rwmb_meta( BTPFX . '_quote' );
		$quote_author = bt_rwmb_meta( BTPFX . '_quote_author' );
		
		$permalink = get_permalink();
		
		$post_format = get_post_format();
	
		$media_html = '';
		
		if ( has_post_thumbnail() ) {
		
			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$img = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			
			if ( $img != '' ) {
				$media_html = '<div class="boldPhotoBox"><div class="bpbItem">';
				$media_html .= '<a href="' . esc_url_raw( $permalink ) . '"><img src="' . esc_url_raw( $img[0] ) . '" alt="' . esc_attr( basename( $img[0] ) ) . '"></a>';
				$media_html .= '</div></div>';
			}
			
		}
  
		if ( $post_format == 'image' && ! has_post_thumbnail() ) {
		
			foreach ( $images as $img ) {
				$img = wp_get_attachment_image_src( $img['ID'], 'large' );
				$media_html = '<div class="boldPhotoBox"><div class="bpbItem"><a href="' . esc_url_raw( $permalink ) . '"><img src="' . esc_url_raw( $img[0] ) . '" alt="' . esc_attr( basename( $img[0] ) ) . '"></a></div></div>';
				break;
			}
			
		} else if ( $post_format == 'gallery' ) {
		
			if ( count( $images ) > 0 ) {
				$images_ids = array();
				foreach ( $images as $img ) {
					$images_ids[] = $img['ID'];
				}			
				$media_html = '<div class="boldPhotoBox">' . do_shortcode( '[gallery ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
			}
			
		} else if ( $post_format == 'video' ) {
			
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
			
		} else if ( $post_format == 'audio' ) {
			
			if ( strpos( $audio, '</ifra' . 'me>' ) > 0 ) {
				$media_html = '<div class="boldPhotoBox audio">' . $audio . '</div>';
			} else {
				$media_html = '<div class="boldPhotoBox audio">' . do_shortcode( $audio ) . '</div>';
			}
			
			if ( $audio == '' ) {
				$media_html = '';
			}
			
		} else if ( $post_format == 'link' ) {
			
			$media_html = '<div class="boldPhotoBox"><div class="bpbItem wBoldLink"><a href="' . esc_url_raw( $link_url ) . '"><span class="ico" data-icon="&#xe653;"></span><strong>' . esc_html( $link_title ) . '</strong><span class="bUrl">' . esc_url( $link_url ) . '</span></a></div></div>';
			
			if ( esc_html( $link_title ) == '' || esc_url( $link_url ) == '' ) {
				$media_html = '';
			}
			
		} else if ( $post_format == 'quote' ) {
			
			$media_html = '<div class="boldPhotoBox"><blockquote><div class="bqIcon" data-icon="&#xe642;"></div><p>' . esc_html( $quote ) . '</p><cite>' . esc_html( $quote_author ) . '</cite></blockquote></div>';
			
			if ( esc_html( $quote ) == '' || esc_html( $quote_author ) == '' ) {
				$media_html = '';
			}
			
		}

		global $date_format;
		
		$content_html = apply_filters( 'the_content', get_the_content( '', false ) );
		$content_html = str_replace( ']]>', ']]&gt;', $content_html );
		
		$categories = get_the_category();
		$categories_html = '';
		if ( $categories ) {
			$categories_html = '<span class="boldArticleCategories">';
			foreach ( $categories as $cat ) {
				$categories_html .= '<a href="' . esc_url_raw( get_category_link( $cat->term_id ) ) . '" class="boldArticleCategory">' . esc_html( $cat->name ) . '</a>';
			}
			$categories_html .= '</span>';
		}

		

		$share_html = '<div class="socialRow">' . bt_get_share_html( $permalink ) . '</div>';
		if ( is_search() ) $share_html = '';
		
		$blog_author = bt_get_option( 'blog_author' );
		$blog_date = bt_get_option( 'blog_date' );		
		
		$blog_side_info = bt_get_option( 'blog_side_info' );
		
		$class_array = array( 'boldArticle', 'gutter', 'articleListItem', 'animate', 'animate-fadein', 'animate-moveup' );
		
		if ( $blog_side_info ) $class_array[] = 'btHasAuthorInfo';
		if ( $media_html != '' ) $class_array[] = 'wPhoto';

		echo '<article class="' . implode( ' ', get_post_class( $class_array ) ) . '">
			<div class="port">
				<div class="boldCell">
					<div class="boldRow">
						<div class="rowItem col-sm-12">' . $media_html;
						
							$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
							$author_html = '<a href="' . esc_url_raw( $author_url ) . '">' . __( 'by', 'bt_theme' ) . ' ' . esc_html( get_the_author() ) . '</a>';

							if ( $blog_side_info ) {
								echo '<div class="articleSideGutter">';
								if ( $blog_date ) {
									echo '<div class="asgItem posted"><span>' . $author_html . '</span></div>';
								}
								if ( $blog_author ) {
									$avatar_html = get_avatar( get_the_author_meta( 'ID' ), 144 ); 
									if ( $avatar_html != '' ) {
										echo '<div class="asgItem avatar"><a href="' . esc_url_raw( $author_url ) . '">' . $avatar_html . '</a></div>';
									}
								}
								echo '</div>';
							}
							
							echo '<header class="header large btDash topDash">';
							echo '<h2><span class="dash"><span class="h2content"><a href="' . esc_url_raw( $permalink ) . '">' . get_the_title() . '</a></span></span></h2>';
							
							$comments_open = comments_open();
							$comments_number = get_comments_number();
							$show_comments_number = true;
							if ( ! $comments_open && $comments_number == 0 ) {
								$show_comments_number = false;
							}
							
							$meta_html = '';
							
							if ( $blog_author || $blog_date || $show_comments_number ) {
							
								$meta_html .= '<p class="boldSubTitle">';
								
								if ( $blog_date ) $meta_html .= '<span>' . esc_html( date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d' ) ) ) ) . '</span>'; 

								if ( $blog_author ) $meta_html .= $author_html;

								$meta_html .= $categories_html;
								
								if ( $show_comments_number ) $meta_html .= '<a href="' . esc_url_raw( $permalink ) . '#comments" class="boldArticleComments">' . $comments_number . '</a>';
								
								$meta_html .= '</p>';
							}
							
							echo wp_kses_post( $meta_html );
							
							echo '</header>';
							
							$content_final_html = get_post()->post_excerpt != '' ? '<p>' . esc_html( get_the_excerpt() ) . '</p>' : $content_html;
							
							$post_type = get_post_type();
							
							if ( $content_final_html != '' && $post_type == 'post' ) {
								
								$extra_class = '';
								
								if ( $post_format == 'link' && $media_html == '' ) {
									$extra_class = 'linkOrQuote';
								}
								echo '<div class="boldArticleBody divider">' . $content_final_html . '</div>';		

							}
							
							echo '<footer>
								<div class="btFootSocialWrap"><span class="btFootSocialWrapToggler"></span>' . $share_html . '</div>
								<p class="boldContinue"><a href="' . esc_url_raw( $permalink ) . '">' . __( 'CONTINUE READING', 'bt_theme' ) . '</a></p>
							</footer>			
						</div>
					</div>
				</div>
			</div>
		</article>';

	}
	
	bt_pagination();
	
} else {
	if ( is_search() ) { ?>
		<article class="classic single noBorder">
			<header>
				<h3 class="errorCodeTxt"><?php _e( 'No results for', 'bt_theme' ); ?>: <?php echo get_query_var( 's' ); ?></h3>
				<h2><?php _e( 'we are sorry', 'bt_theme' ); ?><br><?php _e( 'no search results have been found', 'bt_theme' ); ?></h2>
			</header>
			<div class="articleBody txt-center">
				<a href="/" class="btn chubby"><?php _e( 'Back to homepage', 'bt_theme' ); ?></a>
			</div>
		</article>
	<?php }
}

?>

<?php

get_footer();

?>