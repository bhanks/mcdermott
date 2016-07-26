<?php
if ( ! function_exists( 'bt_breadcrumbs' ) ) {
	function bt_breadcrumbs() {

		global $post;
		
		$post_type = get_post_type( get_the_ID() );
		
		$post_id = get_the_ID();
		
		$home = __( 'Home', 'bt_theme' );
		$home_link = home_url();
		$title = '';

		if ( ! is_404() && ! is_home() ) {
		
			echo '<div class="breadCrumbs"><nav><ul><li><a href="' . esc_url_raw( $home_link ) . '">' . $home . '</a></li>';
			
			if ( is_category() ) {

				$title = __( 'Category:', 'bt_theme' ) . ' ' . single_cat_title( '', false );
				echo '<li>' . $title . '</li>';
		  
			} else if ( is_singular( 'post' ) ) {
			
				$categories = get_the_category();
				echo '<li>';
				$n = 0;
				foreach( $categories as $cat ) {
					$n++;
					echo '<a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '</a>';
					if ( $n < count( $categories ) ) echo ', ';
				}
				echo '</li>';
				echo '<li>' . get_the_title() . '</li>';
				
			} else if ( is_post_type_archive( 'portfolio' ) ) {
				
				$title = __( 'Portfolio', 'bt_theme' );
				echo '<li>' . $title . '</li>';
				
			} else if ( is_singular( 'portfolio' ) ) {
				
				echo '<li>' . __( 'Portfolio', 'bt_theme' ) . '</li>';
				echo '<li>' . get_the_title() . '</li>';
				
			} else if ( is_attachment() ) {
			
				$title = get_the_title();
				echo '<li>' . $title . '</li>';
		  
			} else if ( is_tag() ) {
			
				$title = __( 'Tag:', 'bt_theme' ) . ' ' . single_tag_title( '', false );
				echo '<li>' . $title . '</li>';
		  
			} else if ( is_author() ) {
			
				$title = __( 'Author:', 'bt_theme' ) . ' ' . get_the_author_meta( 'display_name' );
				echo '<li>' .  $title . '</li>';
				
			} else if ( is_day() ) {

				$title = get_the_time( 'Y / m / d' );
				echo '<li>' . $title . '</li>';
		  
			} else if ( is_month() ) {
			
				$title = get_the_time( 'Y / m' );
				echo '<li>' . $title . '</li>';
		  
			} else if ( is_year() ) {
			
				$title = get_the_time( 'Y' );
				echo '<li>' . $title . '</li>';			
				
			} else if ( is_search() ) {
				
				$title = __( 'Search:', 'bt_theme' ) . ' ' . get_query_var( 's' );
				echo '<li>' . $title . '</li>';			
				
			}
			
			echo '</ul></nav></div>';
			if ( $title != '' ) echo '<header class="header medium btDash topDash bottomSpaced"><h1><span class="dash"><span class="h2content">' . $title . '</span></span></h1></header>';
		  
		}
	}
}