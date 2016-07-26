<?php

get_header();

if ( have_posts() ) {
	
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
			$thumb_img_slider = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
			$thumb_img_slider = $thumb_img_slider[0];
			if ( $img != '' ) {
				$media_html = '<div class="boldPhotoBox"><div class="bpbItem">';
				$media_html .= '<a href="' . esc_url_raw( $permalink ) . '"><img src="' . esc_url_raw( $img[0] ) . '" alt="' . esc_attr( basename( $img[0] ) ) . '"></a>';
				$media_html .= '</div></div>';
			}
			
		}

		if ( $post_format == 'image' ) {
		
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
				if ( intval( bt_rwmb_meta( BTPFX . '_grid_gallery' ) ) != 0 ) {
					$media_html = '<div class="boldPhotoBox">' . do_shortcode( '[bt_grid_gallery columns="' . bt_get_option( 'blog_grid_gallery_columns' ) . '" lightbox="yes" format="' . bt_rwmb_meta( BTPFX . '_grid_gallery_format' ) . '" ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
				} else {
					$media_html = '<div class="boldPhotoBox">' . do_shortcode( '[gallery ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
				}
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
			
			$media_html = '<div class="boldPhotoBox"><div class="bpbItem wBoldLink"><a href="' . esc_url_raw( $link_url ) . '"><span class="ico" data-icon-pe="&#xe641;"></span><strong>' . esc_html( $link_title ) . '</strong><span class="bUrl">' . esc_url( $link_url ) . '</span></a></div></div>';
			
			if ( esc_html( $link_title ) == '' || esc_url( $link_url ) == '' ) {
				$media_html = '';
			}
			
		} else if ( $post_format == 'quote' ) {
			
			$media_html = '<div class="boldPhotoBox"><blockquote><div class="bqIcon" data-icon-pe="&#xe668;"></div><p>' . esc_html( $quote ) . '</p><cite>' . esc_html( $quote_author ) . '</cite></blockquote></div>';
			
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
		
		$tags = get_the_tags();
		$tags_html = '';
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$tags_html .= '<li><a href="' . esc_url_raw( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
			}
			$tags_html = rtrim( $tags_html, ', ' );
			$tags_html = '<div class="boldTags"><ul>' . $tags_html . '</ul></div>';
		}
		
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
		if ( '' != $prev_next_html  ) {
			$prev_next_html = '<div class="neighboringArticles">' . $prev_next_html . '</div>';
		}		
		
		$about_author_html = '';
		if ( bt_get_option( 'blog_author_info' ) ) {
		
			$avatar_html = get_avatar( get_the_author_meta( 'ID' ), 280 );
			$avatar_html = str_replace ( 'width=\'280\'', 'width=\'140\'', $avatar_html );
			$avatar_html = str_replace ( 'height=\'280\'', 'height =\'140\'', $avatar_html );
			$avatar_html = str_replace ( 'width="280"', 'width="140"', $avatar_html );
			$avatar_html = str_replace ( 'height="280"', 'height ="140"', $avatar_html );
			
			$about_author_html = '<div class="btAboutAuthor">';
			
			$user_url = get_the_author_meta( 'user_url' );
			if ( $user_url != '' ) {
				$author_html = '<a href="' . esc_url_raw( $user_url ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>';
			} else {
				$author_html = esc_html( get_the_author_meta( 'display_name' ) );
			}
			
			if ( $avatar_html ) {
				$about_author_html .= '<div class="aaAvatar">' . $avatar_html . '</div>';
			}
			
			$about_author_html .= '<div class="aaTxt"><h4>' . $author_html;
			$about_author_html .= '</h4>
					<p>' . get_the_author_meta( 'description' ) . '</p>
				</div>
			</div>';
		}		

		$share_html = bt_get_share_html( $permalink );
		
		$comments_open = comments_open();
		$comments_number = get_comments_number();
		$show_comments_number = true;
		if ( ! $comments_open && $comments_number == 0 ) {
			$show_comments_number = false;
		}
		
		$blog_author = bt_get_option( 'blog_author' );
		$blog_date = bt_get_option( 'blog_date' );
		
		$class_array = array( 'boldArticle', 'gutter' );
		if ( $content_html != '' ) $class_array[] = 'divider';
		if ( $media_html == '' ) $class_array[] = 'noPhoto';

		if ( has_post_thumbnail() && bt_get_option( 'blog_ghost_slider' ) ) {
		
				$slider_class = '';
		
				$meta_slider_html = '';
				
				$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
				$author_html = '<a href="' . esc_url_raw( $author_url ) . '">' . __( 'by', 'bt_theme' ) . ' ' . esc_html( get_the_author() ) . '</a>';				
				
				if ( $blog_author || $blog_date || $show_comments_number ) {
				
					$meta_slider_html .= '<p class="boldSubTitle boldArticleMeta">';
					
					if ( $blog_date ) $meta_slider_html .= '<span>' . esc_html( date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d' ) ) ) ) . '</span>'; 

					if ( $blog_author ) $meta_slider_html .= $author_html;

					$meta_slider_html .= $categories_html;
					
					if ( $show_comments_number ) $meta_slider_html .= '<a href="' . esc_url_raw( $permalink ) . '#comments" class="boldArticleComments">' . $comments_number . '</a>';
					
					$meta_slider_html .= '</p>';
				}
		
		?>
			<section class="boldSection fullScreen ghost dark wBackground cover<?php echo esc_attr( $slider_class ); ?>" style="background-image: url('<?php echo esc_attr( $thumb_img_slider ); ?>')">
				<div class="port">
					<div class="boldCell">
						<div class="boldRow">
							<div class="rowItem col-ms-12 cellCenter">
								<header class="header big btDash topDash">
									<h2><span class="dash"><span class="h2content"><?php the_title(); ?></span></span></h2>
									<?php echo wp_kses_post( $meta_slider_html ); ?>
								</header>
							</div>
						</div>
					</div>
				</div>
				<div class="closeGhost ico accent"><a href="#"><?php echo __( 'View post', 'bt_theme' ); ?></a></div>
			</section>
		<?php }
		
		echo '<article class="' . implode( ' ', get_post_class( $class_array ) ) . '">';
			
			$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$author_html = '<a href="' . esc_url_raw( $author_url ) . '">' . __( 'by', 'bt_theme' ) . ' ' . esc_html( get_the_author() ) . '</a>';
			
			echo '<div class="port">';
				echo '<div class="boldRow boldArticleHeader">';
					echo '<div class="rowItem col-sm-12">';
						echo '<header class="header large btDash topDash">';
						echo '<h1><span class="dash"><span class="h2content">' . get_the_title() . '</span></span></h1>';
				
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
					echo '</div><!-- /rowItem -->';
				echo '</div><!-- /boldRow -->';
				echo '<div class="boldRow">';
					echo '<div class="rowItem col-sm-12">' . $media_html . '</div><!-- /rowItem -->';
				echo '</div><!-- /boldRow -->';
			
				echo '<div class="boldRow">';
				echo '<div class="rowItem col-sm-12">';
			
				$extra_class = '';
				
				if ( $post_format == 'link' && $media_html == '' ) {
					$extra_class = 'linkOrQuote';
				}
				
					echo '<div class="boldArticleBody portfolioBody ' . esc_attr( $extra_class ) . '">' . $content_html . '</div>';

				echo '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';
			echo '<div class="boldRow">';
				echo '<div class="rowItem col-sm-6 tagsRowItem">';

					echo wp_kses_post( $tags_html );

				echo '</div><!-- /rowItem -->';
				echo '<div class="rowItem col-sm-6 cellRight shareRowItem">';

					echo '<div class="socialRow">' . $share_html . '</div>';
			
				echo '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';
			echo '<div class="boldRow">';
				echo '<div class="rowItem col-sm-12">';
					
					wp_link_pages( array( 
						'before'      => '<p class="btLinkPages">' . __( 'Pages:', 'bt_theme' ),
						'separator'   => ' ',
						'after'       => '</p>'
					));
					
					echo wp_kses_post( $about_author_html );

				echo '</div><!-- /rowItem -->';
			echo '</div><!-- /boldRow -->';				
		echo '</div><!-- /port -->';
	echo '</article>';

	echo '<section class="boldSection gutter">';
		echo '<div class="port">';
			echo '<div class="boldCell">';
				echo '<div class="boldRow btCommentsRow">';
					echo '<div class="rowItem col-ms-12">';
						
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}		

					echo '</div><!-- /rowItem -->';
				echo '</div><!-- /boldRow -->';
				echo '<div class="boldRow btNextPrevRow">';
					echo '<div class="rowItem col-ms-12">';
											
						echo wp_kses_post( $prev_next_html );					

					echo '</div><!-- /rowItem -->';
				echo '</div><!-- /boldRow -->';
			echo '</div><!-- /boldCell -->';
		echo '</div><!-- /port -->';
	echo '</section>';
		
	}
	
}

	?>
	

<?php

get_footer();

?>