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
		
		$permalink = get_permalink();
	
		$media_html = '';
		
		if ( has_post_thumbnail() ) {
		
			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$img = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			
			if ( $img != '' ) {
				$media_html = '<div class="boldPhotoBox"><div class="bpbItem">';
				$media_html .= '<a href="' . esc_url_raw( $permalink ) . '"><img src="' . esc_url_raw( $img[0] ) . '" alt="' . esc_attr( basename( $img[0] ) ) . '"></a>';
				$media_html .= '</div></div>';
			}

		} else if ( count( $images ) == 1 ) {
		
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
			$media_html = '<div class="boldPhotoBox">' . do_shortcode( '[gallery ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
			
		} 
		
		if ( $video != '' ) {
			
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
		
		$share_html = bt_get_share_html( $permalink, 'pf' );
		
		$class_array = array( 'boldArticle', 'articleListItem' );

		if ( $media_html != '' ) $class_array[] = 'wPhoto';

		echo '<article class="' . implode( ' ', get_post_class( $class_array ) ) . '">' . $media_html;
			
			echo '<header class="header large btDash topDash">
			<h2><span class="dash"><span class="h2content"><a href="' . esc_url_raw( $permalink ) . '">' . get_the_title() . '</a></span></span></h2>';			
			
			echo '<p class="boldSubTitle">' . bt_rwmb_meta( BTPFX . '_subheading' ) . '</p>';
			
			echo '</header>';
			
			$content_final_html = get_post()->post_excerpt != '' ? '<p>' . esc_html( get_the_excerpt() ) . '</p>' : $content_html;
			
			if ( $content_final_html != '' ) {
				echo '<div class="boldArticleBody divider">' . $content_final_html . '</div>';
			}
			
			echo '<footer>
				<div class="socialRow">
					' . $share_html . '
				</div>
				<p class="boldContinue"><a href="' . esc_url_raw( $permalink ) . '">' . __( 'CONTINUE READING', 'bt_theme' ) . '</a></p>
			</footer>			
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