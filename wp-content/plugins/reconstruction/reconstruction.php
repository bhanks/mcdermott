<?php

/**
 * Plugin Name: ReConstruction Plugin
 * Description: Shortcodes and widgets by BoldThemes.
 * Version: 1.0.3
 * Author: BoldThemes
 * Author URI: http://bold-themes.com
 */
 
function bt_load_plugin_textdomain() {

	$domain = 'bt_plugin';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'plugins_loaded', 'bt_load_plugin_textdomain' );

 // [bt_highlight]
function bt_highlight( $atts, $content ) {
	extract( shortcode_atts( array(
	), $atts, 'bt_highlight' ) );
	return '<span class="btHighlight">' . wptexturize( do_shortcode( $content ) ) . '</span>';
}
add_shortcode( 'bt_highlight', 'bt_highlight' );

// [bt_drop_cap type="1/2/3"]
function bt_drop_cap( $atts, $content ) {
	extract( shortcode_atts( array(
		'type' => '1'
	), $atts, 'bt_drop_cap' ) );
	
	$type = intval( $type );
	
	$class = 'enhanced';
	
	if ( $type == 2 ) {
		$class = 'enhanced circle colored';
	} else if ( $type == 3 ) {
		$class = 'enhanced ring';
	}

	return '<span class="' . $class . '">' . wptexturize( do_shortcode( $content ) ) . '</span>';
}
add_shortcode( 'bt_drop_cap', 'bt_drop_cap' );

// [bt_image]
class bt_image {
	static function init() {
		add_shortcode( 'bt_image', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'image'  		=> '',
			'caption_text'	=> '',
			'size'     		=> '',
			'url'      		=> '',
			'el_style' 		=> '',
			'el_class' 		=> ''
		), $atts, 'bt_image' ) );
		
		$image = sanitize_text_field( $image );
		$caption_text = sanitize_text_field( $caption_text );
		$size = sanitize_text_field( $size );
		$url = sanitize_text_field( $url );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		
		if ( $size == '' ) $size = 'large';
		
		$caption = '';
		if ( $image != '' ) {
			$post_image = get_post( $image );
			if ( $post_image ) $caption = get_post( $image )->post_excerpt;
			$image = wp_get_attachment_image_src( $image, $size );
			$image = $image[0];
		}
		
		$class_html = '';
		if ( $el_class != '' ) {
			$class_html = ' ' . $el_class;
		}
		
		$style_html = '';
		if ( $el_style != '' ) {
			$style_html= ' ' . 'style="' . $el_style . '"';
		}	
		
		$output = '<div class = "btImage"><img src="' . $image . '" class="btImage' . $class_html . '" alt="' . $image . '"' . $style_html . '></div>';		
		
		if ( $url != '' ) {
			$link_output = '<div class="bpgPhoto"> 
					<a href="' . $url . '" target="_blank"></a>
					<div class="boldPhotoBox"><div class="bpbItem">' . $output . '</div></div>
					<span class="captionPane">
						<span class="captionTable">
							<span class="captionCell">
								<span class="captionTxt"><strong>' . $caption . '</strong>' . $caption_text . '</span>
							</span>
						</span>
					</span>
			</div>';
			
			$output = $link_output;
		}
 		
		return $output;
	}
}

remove_shortcode( 'image' );
// [image]
class image {
	static function init() {
		add_shortcode( 'image', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts ) {
		extract( shortcode_atts( array(
				'ids'      => '',
				'orderby'  => '',
				'order'    => '',
				'size'     => '',
				'el_style' => '',
				'el_class' => ''
		), $atts, 'gallery' ) );

		$ids = sanitize_text_field( $ids );
		$orderby = sanitize_text_field( $orderby );
		$order = sanitize_text_field( $order );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		$size = sanitize_text_field( $size );

		if ( $orderby == 'post_date' ) {
			$orderby = 'date';
		}

		if ( $orderby == '' ) {
			$orderby = 'post__in';
		}

		if ( $order == '' ) {
			$order = 'ASC';
		}

		if ( $size == '' ) {
			$size = 'large';
		}

		$ids = trim( $ids );
		$ids = explode( ',', $ids );
		$the_query = new WP_Query( array ( 'post_type' => 'attachment', 'post_status' => 'any', 'orderby' => $orderby, 'order' => $order, 'post__in' => $ids, 'posts_per_page' => -1, 'nopaging' => true ) );

		$output = '';

		while ( $the_query->have_posts() ) {

			$the_query->the_post();
			$img = wp_get_attachment_image_src( $the_query->post->ID, $size );
				
			$img_full = wp_get_attachment_image_src( $the_query->post->ID, 'full' );
			$img_full = $img_full[0];

			$img = $img[0];
			$caption = $the_query->post->post_excerpt;
			$title = $the_query->post->post_title;
				
			$output = '<div class="mediaBox"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( $title ) . '"></div>';
				
		}

		wp_reset_postdata();


		return $output;
	}
}

remove_shortcode( 'gallery' );
// [gallery]
class gallery {
	static function init() {
		add_shortcode( 'gallery', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'ids'      => '',
			'orderby'  => '',
			'order'    => '',
			'size'     => '',
			'el_style' => '',
			'el_class' => ''
		), $atts, 'gallery' ) );
		
		$ids = sanitize_text_field( $ids );
		$orderby = sanitize_text_field( $orderby );
		$order = sanitize_text_field( $order );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		$size = sanitize_text_field( $size );
		
		if ( $orderby == 'post_date' ) {
			$orderby = 'date';
		}
		
		if ( $orderby == '' ) {
			$orderby = 'post__in';
		}
		
		if ( $order == '' ) {
			$order = 'ASC';
		}
		
		if ( $size == '' ) {
			$size = 'large';
		}
		
		$ids = trim( $ids );
		$ids = explode( ',', $ids );
		$the_query = new WP_Query( array ( 'post_type' => 'attachment', 'post_status' => 'any', 'orderby' => $orderby, 'order' => $order, 'post__in' => $ids, 'posts_per_page' => -1, 'nopaging' => true ) );
		
		$output = '';
		
		while ( $the_query->have_posts() ) {
		
			$the_query->the_post();
			$img = wp_get_attachment_image_src( $the_query->post->ID, $size );
			
			$img_full = wp_get_attachment_image_src( $the_query->post->ID, 'full' );
			$img_full = $img_full[0];			
	
			$img = $img[0];
			$caption = $the_query->post->post_excerpt;
			$title = $the_query->post->post_title;
			
			$output .= '<div class="bpbItem"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( $title ) . '"></div>';
			
		}
		
		wp_reset_postdata();
		
		$class_html = '';
		if ( $el_class != '' ) {
			$class_html = ' ' . $el_class;
		}
		
		$style_html = '';
		if ( $el_style != '' ) {
			$style_html= ' ' . 'style="' . $el_style . '"';
		}		

		$output = '<div class="boldPhotoSlide' . $class_html . '"' . $style_html . '>' . $output . '</div>';
		
		return $output;
	}
}

// [bt_grid_gallery]
class bt_grid_gallery {
	static function init() {
		add_shortcode( 'bt_grid_gallery', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts ) {
	
		wp_enqueue_script( 
			'bt_imagesloaded',
			plugin_dir_url( __FILE__ ) . 'imagesloaded.pkgd.min.js',
			array( 'jquery' ),
			'',
			true
		);
		
		wp_enqueue_script( 
			'bt_packery',
			plugin_dir_url( __FILE__ ) . 'packery.pkgd.min.js',
			array( 'jquery' ),
			'',
			true
		);	
		
		wp_enqueue_script( 
			'bt_grid_tweak',
			plugin_dir_url( __FILE__ ) . 'bt_grid_tweak.js',
			array( 'jquery' ),
			'',
			true
		);
	
		wp_enqueue_script( 
			'bt_grid_gallery',
			plugin_dir_url( __FILE__ ) . 'bt_grid_gallery.js',
			array( 'jquery' ),
			'',
			true
		);
	
		extract( shortcode_atts( array(
			'ids'       => '',
			'format'    => '',
			'columns'   => '',
			'lightbox'  => '',
			'orderby'   => '',
			'order'     => '',
			'has_thumb' => '',
			'links'     => '',
			'el_style'  => '',
			'el_class'  => ''
		), $atts, 'bt_grid_gallery' ) );
		
		$ids = sanitize_text_field( $ids );
		$format = sanitize_text_field( $format );
		$columns = sanitize_text_field( $columns );
		$lightbox = sanitize_text_field( $lightbox );
		$orderby = sanitize_text_field( $orderby );
		$order = sanitize_text_field( $order );
		$has_thumb = sanitize_text_field( $has_thumb );
		$links = sanitize_text_field( $links );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		
		$format_arr = explode( ',', $format );
		
		$links_arr = explode( ',', $links );
		
		if ( $orderby == 'post_date' ) {
			$orderby = 'date';
		}
		
		if ( $orderby == '' ) {
			$orderby = 'post__in';
		}
		
		if ( $order == '' ) {
			$order = 'ASC';
		}
		
		$ids = trim( $ids );
		$ids = explode( ',', $ids );
		$the_query = new WP_Query( array ( 'post_type' => 'attachment', 'post_status' => 'any', 'orderby' => $orderby, 'order' => $order, 'post__in' => $ids, 'posts_per_page' => -1, 'nopaging' => true ) );
		
		$output = '';
		
		$n = 0;
		
		$lightbox_class = '';
		
		while ( $the_query->have_posts() ) {

			$the_query->the_post();
			
			$size = 'grid_11';
			$tile_format = '11';
			
			if ( isset( $format_arr[ $n ] ) ) {
				if ( $format_arr[ $n ] == '11' ) {
					$size = 'grid_11';
					$tile_format = '11';
				} else if ( $format_arr[ $n ] == '21' ) {
					$size = 'grid_21';
					$tile_format = '21';
				} else if ( $format_arr[ $n ] == '12' ) {
					$size = 'grid_12';
					$tile_format = '12';
				} else if ( $format_arr[ $n ] == '22' ) {
					$size = 'grid_22';
					$tile_format = '22';
				}
			}
			
			$img = wp_get_attachment_image_src( $the_query->post->ID, $size );
			$img = $img[0];
			
			$caption = $the_query->post->post_excerpt;
			
			$data_order_num = $n;
			if ( $has_thumb == 'yes' ) {
				$data_order_num++;
			}
			
			if ( $lightbox != 'yes' ) {
				$link = '<a href="#"></a>';
			} else {
				$lightbox_class = ' ' . 'lightbox';
				$img_full = wp_get_attachment_image_src( $the_query->post->ID, 'full' );
				$img_full = $img_full[0];
				$link = '<a href="' . esc_url( $img_full ) . '" class="lightbox" data-title="' . esc_attr( $caption ) . '"></a>';
			}
			
			if ( isset( $links_arr[ $n ] ) && $links_arr[ $n ] != '' ) {
				$lightbox_class = '';
				$link = '<a href="' . $links_arr[ $n ] . '" target="_blank"></a>';
			}

			$output .= '<div class="bpgPhoto gridItem bt' . $tile_format . '">
				' . $link . '
				<div class="boldPhotoBox">
					<div class="bpbItem">
						<div class="btImage">
							<img src="' . $img . '" alt="' . $img . '" data-order-num="' . $data_order_num . '" id="img_' . $data_order_num . '">
						</div>
					</div>
				</div>
				<span class="captionPane">
					<span class="captionTable">
						<span class="captionCell">
							<span class="captionTxt"><strong>' . $caption . '</strong></span>
						</span>
					</span>
				</span>
			</div>';
			
			$n++;
		}
		
		wp_reset_postdata();
		
		$class_html = '';
		if ( $el_class != '' ) {
			$class_html = ' ' . $el_class;
		}
		
		$style_html = '';
		if ( $el_style != '' ) {
			$style_html= ' ' . 'style="' . $el_style . '"';
		}		

		$output = '<div class="tilesWall tiled' . $class_html . $lightbox_class . '"' . $style_html . ' data-col="' . $columns . '"><div class="gridSizer"></div>' . $output . '</div>';
		
		return $output;
	}
}

// [bt_section]
class bt_section {
	static function init() {
		add_shortcode( 'bt_section', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		
		extract( shortcode_atts( array(
			'layout'           => '', // boxed/wide
			'top_spaced'       => '', // not-spaced/semiSpaced/spaced/extraSpaced
			'bottom_spaced'    => '', // not-spaced/semiSpaced/spaced/extraSpaced
			'skin'             => '', // inherit/dark/light
			'full_screen'      => '', // no/yes
			'back_image'       => '',
			'back_video'       => '',
			'video_settings'   => '',
			'parallax'         => '',
			'parallax_offset'  => '',
			'divider'          => '', // no/yes
			'el_id'            => '',
			'el_class'         => '',
			'el_style'         => ''
		), $atts, 'bt_section' ) );
		
		$layout = sanitize_text_field( $layout );
		$top_spaced = sanitize_text_field( $top_spaced );
		$bottom_spaced = sanitize_text_field( $bottom_spaced );
		$skin = sanitize_text_field( $skin );
		$full_screen = sanitize_text_field( $full_screen );
		$back_image = sanitize_text_field( $back_image );
		$back_video = sanitize_text_field( $back_video );
		$video_settings = sanitize_text_field( $video_settings );
		$parallax = sanitize_text_field( $parallax );
		$parallax_offset = sanitize_text_field( $parallax_offset );
		$divider = sanitize_text_field( $divider );
		$el_id = sanitize_text_field( $el_id );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		
		$class = array( 'boldSection' );

		if ( $divider != 'no' && $divider != '' ) {
			$class[] = 'divider';
		}

		if ( $top_spaced != 'not-spaced' && $top_spaced != '' ) {
			$class[] = $top_spaced;
		}

		if ( $bottom_spaced != 'not-spaced' && $bottom_spaced != '' ) {
			$class[] = $bottom_spaced;
		}
		
		if ( $skin == 'dark' ) {
			$class[] = 'dark';
		} else if ( $skin == 'light' ) {
			$class[] = 'light';
		}
		
		global $bt_sidebar;
		
		if ( $layout != 'wide' && ! $bt_sidebar ) {
			$class[] = 'gutter';
		}

		if ( $full_screen == 'yes' && ! $bt_sidebar ) {
			$class[] = 'fullScreen';
		}

		$data_parallax_attr = '';
		if ( $parallax != '' && ! wp_is_mobile() ) {
		
			wp_enqueue_script( 
				'bt_parallax',
				plugin_dir_url( __FILE__ ) . 'bt_parallax.js',
				array( 'jquery' ),
				'',
				true
			);
		
			$data_parallax_attr = 'data-parallax="' . $parallax . '" data-parallax-offset="' . intval( $parallax_offset ) . '"';
			$class[] = 'bt_parallax';
		}
		
		if ( $back_image != '' ) {
			$back_image = wp_get_attachment_image_src( $back_image, 'full' );
			$back_image_url = $back_image[0];
			$back_image_style = 'background-image:url(\'' . $back_image_url . '\');';
			$el_style = $back_image_style . $el_style;	
			$class[] = 'wBackground cover';
		}
		
		$id_attr = '';
		if ( $el_id == '' ) {
			$el_id = uniqid( 'bt_section' );
		}
		$id_attr = 'id="' . $el_id . '"';
		
		$back_video_attr = '';
		if ( $back_video != '' ) {
			wp_enqueue_style( 'bt_style_yt', plugin_dir_url( __FILE__ ) . 'css/YTPlayer.css', array(), false );
			wp_enqueue_script( 
				'bt_yt',
				plugin_dir_url( __FILE__ ) . 'jquery.mb.YTPlayer.min.js',
				array( 'jquery' ),
				'',
				true
			);
			
			$class[] = 'bt_yt_video';
			
			if ( $video_settings == '' ) {
				$video_settings = 'showControls:false,showYTLogo:false,mute:true,stopMovieOnBlur:false,opacity:1';
			}
			
			$back_video_attr = ' ' . 'data-property="{videoURL:\'' . $back_video . '\',containment:\'self\',' . $video_settings . '}"';
			$proxy = new YT_Video_Proxy();
			add_action( 'wp_footer', array( $proxy, 'js_init' ) );			
		}
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
		

		$output = '<section ' . $id_attr . ' ' . $data_parallax_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . $back_video_attr . '>';
		/*if ( $data_parallax_attr != '' ) {
			$output .= '<div class="bt_parallax_img" style="background-image:url(' . $back_image_url . ')"></div>';
		}*/
		$output .= '<div class="port">';
		$output .= '<div class="boldCell">';
		$output .= wptexturize( do_shortcode( $content ) );
		$output .= '</div>';
		$output .= '</div>';

		$output .= '</section>';
		
		return $output;
	}
}

class YT_Video_Proxy {
	function __construct() {
		
	}	

	public function js_init() { ?>
		<script>
			jQuery(function() {
				jQuery( '.bt_yt_video' ).YTPlayer();
			});
		</script>
	<?php }
}

// [bt_row]
class bt_row {
	static function init() {
		add_shortcode( 'bt_row', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'el_class'  	     => '',
			'el_style'  		 => '',
			'el_vertical_align'  => ''
		), $atts, 'bt_row' ) );
		
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		$el_vertical_align = sanitize_text_field( $el_vertical_align );
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
	
		$output = '<div class="boldRow ' . $el_class . '" ' . $style_attr . '>';
		$output .= wptexturize( do_shortcode( $content ) );
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_column]
class bt_column {
	static function init() {
		add_shortcode( 'bt_column', array( __CLASS__, 'handle_shortcode' ) );
	}
	
	static function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
	
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'width'    		   => '',
			'align'   		   => '', // inherit/left/right/center
			'animation'		   => '', // no_animation/...
			'vertical_align'   => '', // inherit/top/middle/bottom
			'border'           => '',
			'cell_padding'     => '', 
			'text_indent'	   => '', 
			'background_color' => '', 
			'transparent'	   => '', 
			'el_class' 		   => '',
			'el_style'		   => ''
		), $atts, 'bt_column' ) );
		
		$width = sanitize_text_field( $width );
		$align = sanitize_text_field( $align );
		$animation = sanitize_text_field( $animation );
		$vertical_align = sanitize_text_field( $vertical_align );
		$border = sanitize_text_field( $border );
		$cell_padding = sanitize_text_field( $cell_padding );
		$text_indent = sanitize_text_field( $text_indent );
		$background_color = sanitize_text_field( $background_color );
		$transparent = sanitize_text_field( $transparent );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		
		$class = array( 'rowItem' );
		
		if ( $border == 'btLeftBorder' || $border == 'btRightBorder' ) {
			$class[] = $border;
		}
		
		$array = explode( '/', $width );

		if ( empty( $array ) || $array[0] == 0 || $array[1] == 0 ) {
			$width = 12;
		} else {
			$top = $array[0];
			$bottom = $array[1];
			
			$width = 12 * $top / $bottom;
			
			if ( ! is_int( $width ) || $width < 1 || $width > 12 ) {
				$width = 12;
			}
		}
		
		if ( $width == 3 ) {
			$class[] = 'col-lg-3 col-md-6 col-sm-12';
		} else if ( $width == 4 ) {
			$class[] = 'col-md-4 col-sm-12';
		} else if ( $width == 6 ) {
			$class[] = 'col-md-6 col-sm-12';	
		} else {
			$class[] = 'col-sm-12 col-md-' . $width;
		}
		
		if ( $align == 'left' || $align == '' || $align == 'inherit' ) {
			$class[] = 'cellLeft';
		} else if ( $align == 'right' ) {
			$class[] = 'cellRight';
		} else if ( $align == 'center' ) {
			$class[] = 'cellCenter';
		}
		
		if ( $animation != 'no_animation' && $animation != '' ) {
			$class[] = $animation;
		}		

		if ( $text_indent != 'no_text_indent' && $text_indent != '' ) {
			$class[] = $text_indent;
		}		

		if ( $vertical_align != 'Inherit' && $vertical_align != '' ) {
			$class[] = $vertical_align;
		}		

		if ( $cell_padding != 'default' && $cell_padding != '' ) {
			$class[] = $cell_padding;
		}	
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
		
		if($transparent == "") {
			$transparent = 1;
		}

		if($background_color != "") {
			$background_color = bt_column::hex2rgb( $background_color );
			$background_color = 'style="background: rgba(' . $background_color[0] . ', ' . $background_color[1] . ', ' . $background_color[2] . ', ' . $transparent . ');"';
		}
				
		$output = '<div class="' . implode( ' ', $class ) . '" ' . $style_attr . ' >';
			$output .= '<div class="rowItemContent" ' . $background_color .'>';
				$output .= wptexturize( do_shortcode( $content ) );
			$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_text]
class bt_text {
	static function init() {
		add_shortcode( 'bt_text', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {

		$output = '<div class="btText">' . wptexturize( do_shortcode( $content ) ) . '</div>';
		
		return $output;
	}
}

// [bt_header]
class bt_header {
	static function init() {
		add_shortcode( 'bt_header', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'superheadline'		=> '',
			'headline'			=> '',
			'headline_size'		=> '', // small/medium/big
			'dash'				=> '', // no/top/bottom
			'subheadline'		=> '', 
			'el_class' 			=> '',
			'el_style'			=> ''
		), $atts, 'bt_header' ) );
		
		$superheadline = sanitize_text_field( $superheadline );
		$headline = sanitize_text_field( $headline );
		$headline_size = sanitize_text_field( $headline_size );
		$dash = sanitize_text_field( $dash );
		$subheadline = sanitize_text_field( $subheadline );	
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );

		if ( $superheadline != '' ) {
			$superheadline = '<p class="boldSuperTitle">' . $superheadline . '</p>';
		}

		if ( $subheadline != '' ) {
			$subheadline = '<p class="boldSubTitle">' . $subheadline . '</p>';
		}
		
		$h_tag = 'h1';
		$class = '';

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		if ( $headline_size != '' ) {
			$class .= $headline_size;
		}
		if ( $headline_size == 'medium' ) {
			$h_tag = 'h2';
		} else if ( $headline_size == 'small' ) {
			$h_tag = 'h3';
		}

		if ( $dash == 'yes' ) {
			$dash = 'top';
		}
		
		if ( $dash != 'no' ) {
			$class .= ' bt_dash';
		}
		if( $dash != 'no' && $dash != '' ) {
			$class .= ' btDash ' . $dash . 'Dash';
		}

		if ( $el_class != '' ) {
			$class .= ' ' . $el_class;
		}
		
		$output = '<header class="header ' . $class . '"' . $style_attr . '>';
		$output .= $superheadline;
        if ( $headline != '' ) $output .= '<' . $h_tag . '><span class="dash"><span class="h2content">' . $headline . '</span></span></' . $h_tag . '>';
        $output .= $subheadline;
        $output .= '</header>';
		
		return $output;
	}
}

// [bt_testimonials]
class bt_testimonials {
	static function init() {
		add_shortcode( 'bt_testimonials', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(

		), $atts, 'bt_testimonials' ) );

		$content = do_shortcode( $content );
		$content = explode( '%$%', $content );

		$output = '<div class="btTestimonials">';
					$output .= '<div class="testimonialClients">';
						$output .= '<div class="testimonialClientsPort">';
							for ( $i = 0; $i < count( $content ); $i = $i + 2 ) {
								$output .= wptexturize( $content[ $i ] );
							}
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="btTestimonies">';
						$output .= '<div class="btTestimoniesPort">';
							for ( $i = 1; $i < count( $content ); $i = $i + 2 ) {
								$output .= wptexturize( $content[ $i ] );
							}
						$output .= '</div>';
				$output .= '</div>';
		$output .= '</div>';
		
		$proxy = new bt_testimonials_proxy();
		add_action( 'wp_footer', array( $proxy, 'js_init' ) );
		
		return $output;
	}
}

class bt_testimonials_proxy {
	function __construct() {
	}

	public function js_init() { ?>
		<script>
			(function( $ ) {
	
	            $( document ).ready(function() {
	
	                $( '.btTestimoniesPort' ).slick({
	                    slide: '.btTestimony',
	                    slidesToShow: 1,
	                    slidesToScroll: 1,
	                    arrows: false,
	                    dots: false,
	                    infinite: true,
	                    vertical: true,
	                    asNavFor: '.testimonialClientsPort'
	                });
	
	                $( '.testimonialClientsPort' ).slick({
	                    slide: '.tClient',
	                    slidesToShow: 5,
	                    slidesToScroll: 1,
	                    asNavFor: '.btTestimoniesPort',
	                    dots: false,
	                    arrows: false,
	                    infinite: true,
	                    centerMode: true,
	                    centerPadding: '0',
	                    focusOnSelect: true
	                });
	
	            });
	        })( jQuery );
		</script>
	<?php }
}

// [bt_testimonials_items]
class bt_testimonials_items {
	static function init() {
		add_shortcode( 'bt_testimonials_items', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'headline'	=> '',
			'image'     => '',
			'text'      => '',
			'name'		=> '',
			'job'		=> ''
		), $atts, 'bt_testimonials_items' ) );

		$headline = sanitize_text_field( $headline );
		
		$caption = '';
		if ( $image != '' ) {
			$post_image = get_post( $image );
			if ( $post_image ) $caption = get_post( $image )->post_excerpt;
			$image = wp_get_attachment_image_src( $image );
			$image = $image[0];
		}
		
		$text = sanitize_text_field( $text );
		$name = sanitize_text_field( $name );
		$job = sanitize_text_field( $job );
		
		$output1 = '<div class="tClient">
			<div class="tcItem">
				<img class="tcAspect" src="' . get_template_directory_uri() . '/gfx/aspect-square.png" aria-hidden="true" rel="nofollow" alt="Aspect image">
				<span style="background-image: url(' . $image . ');"></span>
			</div>
		</div>';
		
		$output2 = '<div class="btTestimony">
			<h4>' . $headline . '</h4>
			<p>' . $text . '</p>
			<p><strong>' . $name . '<br/>' . $job . '</strong></p>
		</div>';	
		
		return $output1 . '%$%' . $output2 . '%$%';

	}
}

// [bt_tabs]
class bt_tabs {
	static function init() {
		add_shortcode( 'bt_tabs', array( __CLASS__, 'handle_shortcode' ) );
	}
	
	static function handle_shortcode( $atts, $content ) {
	
		$content = do_shortcode( $content );
		$content = explode( '%$%', $content );	
		
		$output = '<div class="btTabs tabsHorizontal">';
			$output .='<ul class="tabsHeader">';
				for ( $i = 0; $i < count( $content ); $i = $i + 2 ) {
					$output .= wptexturize( $content[ $i ] );
				}
			$output .='</ul>';
			$output .='<div class="tabPanes tabPanesTabs">';
				for ( $i = 1; $i < count( $content ); $i = $i + 2 ) {
					$output .= wptexturize( $content[ $i ] );
				}
			$output .='</div>';
		$output .='</div>';
		
		$proxy = new bt_tabs_proxy();
		add_action( 'wp_footer', array( $proxy, 'js_init' ) );
		
		return $output;
	}
}
class bt_tabs_proxy {
	function __construct() {
	}	

	public function js_init() { ?>
		<script>
			(function( $ ) {
	            $( document ).ready(function () {
	                $( '.tabsHorizontal .tabPanesTabs' ).slick({
	                    slide: '.tabPane',
	                    slidesToShow: 1,
	                    slidesToScroll: 1,
	                    arrows: false,
	                    dots: false,
	                    infinite: false,
	                    centerMode: false,
	                    adaptiveHeight: true,
	                    asNavFor: '.tabsHeader'
	                });
	                $( '.tabsHorizontal .tabsHeader' ).slick({
	                    slide: 'li',
	                    slidesToScroll: 1,
	                    asNavFor: '.tabPanesTabs',
	                    dots: false,
	                    arrows: false,
	                    infinite: false,
	                    centerMode: false,
	                    variableWidth: true,
	                    centerPadding: '0',
	                    focusOnSelect: true
	                });
	            });
	        })( jQuery );
		</script>
	<?php }
}

// [bt_tabs_items]
class bt_tabs_items {
	static function init() {
		add_shortcode( 'bt_tabs_items', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
				'headline'	=> '',
		), $atts, 'bt_tabs_items' ) );

		$headline = sanitize_text_field( $headline );

		$output1 = '<li><span>' . $headline . '</span></li>';
		
		$output2 = '<div class="tabPane">
			<div class="tabAccordionTitle"><span>' . $headline . '</span></div>
			<div class="tabAccordionContent">' . wptexturize( $content ) . '</div>
		</div>';
		
		return $output1 . '%$%' . $output2 . '%$%';

	}
}

// [bt_accordion]
class bt_accordion {
	static function init() {
		add_shortcode( 'bt_accordion', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
	
		$content = do_shortcode( $content );
		$content = explode( '%$%', $content );	

		$output = '<div class="btTabs tabsVertical">';
			$output .= '<ul class="tabsHeader">';
				for ( $i = 0; $i < count( $content ); $i = $i + 2 ) {
					$output .= wptexturize( $content[ $i ] );
				}
			$output .= '</ul>';
			$output .= '<div class="tabPanes accordionPanes">';
				for ( $i = 1; $i < count( $content ); $i = $i + 2 ) {
					$output .= wptexturize( $content[ $i ] );
				}
			$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_accordion_items]
class bt_accordion_items {
	static function init() {
		add_shortcode( 'bt_accordion_items', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
				'headline'	=> '',
		), $atts, 'bt_accordion_items' ) );

		$headline = sanitize_text_field( $headline );

		$output1 = '<li><span>' . $headline . '</span></li>';
		
		$output2 = '<div class="tabPane">
			<div class="tabAccordionTitle"><span>' . $headline . '</span></div>
			<div class="tabAccordionContent">' . wptexturize( $content ) . '</div>
		</div>';
		
		return $output1 . '%$%' . $output2 . '%$%';

	}
}

// [bt_service]
class bt_service {
	static function init() {
		add_shortcode( 'bt_service', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'icon'      => '',
			'icon_type' => '',
			'url'       => '',
			'headline'  => '',
			'text'      => '',
			'el_style'  => '',
			'el_class'  => ''
		), $atts, 'bt_service' ) );
		
		$icon = sanitize_text_field( $icon );
		$icon_type = sanitize_text_field( $icon_type );
		$url = sanitize_text_field( $url );
		$headline = sanitize_text_field( $headline );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' style="' . $el_style . '"';
		}
		
		$text_arr = preg_split( '/$\R?^/m', $text );
		
		$text_final = '';
		foreach ( $text_arr as $item ) {
			$text_final .= $item . '<br>';
		}
		
		trim( $text_final, '<br>' );
		
		$fa = '-bold';
		
		if ( substr( $icon, 0, 1 ) == 'f' ) {
			$fa = '-fa';
		}
		
		$output = '<div class="servicesItem ' . $el_class . '"' . $style_attr . '>';
			$output .= '<div class="sIcon">';
				if ( $url == '' ) $url != '#';
				$output .= '<div class="ico ' . $icon_type . '"><a href="' . $url . '" data-ico' . $fa . '="&#x' . $icon . ';"></a></div>';
			$output .= '</div>';
			$output .= '<div class="sTxt">';
				$output .= '<header class="header small"><h3><span class="dash"><span class="h2content">' . $headline . '</span></span></h3></header><p>' . $text_final . '</p>';
			$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_gmaps]
class bt_gmaps {
	static function init() {
		add_shortcode( 'bt_gmaps', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts ) {
	
		wp_enqueue_script( 
			'gmaps_api',
			'https://maps.googleapis.com/maps/api/js?v=&sensor=false'
		);	
	
		extract( shortcode_atts( array(
			'latitude'  => '',
			'longitude' => '',
			'zoom'      => '',
			'height'    => '',
			'el_style'  => '',
			'el_class'  => ''
		), $atts, 'bt_gmaps' ) );
		
		$latitude = sanitize_text_field( $latitude );
		$longitude = sanitize_text_field( $longitude );
		$zoom = sanitize_text_field( $zoom );
		$height = sanitize_text_field( $height );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		
		if ( $zoom == '' ) $zoom = 14;
		if ( $height == '' ) $height = '250px';
		
		if ( $el_class != '' ) {
			$el_class = 'class="' . $el_class . '"';
		}
		
		$map_id = uniqid( 'map_canvas' );
		
		$proxy = new Gmaps_Proxy( $map_id, $latitude, $longitude, $zoom );
		add_action( 'wp_footer', array( $proxy, 'js_init' ), 20 );	
		
		return '<div id="' . $map_id . '" style="width:100%;height:' . $height . ';' . $el_style . ';" ' . $el_class . '></div>';
	}
}

class Gmaps_Proxy {
	function __construct( $map_id, $latitude, $longitude, $zoom ) {
		$this->map_id    = $map_id;
		$this->latitude  = $latitude;
		$this->longitude = $longitude;
		$this->zoom      = $zoom;
	}	

	public function js_init() { ?>
		<script>
			var init_gmaps<?php echo $this->map_id; ?> = function() {
				var myLatLng = new google.maps.LatLng( <?php echo $this->latitude; ?>, <?php echo $this->longitude; ?> );
				var mapOptions = {
					zoom: <?php echo $this->zoom; ?>,
					center: myLatLng,
					scrollwheel: false,
					scaleControl:true,
					zoomControl: true,
					zoomControlOptions: {
						style: google.maps.ZoomControlStyle.SMALL,
						position: google.maps.ControlPosition.RIGHT_CENTER
					},
					streetViewControl: true,
					mapTypeControl: true
				}
				var map = new google.maps.Map( document.getElementById( '<?php echo $this->map_id; ?>' ), mapOptions );

				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map
				});
			};
			init_gmaps<?php echo $this->map_id; ?>();
		</script>
	<?php }
}

// [bt_clients]
class bt_clients {
	static function init() {
		add_shortcode( 'bt_clients', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
				'display_type'    => ''
		), $atts, 'bt_clients' ) );
		
		$display_type = sanitize_text_field( $display_type );

		if ( $display_type == 'regular' ) {
			$extra_class = "boldClientRegularList";
			$inner_class = "";
		} else {
			$extra_class = "boldClientList";
			$inner_class = "bclPort";
		}
		
		$output = '<div class="' . $extra_class . '">';
			$output .= '<div class="' . $inner_class . '">';
				$output .= wptexturize( do_shortcode( $content ) );
			$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}


// [bt_client]
class bt_client {
	static function init() {
		add_shortcode( 'bt_client', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'image'    => '',
			'url'      => ''
		), $atts, 'bt_client' ) );
		
		$image = sanitize_text_field( $image );
		$url = sanitize_text_field( $url );
		
		if ( $image != '' ) {
			$image = wp_get_attachment_image_src( $image, 'medium' );
			$image = $image[0];
		}
		
		$output = '<div class="bclItem">';
			$output .= '<div class = "bclItemChild"><img src = "' . get_template_directory_uri() . '/gfx/aspect-square.png" alt="Aspect image">';
				if ( $url != '' ) {
					$output .= '<div style="background-image:url(' . $image . ');"><a href="' . $url . '"></a></div>';
				} else {
					$output .= '<div style="background-image:url(' . $image . ');"></div>';
				}
			$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_button]
class bt_button {
	static function init() {
		add_shortcode( 'bt_button', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'text'     => '',
			'icon'     => '',
			'url'      => '',
			'target'      => '',
			'style'    => '',
			'size'     => '',
			'width'     => '',
			'el_style' => '',
			'el_class' => ''			
		), $atts, 'bt_button' ) );
		
		$text = sanitize_text_field( $text );
		$icon = sanitize_text_field( $icon );
		$url = sanitize_text_field( $url );
		$target = sanitize_text_field( $target );
		$style = sanitize_text_field( $style );
		$size = sanitize_text_field( $size );
		$width = sanitize_text_field( $width );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );		
		
		$class = array( 'boldBtn' );
		
		if ( $style == 'dark' ) {
			$class[] = 'btnDark';
		} else if ( $style == 'gray' ){
			$class[] = 'btnGray';
		} else {
			$class[] = 'btnAccent';
		}
		 
		if ( $size == 'small' ) {
			$class[] = 'btnSmall';
		} else {
			$class[] = 'btnBig';
		}
		
		if ( $width == 'full' ) {
			$class[] = 'btnBlock';
		}

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		if ( $icon != '' ) {
			$class[] = 'btnIco';
		}
		
		$style_attr = '';

		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . $el_style . '"';
		}
		
		if ( $url == '' ) {
			$url = '#';
		}

		if ( $target != 'no_target' ) {
			$target = 'target="' . $target . '"';
		} else {
			$target= '';
		}
		
		$fa = '-bold';
		
		if ( substr( $icon, 0, 1 ) == 'f' ) {
			$fa = '-fa';
		}
		
		$output = '<div class="' . implode( ' ', $class ) . '"' . $style_attr . '><a href="' . $url . '"  data-ico' . $fa . '="&#x' . $icon . ';" ' . $target . '>' . $text . '</a></div>';
		
		return $output;
	}
}

// [bt_counter]
class bt_counter {
	static function init() {
		add_shortcode( 'bt_counter', array( __CLASS__, 'handle_shortcode' ) );
	}
	
	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'number' => ''
		), $atts, 'bt_counter' ) );

		$number = sanitize_text_field( $number );
		$output = '';
		$output .= '<div class="btCounterHolder">';
		$output .= '<span class="btCounter animate" data-digit-length="' . strlen( $number ) . '">';
		for ( $i = 0; $i < strlen($number); $i++ ) {
			$output .= '<span class="onedigit p' . ( strlen( $number ) - $i ) . ' d' . $number[ $i ] . '" data-digit="' . $number[ $i ] . '">';
			for ( $j = 0; $j <= $number[ $i ]; $j++ ) {
				$output .= '<span class="n' . $j . '" style="position:relative;">' . $j . '</span>';
			}
			$output .= '</span>';
		}
		$output .= '</span>';
		$output .= '</div>';
			
		return $output;
	}
}

// [bt_percentage_bar]
class bt_percentage_bar {
	static function init() {
		add_shortcode( 'bt_percentage_bar', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'text'       => '',
			'percentage' => ''
		), $atts, 'bt_percentage_bar' ) );

		$text = sanitize_text_field( $text );
		if( $text == '' ) $text = $percentage . "%";
		$percentage = sanitize_text_field( $percentage );

		$output = '';
		$output .= '<div class="btProgressBar animate">';
		$output .= '<div class="btProgressContent">';
		$output .= '<div data-percentage="' . $percentage . '" class="btProgressAnim animate"><span>' . $text . '</span></div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}

// [bt_slider]
class bt_slider {
	static function init() {
		add_shortcode( 'bt_slider', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'auto_play' => '',
			'height'    => '',
			'el_style'  => '',
			'el_class'  => ''			
		), $atts, 'bt_slider' ) );
		
		$auto_play = sanitize_text_field( $auto_play );
		$height = sanitize_text_field( $height );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );		
		
		$class = array( 'slided' );
		
		$slick_data = '';
		$auto_play = intval( $auto_play );
		if ( $auto_play > 0 ) {
			$slick_data = ' ' . "data-slick='{\"autoplay\":true,\"autoplaySpeed\":" . $auto_play . ",\"pauseOnHover\":false,\"pauseOnDotsHover\":true}'";
		}

		if ( $height == '' ) {
			$class[] = "autoSliderHeight";
		} else {
			$class[] = $height . "SliderHeight";
		}
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		$output = '<div class="' . implode( ' ', $class ) . '" ' . $style_attr . $slick_data . '>';
			$output .= wptexturize( do_shortcode( $content ) );
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_slider_item]
class bt_slider_item {
	static function init() {
		add_shortcode( 'bt_slider_item', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'image'    => '',
			'el_style' => '',
			'el_class' => ''			
		), $atts, 'bt_slider_item' ) );
		
		$image = sanitize_text_field( $image );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		
		$img_full = '';
		$img_thumb = '';
		if ( $image != '' ) {
			$img_full = wp_get_attachment_image_src( $image, 'full' );
			$img_full = $img_full[0];
			$img_thumb = wp_get_attachment_image_src( $image, 'medium' );
			$img_thumb = $img_thumb[0];		
		}		
		
		$class = array( 'slidedItem', 'firstItem' );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		$output = '<div class="' . implode( ' ', $class ) . '" ' . $style_attr . ' data-thumb="' . $img_thumb . '">';
			$output .= '<div class="port wBackground cover" style="background-image: url(\'' . $img_full . '\')">';
				$output .= '<div class="boldCell" data-slick="yes">';
					$output .= '<div class="btSlideGutter">';
						$output .= '<div class="btSlidePane">';
							$output .= wptexturize( do_shortcode( $content ) );
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';	
		$output .= '</div>';		
		
		return $output;
	}
}

// [bt_hr]
class bt_hr {
	static function init() {
		add_shortcode( 'bt_hr', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'top_spaced'  			 => '',
			'bottom_spaced'  		 => '',
			'transparent_border'  	 => '',
			'el_style'				 => '',
			'el_class' 				 => ''			
		), $atts, 'bt_hr' ) );
		
		$top_spaced = sanitize_text_field( $top_spaced );
		$bottom_spaced = sanitize_text_field( $bottom_spaced );
		$transparent_border = sanitize_text_field( $transparent_border );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		
		$class = array();
		if ( $top_spaced != 'not-spaced' && $top_spaced != '' ) {
			$class[] = $top_spaced;
		}

		if ( $bottom_spaced != 'not-spaced' && $bottom_spaced != '' ) {
			$class[] = $bottom_spaced;
		}

		if ( $transparent_border != '') {
			$class[] = $transparent_border;
		}
		
		if ( $el_class != '') {
			$class[] = $el_class;
		}
		
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . $el_style . '"';
		}
		

		$output = '<hr class="' . implode( ' ', $class ) . '" '. $style_attr . '>';
		
		return $output;
	}
}

// [bt_icon]
class bt_icon {
	static function init() {
		add_shortcode( 'bt_icon', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'icon'      => '',
			'icon_type' => '',
			'url'       => ''
		), $atts, 'bt_icon' ) );
		
		$icon = sanitize_text_field( $icon );
		$icon_type = sanitize_text_field( $icon_type );
		$url = sanitize_text_field( $url );

		$fa = '-bold';
		
		if ( substr( $icon, 0, 1 ) == 'f' ) {
			$fa = '-fa';
		}
		
		$output = '<div class="ico ' . $icon_type . '"><a href="' . $url . '" data-ico' . $fa . '="&#x' . $icon . ';"></a></div>';

		return $output;
	}
}

// [bt_icons]
class bt_icons {
	static function init() {
		add_shortcode( 'bt_icons', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'el_style' => '',
			'el_class' => ''
		), $atts, 'bt_icons' ) );
		
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );		
		
		$class = array( 'socialRow' );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		$output = '<div class="' . implode( ' ', $class ) . '" ' . $style_attr . '>';
			$output .= wptexturize( do_shortcode( $content ) );
		$output .= '</div>';
		
		return $output;
	}
}

// [bt_grid]
class bt_grid {
	static function init() {
		add_shortcode( 'bt_grid', array( __CLASS__, 'handle_shortcode' ) );
		add_action( 'wp_ajax_bt_get_grid', array( __CLASS__, 'bt_get_grid_callback' ) );
		add_action( 'wp_ajax_nopriv_bt_get_grid', array( __CLASS__, 'bt_get_grid_callback' ) );
	}
	
	static function bt_get_grid_callback() {
		$data = bt_get_posts_data( intval( $_POST['number'] ), intval( $_POST['offset'] ), $_POST['cat_slug'], $_POST['post_type'] );
		bt_grid::bt_dump_grid( $data, $_POST['grid_type'], $_POST['post_type'], $_POST['format'], $_POST['tiles_title'] );
		die();
	}
	
	static function bt_dump_grid( $data, $grid_type, $post_type, $format, $tiles_title ) {
		if ( count( $data ) == 0 ) {
			echo 'no_posts';
			die();
		}
		
		$new_arr = array();
		
		$format_arr = explode( ',', $format );
		
		$i = 0;
		foreach( $data as $post ) {
		
			$item = '';
			
			if ( isset( $format_arr[ $i ] ) ) {
				if ( $format_arr[ $i ] == '21' ) {
					$tile_format = '21';
				} else if (  $format_arr[ $i ] == '12' ) {
					$tile_format = '12';
				} else if (  $format_arr[ $i ] == '22' ) {
					$tile_format = '22';
				} else {
					$tile_format = '11';
				}
			} else {
				if ( $post['tile_format'] != '' ) {
					$tile_format = $post['tile_format'];
					if ( $tile_format != '11' || $tile_format != '12' || $tile_format != '21' || $tile_format != '22' ) $tile_format = '11';
				} else {
					$tile_format = '11';
				}
			}
			
			$img_size = 'grid_' . $tile_format;
			
			if ( $grid_type  == 'classic' ) {
				$img_size = 'grid';
			}
			
			// post formats
			
			$media_html = '';
			
			$img_src = '';
			$post_thumbnail_id = get_post_thumbnail_id( $post['ID'] );
			
			$hw = '';
			
			if ( $post_thumbnail_id != '' ) {
				$img = wp_get_attachment_image_src( $post_thumbnail_id, $img_size );
				$img_src = $img[0];
				if ( $grid_type == 'classic' && $img[1] != '' ) $hw = $img[2] / $img[1];
				
			} else if ( ( $post['format'] == 'image' && count( $post['images'] ) > 0 ) || ( $post_type == 'portfolio' && count( $post['images'] ) == 1 ) ) {
				foreach ( $post['images'] as $img ) {
					$img = wp_get_attachment_image_src( $img['ID'], $img_size );
					$img_src = $img[0];
					if ( $grid_type == 'classic' && $img[1] != '' ) $hw = $img[2] / $img[1];
					break;
				}
			}
			
			if ( $grid_type == 'classic' ) {
			
				if ( $post['format'] == 'gallery' ) {
						
					if ( count( $post['images'] ) > 0 ) {
						$images_ids = array();
						foreach ( $post['images'] as $img ) {
							$images_ids[] = $img['ID'];
						}
						$img = wp_get_attachment_image_src( $images_ids[0], 'grid' );
						$src = $img[0];
						if ( $img[1] == 0 || $img[1] == '' ) {
							$media_html = '';
						} else {
							$hw = $img[2] / $img[1];
							$media_html = '<div class="boldPhotoBox" data-hw="' . $hw . '">' . do_shortcode( '[gallery size="grid" ids="' . join( ',', $images_ids ) . '"]' ) . '</div>';
						}
					}
					
				} else if ( $post['format'] == 'video' || ( $post_type == 'portfolio' && $post['video'] != '' ) ) {
				
					$hw = 9 / 16;
					
					$media_html = '<div class="boldPhotoBox video" data-hw="' . $hw . '"><img class="aspectVideo" src="' . esc_url( get_template_directory_uri() . '/gfx/video-16.9.png' ) . '" alt="" role="presentation" aria-hidden="true">';

					if ( strpos( $post['video'], 'vimeo.com/' ) > 0 ) {
						$video_id = substr( $post['video'], strpos( $post['video'], 'vimeo.com/' ) + 10 );
						$media_html .= '<ifra' . 'me src="' . esc_url( 'http://player.vimeo.com/video/' . $video_id ) . '" allowfullscreen></ifra' . 'me>';
					} else {
						$yt_id_pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
						$youtube_id = ( preg_replace( $yt_id_pattern, '$1', $post['video'] ) );
						if ( strlen( $youtube_id ) == 11 ) {
							$media_html .= '<ifra' . 'me width="560" height="315" src="' . esc_url( 'http://www.youtube.com/embed/' . $youtube_id ) . '" allowfullscreen></ifra' . 'me>';
						} else {
							$media_html = '<div class="boldPhotoBox video" data-hw="' . $hw . '">';
							$media_html .= do_shortcode( $post['video'] );
						}
					}
					
					$media_html .= '</div>';
					
					if ( $post['video'] == '' ) {
						$media_html = '';
					}
					
				} else if ( $post['format'] == 'audio' || ( $post_type == 'portfolio' && $post['audio'] != '' ) ) {
					
					if ( strpos( $post['audio'], '</ifra' . 'me>' ) > 0 ) {
						$media_html = '<div class="boldPhotoBox audio">' . wp_kses( $post['audio'], array( 'iframe' => array( 'height' => array(), 'src' =>array() ) ) ) . '</div>';
					} else {
						$media_html = '<div class="boldPhotoBox audio">' . do_shortcode( $post['audio'] ) . '</div>';
					}
					
					if ( $post['audio'] == '' ) {
						$media_html = '';
					}
					
				} else if ( $post['format'] == 'link' || ( $post_type == 'portfolio' && $post['link_url'] != '' ) ) {
					
					$media_html = '<div class="boldPhotoBox"><div class="bpbItem wBoldLink"><a href="' . esc_url( $post['link_url'] ) . '"><span class="ico" data-icon-pe="&#xe641;"></span><strong>' . esc_html( $post['link_title'] ) . '</strong><span class="bUrl">' . esc_url( $post['link_url'] ) . '</span></a></div></div>';
					
					if ( esc_html( $post['link_title'] ) == '' || esc_url( $post['link_url'] ) == '' ) {
						$media_html = '';
					}
					
				} else if ( $post['format'] == 'quote' || ( $post_type == 'portfolio' && $post['quote'] != '' ) ) {
					
					$media_html = '<div class="boldPhotoBox"><blockquote><div class="bqIcon" data-icon-pe="&#xe668;"></div><p>' . esc_html( $post['quote'] ) . '</p><cite>' . esc_html( $post['quote_author'] ) . '</cite></blockquote></div>';
					
					if ( esc_html( $post['quote'] ) == '' || esc_html( $post['quote_author'] ) == '' ) {
						$media_html = '';
					}
				}
			}
			
			if ( $media_html == '' ) {
				$extra_class = ' ' . 'noPhoto';
				if ( $img_src != '' ) {
					if ( $grid_type == 'classic' ) {
						$media_html = '<div class="boldPhotoBox" data-hw="' . $hw . '"><div class="bpbItem"><a href="' . esc_url( $post['permalink'] ) . '"><img src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $img_src ) . '"></a></div></div>';
					} else {
						$media_html = '<div class="boldPhotoBox" data-hw="' . $hw . '"><div class="bpbItem"><div class="btImage"><img src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $img_src ) . '"></div></div></div>';
					}
					$extra_class = '';
				} else if ( $grid_type != 'classic' ) {
					$media_html = '<div class="boldPhotoBox"><div class="bpbItem"><div class="btImage"><img src="' . get_template_directory_uri() . '/gfx/ph_tiles.png' . '" alt="ph_tiles.png"></div></div></div>';
				}
			}
			
			if ( $grid_type == 'classic' ) {
			
				$comments = '';
				if ( $post['comments'] !== false ) {
					$comments = ' ' . '<a class="boldArticleComments" href="' . esc_url( $post['permalink'] ) . '#comments">' . $post['comments'] . '</a>';
				}
				
				$author = '';
				if ( $post_type == 'portfolio' ) {
					$author = '';
					$bold_article_meta = '';
				} else {
					$author = ' ' . $post['author'];
					$bold_article_meta = '<p class="boldArticleMeta">' . $post['date'] . $author . $comments . '</p>';
				}				
				
				if ( $post_type == 'portfolio' ) {
					$share_html = bt_get_share_html( $post['permalink'], 'pf' );
				} else {
					$share_html = bt_get_share_html( $post['permalink'] );	
				}
				
				if ( $media_html == '' ) $media_html = '<div class="boldPhotoBox"><a href="' . esc_url_raw( $post['permalink'] ) . '"></a><div class="bpbItem"></div></div>';
			
				$new_arr[ $i ]['container_class'] = 'gridItem';
				$item .= $media_html . '
					<div class="bgiTxt">
						<p class="bgiCat">' . $post['category'] . '</p>
						<h2><a href="' . esc_url_raw( $post['permalink'] ) . '">' . $post['title'] . '</a></h2>
						' . $bold_article_meta . '
						<p class="btGridShare">' . $share_html . '</p>
					</div><!-- /bgiTxt -->';
			} else {
				$new_arr[ $i ]['container_class'] = 'bpgPhoto gridItem bt' . $tile_format . $extra_class;
				$item .= '<a href="' . esc_url_raw( $post['permalink'] ) . '"></a>';
				$item .= $media_html . '
						<span class="captionPane">
							<span class="captionTable">
								<span class="captionCell">
									<span class="captionTxt">';
				if ( $tiles_title != 'yes' ) {
					$item .= '			<strong>' . $post['title'] . '</strong><span>' . strip_tags( $post['category'] ) . '</span>';
				}
				$item .= '			</span>
								</span>
							</span>
						</span>
					';
				if ( $tiles_title == 'yes' ) {
					$item .= '
					<div class="btShowTitle">
						<span class="btShowTitleCaptionTxt">
							<strong>' . $post['title'] . '</strong>
							<span>' . strip_tags( $post['category'] ) . '</span>
							<a href="' . esc_url_raw( $post['permalink'] ) . '">' . __( 'VIEW PROJECT', 'bt_plugin' ) . '</a>
						</span>
					</div>';
				}
			}
			
			$new_arr[ $i ]['html'] = $item;
			$new_arr[ $i ]['hw'] = $hw;
			$i++;			
			
		}

		echo json_encode( $new_arr );
	}
	
	static function handle_shortcode( $atts, $content ) {
	
		extract( shortcode_atts( array(
			'number'          => '',
			'columns'         => '',
			'category'        => '',
			'grid_type'       => '',
			'format'          => '',
			'tiles_title'     => '',
			'post_type'       => '',
			'scroll_loading'  => '',
			'category_filter' => '',
			'el_class'        => '',
			'el_style'        => ''
		), $atts, 'bt_grid' ) );
		
		$number = sanitize_text_field( $number );
		$columns = sanitize_text_field( $columns );	
		$category = sanitize_text_field( $category );
		$category_filter = sanitize_text_field( $category_filter );
		$grid_type = sanitize_text_field( $grid_type );
		$format = sanitize_text_field( $format );
		$tiles_title = sanitize_text_field( $tiles_title );
		$post_type = sanitize_text_field( $post_type );
		$scroll_loading = sanitize_text_field( $scroll_loading );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
		
		if ( $number == '' || $number <= 0 ) $number = 12;
		
		$col = 4;
		if ( $columns != '' ) $col = $columns;
		
		if ( $grid_type != 'classic' ) $grid_type = 'tiled';

		$tiles_title_class = '';

		if ( $tiles_title == 'yes' ) $tiles_title_class = 'btHasTitles';
		
		if ( $post_type != 'portfolio' ) $post_type = 'post';
		
		if ( $scroll_loading != 'yes' ) {
			$scroll_loading = 'no';
		}
		
		wp_enqueue_script( 
			'bt_imagesloaded',
			plugin_dir_url( __FILE__ ) . 'imagesloaded.pkgd.min.js',
			array( 'jquery' ),
			'',
			true
		);
		
		wp_enqueue_script( 
			'bt_packery',
			plugin_dir_url( __FILE__ ) . 'packery.pkgd.min.js',
			array( 'jquery' ),
			'',
			true
		);
		
		wp_enqueue_script( 
			'bt_grid_tweak',
			plugin_dir_url( __FILE__ ) . 'bt_grid_tweak.js',
			array( 'jquery' ),
			'',
			true
		);		
		
		wp_enqueue_script( 
			'bt_grid',
			plugin_dir_url( __FILE__ ) . 'bt_grid.js',
			array( 'jquery' ),
			'',
			true
		);
		
		$output = '<div class="btGridContainer ' . $grid_type . ' ' . $el_class . ' ' . $tiles_title_class . '" ' . $style_attr . '>';
		if ( $category_filter == 'yes' ) {
			if ( $post_type == 'post' ) {
				$cats = get_categories();
			} else {
				$cats = get_categories( array( 'type' => 'portfolio', 'taxonomy' => 'portfolio_category' ) );
			}
			$output .= '<div class="btCatFilter">';
			$output .= '<span class="btCatFilterTitle">' . __( 'Category filter:', 'bt_plugin' ) . '</span>';
			$output .= '<span class="btCatFilterItem all" data-slug="">' . __( 'All', 'bt_plugin' ) . '</span>';
			foreach ( $cats as $cat ) {
				$output .= '<span class="btCatFilterItem" data-slug="' . $cat->slug . '">' . $cat->name . '</span>';
			}
			$output .= '</div>';
		}
		$output .= '<div class="tilesWall ' . $grid_type . '" data-num="' . $number . '" data-tiles-title="' . $tiles_title . '" data-grid-type="' . $grid_type . '" data-post-type="' . $post_type . '" data-col="' . $col . '" data-cat-slug="' . $category . '" data-scroll-loading="' . $scroll_loading . '" data-format="' . $format . '">';
		$output .= '<div class="gridSizer"></div>';
		$output .= '</div>';
		$output .= '<div class="bt_loader bt_loader_grid"></div><div class="bt_no_more">' . esc_html( __( 'no more posts', 'bt_plugin' ) ) . '</div>';
		$output .= '</div>';
		
		return $output;

	}
}

// [bt_latest_posts]
class bt_latest_posts {
	static function init() {
		add_shortcode( 'bt_latest_posts', array( __CLASS__, 'handle_shortcode' ) );
	}
	static function handle_shortcode( $atts, $content ) {
	
		extract( shortcode_atts( array(
			'number'          => '',
			'category'        => '',
			'format'          => '',
			'post_type'       => '',
			'el_class'        => '',
			'el_style'        => ''
		), $atts, 'bt_grid' ) );
		
		$number = sanitize_text_field( $number );
		$category = sanitize_text_field( $category );
		$format = sanitize_text_field( $format );
		$post_type = sanitize_text_field( $post_type );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
		
		if ( $number == '' || $number <= 0 ) $number = 3;

		$column_width = 12;

		$img_size = 'latest_posts';
		$dash_class = '';
		$dash_class = '';
		$indent_class = '';

		if( $format == 'horizontal' ) {
			$column_width = intval(12/$number);
			$img_size = 'grid_21';
			$dash_class = 'btDash topDash';
			$indent_class = 'btTextIndent';
		}
		
		if ( $post_type != 'portfolio' ) $post_type = 'post';

		$data = bt_get_posts_data( $number, 0, $category, $post_type );
		
		$output = '<div class="btLatestPostsContainer ' . $format . 'Posts ' . $el_class . '" ' . $style_attr . '>';
		
		$i = 0;
		foreach( $data as $post_item ) { 
			$i++;
			if ( $i > $number ) break;
			
			$img_src = '';
			$post_thumbnail_id = get_post_thumbnail_id( $post_item['ID'] );
			
			$hw = '';
			
			if ( $post_thumbnail_id != '' ) {
				$img = wp_get_attachment_image_src( $post_thumbnail_id, $img_size );
				$img_src = $img[0];
				if ( $img[1] != '' ) $hw = $img[2] / $img[1];
				
			} else if ( ( $post_item['format'] == 'image' && count( $post_item['images'] ) > 0 ) || ( $post_type == 'portfolio' && count( $post_item['images'] ) == 1 ) ) {
				foreach ( $post_item['images'] as $img ) {
					$img = wp_get_attachment_image_src( $img['ID'], $img_size );
					$img_src = $img[0];
					if ( $img[1] != '' ) $hw = $img[2] / $img[1];
					break;
				}
			}
			
			$comments = '';
			if ( $post_item['comments'] !== false ) {
				$comments = ' ' . '<a class="boldArticleComments" href="' . esc_url( $post_item['permalink'] ) . '#comments">' . $post_item['comments'] . '</a>';
			}
			
			$author = '';
			if ( $post_type == 'portfolio' ) {
				$author = '';
				$bold_article_meta = '';
			} else {
				$author = ' ' . $post_item['author'];
				// $bold_article_meta = '<p class="boldArticleMeta">' . $post_item['date'] . $author . $comments . '</p>';
			 	$bold_article_meta = $post_item['date'];
			}

			$output .= '
				<div class="btSingleLatestPost col-md-' . $column_width . ' col-sm-12 ' . $indent_class . ' inherit"' . $el_style . '>
					<div class = "btSingleLatestPostImage">
						<div class="bpgPhoto"> 
							<a href="' . $post_item['permalink'] . '" target="_self"></a>
							<div class="boldPhotoBox"><div class="bpbItem"><div class="btImage"><img src="' . $img_src . '" class="btImage" alt="' . $post_item['title'] . '"></div></div></div>
							<span class="captionPane">
								<span class="captionTable">
									<span class="captionCell">
										<span class="captionTxt">' . '<strong></strong></span>
									</span>
								</span>
							</span>
						</div>
					</div>
					<div class = "btSingleLatestPostContent">
						<header class="header small ' . $dash_class . '">
							<h3><span class="dash"><span class="h2content"><a href="' . $post_item['permalink'] . '" target="_self">' . $post_item['title'] . '</a></span></span></h3><p class="boldSubTitle">' . $bold_article_meta . '</p>
						</header>
						<p>' . $post_item['excerpt'] . '</p>
					</div>
				</div>';
		}

		$output .= '</div>';
		
		return $output;

	}
}

// [bt_quote_booking]
class bt_quote_booking {

	static function init() {
		add_shortcode( 'bt_quote_booking', array( __CLASS__, 'handle_shortcode' ) );
		add_action( 'wp_ajax_bt_quote_booking', array( __CLASS__, 'bt_quote_booking_callback' ) );
		add_action( 'wp_ajax_nopriv_bt_quote_booking', array( __CLASS__, 'bt_quote_booking_callback' ) );
	}
	
	static function bt_quote_booking_callback() {
		$admin_email = $_POST['admin_email'];
		$subject = urldecode( $_POST['subject'] );
		$quote = urldecode( $_POST['quote'] );
		$total = $_POST['total'];
		$name = $_POST['name'];
		$email = strip_tags( $_POST['email'] );
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$message = $_POST['message'];
		
		$message_to_admin = '<html><body>';
		
		$message_to_admin .= '<table style="width:100%" cellspacing="0">';
		if ( $quote != '' ) $message_to_admin .= $quote;
		$message_to_admin .= '<tr><td style="font-weight:bold;border-top:1px solid #888;padding:.5em;">' . __( 'Total', 'bt_plugin' ) . '</td><td style="text-align:right;font-weight:bold;border-top:1px solid #888;padding:.5em;">' . $total . '</td></tr>';
		$message_to_admin .= '</table>';
		
		$message_to_admin .= '<br>';
		
		if ( $name != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Name', 'bt_plugin' ) . '</b>: ' . $name . '</div>';
		if ( $email != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Email', 'bt_plugin' ) . '</b>: <a href="mailto:' . $email . '">' . $email . '</a></div>';
		if ( $phone != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Phone', 'bt_plugin' ) . '</b>: ' . $phone . '</div>';
		if ( $address != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Address', 'bt_plugin' ) . '</b>: ' . $address . '</div>';
		if ( $date != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Service Date', 'bt_plugin' ) . '</b>: ' . $date . '</div>';
		if ( $time != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Service Time', 'bt_plugin' ) . '</b>: ' . $time . '</div>';
		if ( $message != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Message', 'bt_plugin' ) . '</b>: ' . $message . '</div>';
		
		$message_to_admin .= '</body></html>';
		
		$message_to_admin = quoted_printable_encode( $message_to_admin );
		
		$headers = '';
		if ( $email != '' ) $headers = "From: " . $email . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers .= "Content-Transfer-Encoding: quoted-printable";
		
		$s = $subject;
		if ( $name != '' ) $s = $s . ' / ' . $name;
		
		try{
			$r = wp_mail( $admin_email, $s, $message_to_admin, $headers );
			if ( $r ) echo 'ok';
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
		
		die();
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'admin_email' => '',
			'subject'     => '',
			'time_start'  => '',
			'time_end'    => '',
			'currency'    => '',
			'm_name'      => '',
			'm_email'     => '',
			'm_phone'     => '',
			'm_address'   => '',
			'm_date'      => '',
			'm_time'      => '',
			'm_message'   => '',
			'el_class'    => '',
			'el_style'    => ''
		), $atts, 'bt_quote_booking' ) );
		
		$admin_email = sanitize_text_field( $admin_email );
		$subject = sanitize_text_field( $subject );
		$time_start = sanitize_text_field( $time_start );
		$time_end = sanitize_text_field( $time_end );
		$currency = sanitize_text_field( $currency );
		$m_name = sanitize_text_field( $m_name );
		$m_email = sanitize_text_field( $m_email );
		$m_phone = sanitize_text_field( $m_phone );
		$m_address = sanitize_text_field( $m_address );
		$m_date = sanitize_text_field( $m_date );
		$m_time = sanitize_text_field( $m_time );
		$m_message = sanitize_text_field( $m_message );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		
		wp_enqueue_script( 'jquery-ui-datepicker' );
		
		wp_enqueue_script( 'jquery-ui-slider' );
		
		wp_enqueue_script( 'bt_touch-punch_js', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array( 'jquery-ui-slider' ), '', false );
		
		$proxy = new Quote_Proxy( $admin_email, $subject, $m_name, $m_email, $m_phone, $m_address, $m_message, $m_date, $m_time );
		add_action( 'wp_footer', array( $proxy, 'js_init' ), 20 );
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
		
		if ( $m_name != '' ) $m_name = ' ' . 'btContactField' . $m_name;
		if ( $m_email != '' ) $m_email = ' ' . 'btContactField' . $m_email;
		if ( $m_phone != '' ) $m_phone = ' ' . 'btContactField' . $m_phone;
		if ( $m_address != '' ) $m_address = ' ' . 'btContactField' . $m_address;
		if ( $m_message != '' ) $m_message = ' ' . 'btContactField' . $m_message;
		if ( $m_date != '' ) $m_date = ' ' . 'btContactField' . $m_date;
		if ( $m_time != '' ) $m_time = ' ' . 'btContactField' . $m_time;

		$output = '<div class="btQuoteBooking ' . $el_class . '" ' . $style_attr . '>';
		$output .= wptexturize( do_shortcode( $content ) );
		$output .= '<div class="btQuoteTotal"><span class="btQuoteTotalText">' . __( 'Total', 'bt_plugin' ) . '</span><span class="btQuoteTotalCurrency">' . $currency . '</span><span class="btQuoteTotalCalc"></span></div>';
		
		$output .= '<div class="btQuoteContact">';
		$output .= '<div class="btQuoteItem' . $m_name . '"><input type="text" class="btContactName btContactField" placeholder="' . __( 'Name', 'bt_plugin' ) . '"></div>';
		$output .= '<div class="btQuoteItem' . $m_email . '"><input type="text" class="btContactEmail btContactField" placeholder="' . __( 'Email', 'bt_plugin' ) . '"></div>';
		$output .= '<div class="btQuoteItem' . $m_phone . '"><input type="text" class="btContactPhone btContactField" placeholder="' . __( 'Phone', 'bt_plugin' ) . '"></div>';
		$output .= '<div class="btQuoteItem' . $m_phone . '"><input type="text" class="btContactAddress btContactField" placeholder="' . __( 'Address', 'bt_plugin' ) . '"></div>';
		$output .= '<div class="btQuoteItem' . $m_date . '"><input type="text" class="btContactDate btContactField" placeholder="' . __( 'Preferred Service Date', 'bt_plugin' ) . '"></div>';
		$output .= '<div class="btQuoteItem' . $m_time . '"><select type="text" class="btContactTime btContactField">';
		$output .= '<option value="">' . __( 'Preferred Service Time', 'bt_plugin' ) . '</option>';
		if ( $time_start == '' ) $time_start = 0;
		if ( $time_end == '' ) $time_end = 23;
		for ( $i = intval( $time_start ); $i <= intval( $time_end ); $i++ ) {
			if ( $i < 10 ) $i = '0' . $i;
			$output .= '<option value="' . $i . ':00">' . $i . ':00</option>';
		}
		$output .= '</select></div>';
		$output .= '<div class="btQuoteItem btQuoteItemFullWidth"><textarea class="btContactMessage btContactField' . $m_message . '" placeholder="' . __( 'Message', 'bt_plugin' ) . '"></textarea></div>';
		$output .= '<div class="boldBtn btnAccent btnSmall btnIco"><button type="submit" class="btContactSubmit" data-ico-bold="&#xe659;">' . __( 'Submit', 'bt_plugin' ) . '</button></div>';
		$output .= '<div class="btSubmitMessage"></div>';
		$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

class Quote_Proxy {
	function __construct( $admin_email, $subject, $m_name, $m_email, $m_phone, $m_address, $m_date, $m_time, $m_message ) {
		$this->admin_email = $admin_email;
		$this->subject = $subject;
		$this->m_name = $m_name;
		$this->m_email = $m_email;
		$this->m_phone = $m_phone;
		$this->m_address = $m_address;
		$this->m_date = $m_date;
		$this->m_time = $m_time;
		$this->m_message = $m_message;
	}	

	public function js_init() { ?>
		<script>
			(function( $ ) {
				
	            $( '.btContactDate' ).datepicker();
				
				$( '.btQuoteSlider' ).each(function() {
					$( this ).slider({
						min: $( this ).data( 'min' ),
						max: $( this ).data( 'max' ),
						step: $( this ).data( 'step' )
					});
				});
				
				$( '.ui-slider-handle' ).each(function() {
					$( this ).append( $( this ).closest( '.btQuoteItemInput' ).find( $( '.btQuoteSliderValue' ) ) );
				});
				
				var bt_parse_float = function( x ) {
					r = parseFloat( x );
					if ( isNaN( r ) ) r = 0;
					return r;
				}
				
				var total = 0;
				total = total.toFixed( 2 );
				
				var bt_quote_total = function() {
				
					total = 0;

					$( '.btQuoteText' ).not( '.btQuoteMBlock .btQuoteText' ).each(function() {
						var unit_price = bt_parse_float( $( this ).data( 'price' ) );
						var val = bt_parse_float( $( this ).val() );
						val = val * unit_price;
						total += val;
					});
					
					$( '.btQuoteSelect' ).not( '.btQuoteMBlock .btQuoteSelect' ).fancySelect().each(function() {
						var val = bt_parse_float( $( this )[0].value );
						total += val;
					});
					
					$( '.btQuoteSlider' ).not( '.btQuoteMBlock .btQuoteSlider' ).each(function() {
						var unit_price = bt_parse_float( $( this ).data( 'price' ) );
						var offset = bt_parse_float( $( this ).data( 'offset' ) );
						var val = bt_parse_float( $( this ).slider( 'value' ) );
						$( this ).parent().find( '.btQuoteSliderValue' ).html( val );
						val = val * unit_price;
						total += val;
						total += offset;
					});
					
					$( '.btQuoteMBlock' ).each(function() {
						var m_total = 0;
						var m_first = true;
						$( this ).find( '.btQuoteText' ).each(function() {
							var unit_price = bt_parse_float( $( this ).data( 'price' ) );
							var val = bt_parse_float( $( this ).val() );
							val = val * unit_price;
							if ( m_first ) {
								m_total = val;
							} else {
								m_total *= val;
							}
							m_first = false;
						});
						
						$( this ).find( '.btQuoteSelect' ).fancySelect().each(function() {
							var val = bt_parse_float( $( this )[0].value );
							if ( m_first ) {
								m_total = val;
							} else {
								m_total *= val;
							}
							m_first = false;
						});
						
						$( this ).find( '.btQuoteSlider' ).each(function() {
							var unit_price = bt_parse_float( $( this ).data( 'price' ) );
							var offset = bt_parse_float( $( this ).data( 'offset' ) );
							var val = bt_parse_float( $( this ).slider( 'value' ) );
							$( this ).parent().find( '.btQuoteSliderValue' ).html( val );
							val = val * unit_price;
							if ( m_first ) {
								m_total = val;
							} else {
								m_total *= val;
							}
							m_total += offset;
							m_first = false;
						});
						
						total += m_total;
						
					});
					
					total = total.toFixed( 2 ).replace( /(\d)(?=(\d{3})+\.)/g, '$1,' );
					
					$( '.btQuoteTotalCalc' ).html( total );
				}
				
				bt_quote_total();
				
				$( '.btQuoteText' ).keyup(function() {
					bt_quote_total();
				});
				
				$( '.btQuoteSelect' ).fancySelect().on( 'change.fs', function() {
					bt_quote_total();
				});

				$( '.btQuoteSlider' ).each(function() {
					var this_slider = $( this );
					$( this ).slider({
						slide: function( event, ui ) {
							var val = ui.value;
							this_slider.slider( 'value', val );
							bt_quote_total();
						}
					});
				});
				
				$( '.btContactSubmit' ).click(function() {
			
					var val = true;
					
					$( '.btContactField' ).each(function() {
						if ( $( this ).parent().hasClass( 'btContactFieldMandatory' ) && $( this ).val() == '' ) {
							$( this ).parent().addClass( 'btContactFieldError' );
							val = false;
						}
					});
					
					if ( ! val ) {
						$( '.btSubmitMessage' ).html( '<?php echo __( 'Please fill out all required fields.', 'bt_plugin' ); ?>' );
						return false;
					}
					
					var quote = '';
					var back = 0;
					var bt_is_odd = function( n ) {
						return ( n % 2 ) == 1;
					}
					$( '.btQuoteBooking .btQuoteItem' ).each(function() {
						back++;
						var item_val = 0;
						var selected_name = '';
						
						$( this ).find( '.btQuoteText' ).each(function() {
							item_val = bt_parse_float( $( this ).val() );
						});
						
						$( this ).find( '.btQuoteSelect' ).fancySelect().each(function() {
							selected_name = $( this )[0].selectedOptions[0].innerHTML;
							if ( $( this )[0].selectedOptions[0].index == 0 ) {
								selected_name = '';
							}
							item_val = bt_parse_float( $( this )[0].value );
						});
						
						$( this ).find( '.btQuoteSlider' ).each(function() {
							item_val = bt_parse_float( $( this ).slider( 'value' ) );
						});
						
						var label = $( this ).find( 'label' ).html();
						if ( selected_name != '' ) label = label + ': ' + selected_name;
						
						var background = '';
						if ( bt_is_odd( back ) ) background = ' ' + 'style="background:#eee;"';
						
						item_val = item_val.toFixed( 2 );
						
						if ( label !== undefined ) {
							quote += encodeURI( '<tr' + background + '><td style="padding:.5em;">' + label + '</td><td style="text-align:right;padding:.5em;">' + item_val + '</td></tr>' );
						}
					});

					var data = {
						'action': 'bt_quote_booking',
						'admin_email': '<?php echo $this->admin_email; ?>',
						'subject': '<?php echo $this->subject; ?>',
						'quote' : quote,
						'total' : total,
						'name' : $( '.btContactName' ).val(),
						'email' : $( '.btContactEmail' ).val(),
						'phone' : $( '.btContactPhone' ).val(),
						'address' : $( '.btContactAddress' ).val(),
						'date' : $( '.btContactDate' ).val(),
						'time' : $( '.btContactTime' ).val(),
						'message' : $( '.btContactMessage' ).val()
					};
					
					$( '.btSubmitMessage' ).html( '<?php echo __( 'Please wait...', 'bt_plugin' ); ?>' );
					
					$.ajax({
						type: 'POST',
						url: window.BTAJAXURL,
						data: data,
						async: true,
						success: function( response ) {
							if ( response == 'ok' ) {
								$( '.btSubmitMessage' ).html( '<?php echo __( 'Thank you, we will contact you soon!', 'bt_plugin' ); ?>' );
							} else {
								$( '.btSubmitMessage' ).html( '<?php echo __( 'Error! Please try again later.', 'bt_plugin' ); ?>' );
							}
						},
						error: function( xhr, status, error ) {
							$( '.btSubmitMessage' ).html( '<?php echo __( 'Error! Please try again later.', 'bt_plugin' ); ?>' );
						}
					});
				
				});	
				
	        })( jQuery );
			
		</script>
	<?php }
}

// [bt_quote_item]
class bt_quote_item {
	static function init() {
		add_shortcode( 'bt_quote_item', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'name'  => '',
			'type'  => '',
			'value' => ''
		), $atts, 'bt_quote_item' ) );
		
		$name = sanitize_text_field( $name );
		$type = sanitize_text_field( $type );

		if ( $type == 'text' ) {
		
			$price = round( floatval( $value ), 2 );
			$input = '<input type="text" class="btQuoteText" data-price="' . $price . '"/>';
			
		} else if ( $type == 'select' ) {
		
			$items_arr = preg_split( '/$\R?^/m', $value );
			$input = '<select class="btQuoteSelect">';
				$input .= '<option value="0">' . __( 'Select...', 'bt_plugin' ) . '</option>';
				foreach ( $items_arr as $item ) {
					$arr = explode( ';', $item );
					$input .= '<option value="' . $arr[1] . '">' . $arr[0] . '</option>';
				}
			$input .= '</select>';
			
		} else if ( $type == 'slider' ) {
		
			$arr = explode( ';', $value );
			$price = round( floatval( $arr[3] ), 2 );
			$offset = isset( $arr[4] ) ? round( floatval( $arr[4] ), 2 ) : 0;
			$input = '<div class="btQuoteSlider" data-min="' . $arr[0] . '" data-max="' . $arr[1] . '" data-step="' . $arr[2] . '" data-price="' . $price . '" data-offset="' . $offset . '"></div><span class="btQuoteSliderValue"></span>';
			
		}
		
		$output = '<div class="btQuoteItem"><label>' . $name . '</label><div class="btQuoteItemInput">' . $input . '</div></div>';

		return $output;
	}
}

// [bt_quote_multiply]
class bt_quote_multiply {
	static function init() {
		add_shortcode( 'bt_quote_multiply', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(

		), $atts, 'bt_quote_multiply' ) );
		
		$output = '<div class="btQuoteMBlock">' . wptexturize( do_shortcode( $content ) ) . '</div>';

		return $output;
	}
}

// [bt_price_list]
class bt_price_list {

	static function init() {
		add_shortcode( 'bt_price_list', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'title'       => '',
			'sticker'     => '',
			'currency'    => '',
			'price'       => '',
			'items'       => '',
			'button_text' => '',
			'button_link' => '',
			'el_class'    => '',
			'el_style'    => ''
		), $atts, 'bt_price_list' ) );
		
		$title = sanitize_text_field( $title );
		$sticker = sanitize_text_field( $sticker );
		$currency = sanitize_text_field( $currency );
		$price = sanitize_text_field( $price );
		$button_text = sanitize_text_field( $button_text );
		$button_link = sanitize_text_field( $button_link );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		$output = '<div class="btPriceTable ' . $el_class . '" ' . $style_attr . '>';
		
		if ( $sticker != '' ) $sticker = '<div class="ptSticker"><span>' . $sticker . '</span></div>';

		$items_arr = preg_split( '/$\R?^/m', $items );
		
		$output .= '<div class="ptHeader">' . $sticker . '<h3>' . $title . '</h3></div><!-- /ptHeader -->
			<p class="ptPrice"><span>' . $currency . '</span>' . $price . '</p>
			<ul>';
			foreach ( $items_arr as $item ) {
				$output .= '<li>' . $item . '</li>';
			}
			$output .= '</ul>
			<div class="ptFooter"><a href="' . $button_link . '">' . $button_text . '</a></div>
		';
		$output .= '</div>';
		
		return $output;
	}
}

bt_image::init();
gallery::init();
image::init();

bt_grid_gallery::init();

bt_section::init();
bt_row::init();
bt_column::init();
bt_text::init();
bt_header::init();
bt_testimonials::init();
bt_testimonials_items::init();
bt_tabs::init();
bt_tabs_items::init();
bt_accordion::init();
bt_accordion_items::init();
bt_service::init();
bt_gmaps::init();
bt_clients::init();
bt_client::init();
bt_button::init();
bt_counter::init();
bt_percentage_bar::init();
bt_slider::init();
bt_slider_item::init();
bt_hr::init();
bt_icon::init();
bt_icons::init();
bt_grid::init();
bt_latest_posts::init();
bt_quote_booking::init();
bt_quote_item::init();
bt_quote_multiply::init();
bt_price_list::init();

function bt_map_sc() {
	if ( function_exists( 'bt_rc_map' ) ) {
	
		bt_rc_map( 'bt_image', array( 'name' => __( 'Image', 'bt_plugin' ), 'description' => __( 'Single image', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => __( 'Image', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'caption_text', 'type' => 'textfield', 'heading' => __( 'Caption text', 'bt_plugin' ) ),
				array( 'param_name' => 'size', 'type' => 'textfield', 'heading' => __( 'Size (e.g. thumbnail, medium, large, full)', 'bt_plugin' ) ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
	
		bt_rc_map( 'bt_section', array( 'name' => __( 'Section', 'bt_plugin' ), 'description' => __( 'Basic root element', 'bt_plugin' ), 'root' => true, 'container' => 'vertical', 'accept' => array( 'bt_row' => true ), 'toggle' => true, 'show_settings_on_create' => false,
			'params' => array( 
				array( 'param_name' => 'layout', 'type' => 'dropdown', 'heading' => __( 'Layout', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Boxed', 'bt_plugin' ) => 'boxed',
						__( 'Wide', 'bt_plugin' ) => 'wide'
				) ),		
				array( 'param_name' => 'top_spaced', 'type' => 'dropdown', 'heading' => __( 'Top spaced', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'not-spaced',
						__( 'Small-Spaced', 'bt_plugin' ) => 'topSmallSpaced',		
						__( 'Semi-Spaced', 'bt_plugin' ) => 'topSemiSpaced',
						__( 'Spaced', 'bt_plugin' ) => 'topSpaced',
						__( 'Extra-Spaced', 'bt_plugin' ) => 'topExtraSpaced'
				) ),
				array( 'param_name' => 'bottom_spaced', 'type' => 'dropdown', 'heading' => __( 'Bottom spaced', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'not-spaced',
						__( 'Small-Spaced', 'bt_plugin' ) => 'bottomSmallSpaced',
						__( 'Semi-Spaced', 'bt_plugin' ) => 'bottomSemiSpaced',
						__( 'Spaced', 'bt_plugin' ) => 'bottomSpaced',
						__( 'Extra-Spaced', 'bt_plugin' ) => 'bottomExtraSpaced'
				) ),
				array( 'param_name' => 'skin', 'type' => 'dropdown', 'heading' => __( 'Skin', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Inherit', 'bt_plugin' ) => 'inherit',			
						__( 'Dark', 'bt_plugin' ) => 'dark',
						__( 'Light', 'bt_plugin' ) => 'light'
				) ),
				array( 'param_name' => 'full_screen', 'type' => 'dropdown', 'heading' => __( 'Full Screen', 'bt_plugin' ), 
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no',
						__( 'Yes', 'bt_plugin' ) => 'yes'
				) ),
				array( 'param_name' => 'divider', 'type' => 'dropdown', 'heading' => __( 'Divider', 'bt_plugin' ), 
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no',
						__( 'Yes', 'bt_plugin' ) => 'yes'
				) ),
				array( 'param_name' => 'back_image', 'type' => 'attach_image', 'heading' => __( 'Background Image', 'bt_plugin' ) ),
				array( 'param_name' => 'back_video', 'type' => 'textfield', 'heading' => __( 'YouTube Background Video', 'bt_plugin' ) ),
				array( 'param_name' => 'video_settings', 'type' => 'textfield', 'heading' => __( 'Video Settings (e.g. startAt:20, mute:true, stopMovieOnBlur:false)', 'bt_plugin' ) ),
				array( 'param_name' => 'parallax', 'type' => 'textfield', 'heading' => __( 'Parallax (e.g. -.7)', 'bt_plugin' ) ),
				array( 'param_name' => 'parallax_offset', 'type' => 'textfield', 'heading' => __( 'Parallax Offset in px (e.g. -100)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_row', array( 'name' => __( 'Row', 'bt_plugin' ), 'description' => __( 'Row element', 'bt_plugin' ), 'container' => 'horizontal', 'accept' => array( 'bt_column' => true ), 'toggle' => true, 'show_settings_on_create' => false,
			'params' => array( 
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_column', array( 'name' => __( 'Column', 'bt_plugin' ), 'description' => __( 'Column element', 'bt_plugin' ), 'width_param' => 'width', 'container' => 'vertical', 'accept' => array( 'bt_section' => false, 'bt_row' => false, 'bt_column' => false, '_content' => false, 'bt_client' => false, 'bt_icon' => false, 'bt_testimonials_items' => false, 'bt_tabs_items' => false, 'bt_accordion_items' => false, 'bt_slider_item' => false, 'bt_quote_item' => false, 'bt_quote_multiply' => false ), 'accept_all' => true, 'toggle' => true,
			'params' => array(
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => __( 'Align', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Left', 'bt_plugin' ) => 'left',
						__( 'Right', 'bt_plugin' ) => 'right',
						__( 'Center', 'bt_plugin' ) => 'center'					
				) ),
				array( 'param_name' => 'vertical_align', 'type' => 'dropdown', 'heading' => __( 'Vertical Align', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Inherit', 'bt_plugin' ) => 'inherit',
						__( 'Top', 'bt_plugin' )     => 'btTopVertical',
						__( 'Middle', 'bt_plugin' )  => 'btMiddleVertical',
						__( 'Bottom', 'bt_plugin' )  => 'btBottomVertical'					
				) ),
				array( 'param_name' => 'border', 'type' => 'dropdown', 'heading' => __( 'Border', 'bt_plugin' ),
					'value' => array(
						__( 'No Border', 'bt_plugin' ) => 'no_border',
						__( 'Left', 'bt_plugin' )      => 'btLeftBorder',
						__( 'Right', 'bt_plugin' )     => 'btRightBorder'					
				) ),				
				array( 'param_name' => 'cell_padding', 'type' => 'dropdown', 'heading' => __( 'Padding', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Default', 'bt_plugin' ) => 'default',
						__( 'No padding', 'bt_plugin' ) => 'btNoPadding',
						__( 'Double padding', 'bt_plugin' ) => 'btDoublePadding'				
				) ),
				array( 'param_name' => 'animation', 'type' => 'dropdown', 'heading' => __( 'Animation', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No Animation', 'bt_plugin' ) => 'no_animation',
						__( 'Fade In', 'bt_plugin' ) => 'animate animate-fadein',
						__( 'Move Up', 'bt_plugin' ) => 'animate animate-moveup',
						__( 'Move Left', 'bt_plugin' ) => 'animate animate-moveleft',
						__( 'Move Right', 'bt_plugin' ) => 'animate animate-moveright',
						__( 'Move Down', 'bt_plugin' ) => 'animate animate-movedown',
						__( 'Fade In / Move Up', 'bt_plugin' ) => 'animate animate-fadein animate-moveup',
						__( 'Fade In / Move Left', 'bt_plugin' ) => 'animate animate-fadein animate-moveleft',
						__( 'Fade In / Move Right', 'bt_plugin' ) => 'animate animate-fadein animate-moveright',
						__( 'Fade In / Move Down', 'bt_plugin' ) => 'animate animate-fadein animate-movedown'				
				) ),	
				array( 'param_name' => 'text_indent', 'type' => 'dropdown', 'heading' => __( 'Text indent', 'bt_plugin' ),
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no_text_indent',
						__( 'Yes', 'bt_plugin' ) => 'btTextIndent'
				) ),
				array( 'param_name' => 'background_color', 'type' => 'colorpicker', 'heading' => __( 'Background color', 'bt_plugin' ) ),
				array( 'param_name' => 'transparent', 'type' => 'textfield', 'heading' => __( 'Transparent (e.g. 0.4)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_text', array( 'name' => __( 'Text', 'bt_plugin' ), 'description' => __( 'Text element', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( '_content' => true ), 'toggle' => true, 'show_settings_on_create' => false ) );
		
		bt_rc_map( 'bt_header', array( 'name' => __( 'Header', 'bt_plugin' ), 'description' => __( 'Header element', 'bt_plugin' ),
			'params' => array( 
					array( 'param_name' => 'superheadline', 'type' => 'textfield', 'heading' => __( 'Superheadline', 'bt_plugin' ) ),
				array( 'param_name' => 'headline', 'type' => 'textfield', 'heading' => __( 'Headline', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'headline_size', 'type' => 'dropdown', 'heading' => __( 'Headline Size', 'bt_plugin' ), 'preview' => true, 
					'value' => array(
						__( 'Small', 'bt_plugin' ) => 'small',
						__( 'Medium', 'bt_plugin' ) => 'medium',
						__( 'Big', 'bt_plugin' ) => 'big'
				) ),		
				array( 'param_name' => 'dash', 'type' => 'dropdown', 'heading' => __( 'Dash', 'bt_plugin' ), 'preview' => true, 
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no',
						__( 'Top', 'bt_plugin' ) => 'top',
						__( 'Bottom', 'bt_plugin' ) => 'bottom'
		 		) ),
				array( 'param_name' => 'subheadline', 'type' => 'textfield', 'heading' => __( 'Subheadline', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);

		bt_rc_map( 'bt_testimonials', array( 'name' => __( 'Testimonials', 'bt_plugin' ), 'description' => __( 'Testimonials container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_testimonials_items' => true ), 'show_settings_on_create' => false )
		);
		
		bt_rc_map( 'bt_testimonials_items', array( 'name' => __( 'Testimonial Item', 'bt_plugin' ), 'description' => __( 'Single testimonial item', 'bt_plugin' ),
			'params' => array( 
				array( 'param_name' => 'headline', 'type' => 'textfield', 'heading' => __( 'Headline', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => __( 'Image', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => __( 'Text', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'job', 'type' => 'textfield', 'heading' => __( 'Job', 'bt_plugin' ), 'preview' => true )
			) )
		);
		
		
		bt_rc_map( 'bt_tabs', array( 'name' => __( 'Tabs', 'bt_plugin' ), 'description' => __( 'Tabs container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_tabs_items' => true ), 'show_settings_on_create' => false ));
		
		bt_rc_map( 'bt_tabs_items', array( 'name' => __( 'Tab Item', 'bt_plugin' ), 'description' => __( 'Tabs items', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( '_content' => true ), 'params' => array(
				array( 'param_name' => 'headline', 'type' => 'textfield', 'heading' => __( 'Headline', 'bt_plugin' ), 'preview' => true )
			) )
		);
		
		bt_rc_map( 'bt_accordion', array( 'name' => __( 'Accordion', 'bt_plugin' ), 'description' => __( 'Accordion container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_accordion_items' => true ), 'show_settings_on_create' => false ));
		
		bt_rc_map( 'bt_accordion_items', array( 'name' => __( 'Accordion Item', 'bt_plugin' ), 'description' => __( 'Single accordion element', 'bt_plugin' ), 'accept' => array( '_content' => true ), 'container' => 'vertical', 'params' => array(
				array( 'param_name' => 'headline', 'type' => 'textfield', 'heading' => __( 'Headline', 'bt_plugin' ), 'preview' => true )
			) )
		);
		
		if ( ! function_exists( 'bt_fa_icons' ) ) {
			require_once( 'bt_fa_icons.php' );
		}
		//$icon_arr = array_merge( bt_fa_icons(), bt_s7_icons() );
		$icon_arr = bt_fa_icons();
		ksort( $icon_arr );
		bt_rc_map( 'bt_service', array( 'name' => __( 'Service', 'bt_plugin' ), 'description' => __( 'Service element', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'icon', 'type' => 'dropdown', 'heading' => __( 'Icon', 'bt_plugin' ), 'value' => $icon_arr ),	
				array( 'param_name' => 'icon_type', 'type' => 'dropdown', 'heading' => __( 'Icon Type', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Default', 'bt_plugin' ) 	=> 'default',
						__( 'Accent', 'bt_plugin' )	 	=> 'accent',
						__( 'Borderless', 'bt_plugin' ) => 'borderless',
						__( 'White', 'bt_plugin' ) 		=> 'white'
					) ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bt_plugin' ) ),
				array( 'param_name' => 'headline', 'type' => 'textfield', 'heading' => __( 'Headline', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'text', 'type' => 'textarea', 'heading' => __( 'Text', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_gmaps', array( 'name' => __( 'Google Maps', 'bt_plugin' ), 'description' => __( 'Google Maps with marker on specified coordinates', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'latitude', 'type' => 'textfield', 'heading' => __( 'Latitude', 'bt_plugin' ) ),
				array( 'param_name' => 'longitude', 'type' => 'textfield', 'heading' => __( 'Longitude', 'bt_plugin' ) ),
				array( 'param_name' => 'zoom', 'type' => 'textfield', 'heading' => __( 'Zoom (e.g. 14)', 'bt_plugin' ) ),
				array( 'param_name' => 'height', 'type' => 'textfield', 'heading' => __( 'Height (e.g. 250px)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_clients', array( 'name' => __( 'Clients', 'bt_plugin' ), 'container' => 'vertical', 'description' => __( 'Client container', 'bt_plugin' ), 'accept' => array( 'bt_client' => true ), 'toggle' => true, 'show_settings_on_create' => false,
			'params' => array(
				array( 'param_name' => 'display_type', 'type' => 'dropdown', 'heading' => __( 'Type', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Slider', 'bt_plugin' ) => 'slider',
						__( 'Regular', 'bt_plugin' ) => 'regular'
					) )
			) )
		);
		
		bt_rc_map( 'bt_client', array( 'name' => __( 'Client', 'bt_plugin' ), 'description' => __( 'Individual client element', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => __( 'Image', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_button', array( 'name' => __( 'Button', 'bt_plugin' ), 'description' => __( 'Button with custom link', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => __( 'Text', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'icon', 'type' => 'dropdown', 'heading' => __( 'Icon', 'bt_plugin' ), 'value' => bt_fa_icons(), 'preview' => true ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bt_plugin' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => __( 'Target window', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no_target',
						__( 'Self', 'bt_plugin' ) => '_self',
						__( 'Blank', 'bt_plugin' ) => '_blank',
						__( 'Parent', 'bt_plugin' ) => '_parent',
						__( 'Top', 'bt_plugin' ) => '_top'
				) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => __( 'Style', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Dark', 'bt_plugin' ) => 'dark',
						__( 'Gray', 'bt_plugin' ) => 'gray',
						__( 'Accent', 'bt_plugin' ) => 'accent'
				) ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => __( 'Size', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Big', 'bt_plugin' ) => 'big',
						__( 'Small', 'bt_plugin' ) => 'small'				
				) ),
				array( 'param_name' => 'width', 'type' => 'dropdown', 'heading' => __( 'Width', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Normal', 'bt_plugin' ) => 'normal',
						__( 'Full', 'bt_plugin' ) => 'full'				
				) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )			
			) )
		);
		
		bt_rc_map( 'bt_counter', array( 'name' => __( 'Counter', 'bt_plugin' ), 'description' => __( 'Animated counter', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => __( 'Number', 'bt_plugin' ), 'preview' => true )
			) )
		);

		bt_rc_map( 'bt_percentage_bar', array( 'name' => __( 'Percentage bar', 'bt_plugin' ), 'description' => __( 'Animated percentage bar', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => __( 'Text', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'percentage', 'type' => 'textfield', 'heading' => __( 'Percentage', 'bt_plugin' ), 'preview' => true ),
			) )
		);
		
		bt_rc_map( 'bt_slider', array( 'name' => __( 'Slider', 'bt_plugin' ), 'description' => __( 'Slider container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_slider_item' => true ), 'toggle' => true, 'show_settings_on_create' => false,
			'params' => array(
				array( 'param_name' => 'height', 'type' => 'dropdown', 'heading' => __( 'Slider height', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Auto', 'bt_plugin' ) => 'auto',
						__( 'Small', 'bt_plugin' ) => 'small',
						__( 'Medium', 'bt_plugin' ) => 'medium',
						__( 'Large', 'bt_plugin' ) => 'large'
				) ),
				array( 'param_name' => 'auto_play', 'type' => 'textfield', 'heading' => __( 'Auto Play Speed (e.g. 3000)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_slider_item', array( 'name' => __( 'Slider Item', 'bt_plugin' ), 'description' => __( 'Individual slide element', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_header' => true, 'bt_button' => true, 'bt_counter' => true, 'bt_icons' => true, 'bt_text' => true, 'bt_hr' => true ),
			'params' => array( 
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => __( 'Image', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_hr', array( 'name' => __( 'Separator', 'bt_plugin' ), 'description' => __( 'Horizontal separator', 'bt_plugin' ),
			'params' => array( 
				array( 'param_name' => 'top_spaced', 'type' => 'dropdown', 'heading' => __( 'Top spaced', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'not-spaced',
						__( 'Small-Spaced', 'bt_plugin' ) => 'topSmallSpaced',		
						__( 'Semi-Spaced', 'bt_plugin' ) => 'topSemiSpaced',
						__( 'Spaced', 'bt_plugin' ) => 'topSpaced',
						__( 'Extra-Spaced', 'bt_plugin' ) => 'topExtraSpaced'
				) ),
				array( 'param_name' => 'bottom_spaced', 'type' => 'dropdown', 'heading' => __( 'Bottom spaced', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'not-spaced',
						__( 'Small-Spaced', 'bt_plugin' ) => 'bottomSmallSpaced',
						__( 'Semi-Spaced', 'bt_plugin' ) => 'bottomSemiSpaced',
						__( 'Spaced', 'bt_plugin' ) => 'bottomSpaced',
						__( 'Extra-Spaced', 'bt_plugin' ) => 'bottomExtraSpaced'
				) ),				
				array( 'param_name' => 'transparent_border', 'type' => 'dropdown', 'heading' => __( 'Border', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'noBorder',
						__( 'Yes', 'bt_plugin' ) => 'border'
				) ),				
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);

		bt_rc_map( 'bt_icon', array( 'name' => __( 'Icon', 'bt_plugin' ), 'description' => __( 'Single icon with link', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'icon', 'type' => 'dropdown', 'heading' => __( 'Icon', 'bt_plugin' ), 'value' => bt_fa_icons(), 'preview' => true ),	
				array( 'param_name' => 'icon_type', 'type' => 'dropdown', 'heading' => __( 'Icon Type', 'bt_plugin' ), 'preview' => true,
						'value' => array(
								__( 'Default', 'bt_plugin' ) 	=> 'default',
								__( 'Accent', 'bt_plugin' )	 	=> 'accent',
								__( 'Borderless', 'bt_plugin' ) => 'borderless',
								__( 'White', 'bt_plugin' ) 		=> 'white'
						) ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_icons', array( 'name' => __( 'Icons', 'bt_plugin' ), 'description' => __( 'Icon container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_icon' => true ), 'toggle' => true, 'show_settings_on_create' => false,
			'params' => array( 
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);

		bt_rc_map( 'bt_latest_posts', array( 'name' => __( 'Latest Posts', 'bt_plugin' ), 'description' => __( 'Recent posts', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => __( 'Number of Items', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'category', 'type' => 'textfield', 'heading' => __( 'Category Slug', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'format', 'type' => 'dropdown', 'heading' => __( 'Format', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Horizontal', 'bt_plugin' ) => 'horizontal',
						__( 'Vertical', 'bt_plugin' ) => 'vertical'
				) ),
				array( 'param_name' => 'post_type', 'type' => 'dropdown', 'heading' => __( 'Post Type', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Blog', 'bt_plugin' ) => 'blog',
						__( 'Portfolio', 'bt_plugin' ) => 'portfolio'
				) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);	

		bt_rc_map( 'bt_grid', array( 'name' => __( 'Grid', 'bt_plugin' ), 'description' => __( 'Grid with recent posts', 'bt_plugin' ),
			'params' => array(
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => __( 'Number of items', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'columns', 'type' => 'dropdown', 'heading' => __( 'Columns', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( '3', 'bt_plugin' ) => '3',
						__( '4', 'bt_plugin' ) => '4',
						__( '5', 'bt_plugin' ) => '5',
						__( '6', 'bt_plugin' ) => '6'
				) ),
				array( 'param_name' => 'category', 'type' => 'textfield', 'heading' => __( 'Category Slug', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'category_filter', 'type' => 'dropdown', 'heading' => __( 'Category Filter', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no',
						__( 'Yes', 'bt_plugin' ) => 'yes'
				) ),
				array( 'param_name' => 'grid_type', 'type' => 'dropdown', 'heading' => __( 'Grid Type', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Classic', 'bt_plugin' ) => 'classic',
						__( 'Tiled', 'bt_plugin' ) => 'tiled'
				) ),
				array( 'param_name' => 'format', 'type' => 'textfield', 'heading' => __( 'Tiled Format', 'bt_plugin' ) ),				
				array( 'param_name' => 'tiles_title', 'type' => 'dropdown', 'heading' => __( 'Always Show Content in Tiled Grid', 'bt_plugin' ),
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no',
						__( 'Yes', 'bt_plugin' ) => 'yes'
				) ),				
				array( 'param_name' => 'post_type', 'type' => 'dropdown', 'heading' => __( 'Post Type', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'Blog', 'bt_plugin' ) => 'blog',
						__( 'Portfolio', 'bt_plugin' ) => 'portfolio'
				) ),
				array( 'param_name' => 'scroll_loading', 'type' => 'dropdown', 'heading' => __( 'Scroll Loading', 'bt_plugin' ), 'preview' => true,
					'value' => array(
						__( 'No', 'bt_plugin' ) => 'no',
						__( 'Yes', 'bt_plugin' ) => 'yes'
				) ),			
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		$time_array = array();
		$time_array[ '' ] = '';
		for ( $i = 0; $i <= 23; $i++ ) {
			if ( $i < 10 ) $i = '0' . $i;
			$time_array[ $i . ':00' ] =  $i . ':00';
		}		
		bt_rc_map( 'bt_quote_booking', array( 'name' => __( 'Quote and Booking', 'bt_plugin' ), 'description' => __( 'Quote and booking container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_quote_item' => true, 'bt_quote_multiply' => true, 'bt_hr' => true, 'bt_header' => true, 'bt_text' => true ), 'toggle' => true,
			'params' => array( 
				array( 'param_name' => 'admin_email', 'type' => 'textfield', 'heading' => __( 'Admin Email', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'subject', 'type' => 'textfield', 'heading' => __( 'Email Subject', 'bt_plugin' ) ),
				array( 'param_name' => 'time_start', 'type' => 'dropdown', 'heading' => __( 'Preferred Time Start', 'bt_plugin' ),
					'value' => $time_array
				),
				array( 'param_name' => 'time_end', 'type' => 'dropdown', 'heading' => __( 'Preferred Time End', 'bt_plugin' ),
					'value' => $time_array
				),
				array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => __( 'Currency', 'bt_plugin' ) ),
				array( 'param_name' => 'm_name', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Name', 'bt_plugin' ) ),
				array( 'param_name' => 'm_email', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Email', 'bt_plugin' ) ),
				array( 'param_name' => 'm_phone', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Phone', 'bt_plugin' ) ),
				array( 'param_name' => 'm_address', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Address', 'bt_plugin' ) ),
				array( 'param_name' => 'm_date', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Preferred Date', 'bt_plugin' ) ),
				array( 'param_name' => 'm_time', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Preferred Time', 'bt_plugin' ) ),
				array( 'param_name' => 'm_message', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Message', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_quote_item', array( 'name' => __( 'Quote Item', 'bt_plugin' ), 'description' => __( 'Single quote element', 'bt_plugin' ),
			'params' => array( 
				array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'type', 'type' => 'dropdown', 'heading' => __( 'Input Type', 'bt_plugin' ),
					'value' => array(
						__( 'Text', 'bt_plugin' ) => 'text',
						__( 'Select', 'bt_plugin' ) => 'select',
						__( 'Slider', 'bt_plugin' ) => 'slider'
				) ),
				array( 'param_name' => 'value', 'type' => 'textarea', 'heading' => __( 'Value (unit_price for Text, name;price separated by new line for Select, min;max;step;unit_price;offset_price for Slider)', 'bt_plugin' ) )
			) )
		);
		
		bt_rc_map( 'bt_quote_multiply', array( 'name' => __( 'Quote Multiply', 'bt_plugin' ), 'description' => __( 'Quote multiply container', 'bt_plugin' ), 'container' => 'vertical', 'accept' => array( 'bt_quote_item' => true ), 'show_settings_on_create' => false,
			'params' => array( 
			) )
		);
		
		bt_rc_map( 'bt_price_list', array( 'name' => __( 'Price List', 'bt_plugin' ), 'description' => __( 'Price List element', 'bt_plugin' ),
			'params' => array( 
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => __( 'Title', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'sticker', 'type' => 'textfield', 'heading' => __( 'Sticker Text', 'bt_plugin' ) ),
				array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => __( 'Currency', 'bt_plugin' ) ),
				array( 'param_name' => 'price', 'type' => 'textfield', 'heading' => __( 'Price', 'bt_plugin' ), 'preview' => true ),
				array( 'param_name' => 'items', 'type' => 'textarea', 'heading' => __( 'Items', 'bt_plugin' ) ),
				array( 'param_name' => 'button_text', 'type' => 'textfield', 'heading' => __( 'Button Text', 'bt_plugin' ) ),
				array( 'param_name' => 'button_link', 'type' => 'textfield', 'heading' => __( 'Button Link', 'bt_plugin' ) ),
				array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ) ),
				array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ) )
			) )
		);		

	}
}
add_action( 'plugins_loaded', 'bt_map_sc' );


// WIDGETS

if ( ! class_exists( 'BT_Our_Office' ) ) {

	// OUR OFFICE

	class BT_Our_Office extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_our_office', // Base ID
				__( 'BT Our Office', 'bt_plugin' ), // Name
				array( 'description' => __( 'About Our Office.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			$contact_address = bt_get_option( 'contact_address' );
			$contact_phone = bt_get_option( 'contact_phone' );
			$contact_email = bt_get_option( 'contact_email' );
			$work_time = bt_get_option( 'work_time' );
			$contact_page_slug = bt_get_option( 'contact_page_slug' );
			
			if ( $contact_page_slug != '' ) {
				$contact_page = get_posts(
					array(
						'name'      => $contact_page_slug,
						'post_type' => 'page'
					)
				);
				if ( is_array( $contact_page ) ) {
					$address_html = '<div class="ico office"><a href="' . esc_url_raw( get_permalink( $contact_page[0]->ID ) ) . '" data-ico-bold="&#xe637;"><span>' . $contact_address . '</span></a></div>';
					$work_time_html = '<div class="ico office"><a href="' . esc_url_raw( get_permalink( $contact_page[0]->ID ) ) . '" data-ico-bold="&#xe637;"><span>' . $work_time . '</span></a></div>';
				} else {
					$address_html = '<div class="ico office"><a href="#" data-ico-bold="&#xe637;"><span>' . $contact_address . '</span></a></div>';
					$work_time_html = '<div class="ico office"><a href="#" data-ico-bold="&#xe637;"><span>' . $work_time . '</span></a></div>';
				}
			} else {
				$address_html = '<div class="ico office"><a href="#" data-ico-bold="&#xe637;"><span>' . $contact_address . '</span></a></div>';
				$work_time_html = '<div class="ico office"><a href="#" data-ico-bold="&#xe637;"><span>' . $work_time . '</span></a></div>';
			}
			
			$output = ''; 
			if ( $contact_address != '' ) $output .= $address_html;
			if ( $contact_phone != '' ) $output .= '<div class="ico office"><a href="' . esc_url_raw( 'tel:' . $contact_phone ) . '" data-ico-bold="&#xe60f;"><span>' . $contact_phone . '</span></a></div>';
			if ( $contact_email != '' ) $output .= '<div class="ico office"><a href="' . esc_url_raw( 'mailto:' . $contact_email ) . '" data-ico-bold="&#xe616;"><span>' . $contact_email . '</span></a></div>';
			if ( $work_time != '' ) $output .= $work_time_html;
			
			echo $output;
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'About', 'bt_plugin' );
			?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

			return $instance;
		}
	}	
}

if ( ! class_exists( 'BT_Gallery' ) ) {

	// GALLERY

	class BT_Gallery extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_gallery', // Base ID
				__( 'BT Gallery', 'bt_plugin' ), // Name
				array( 'description' => __( 'Gallery widget.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			if ( $instance['ids'] != '' ) {
				echo do_shortcode( '[gallery ids="' . $instance['ids'] . '"]' );
			}
			
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Gallery', 'bt_plugin' );
			$ids = ! empty( $instance['ids'] ) ? $instance['ids'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>"><?php _e( 'List of image IDs (comma-separated):', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ids' ) ); ?>" type="text" value="<?php echo esc_attr( $ids ); ?>">
			</p>			
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['ids'] = ( ! empty( $new_instance['ids'] ) ) ? strip_tags( $new_instance['ids'] ) : '';

			return $instance;
		}
	}	
}

if ( ! class_exists( 'BT_Text_Image' ) ) {

	// TEXT IMAGE

	class BT_Text_Image extends WP_Widget {

		function __construct() {
			parent::__construct(
					'sp_image', // Base ID
					__( 'BT Text Image', 'bt_plugin' ), // Name
					array( 'description' => __( 'Text with image.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
				
			if ( $instance['ids'] != '' ) {
				echo do_shortcode( '[image ids="' . $instance['ids'] . '"]' );
			}
			echo '<div class="widget_sp_image-description"><p>' . $instance['text'] . '</p></div>';
			
			echo $args['after_widget'];
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$ids = ! empty( $instance['ids'] ) ? $instance['ids'] : '';
			$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>"><?php _e( 'Image IDs:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ids' ) ); ?>" type="text" value="<?php echo esc_attr( $ids ); ?>">
			</p>			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'bt_plugin' ); ?></label> 
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" > <?php echo esc_attr( $text ); ?></textarea>
			</p>			
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['ids'] = ( ! empty( $new_instance['ids'] ) ) ? strip_tags( $new_instance['ids'] ) : '';
			$instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';

			return $instance;
		}
	}	
}

if ( ! class_exists( 'BT_Recent_Posts' ) ) {
	
	// RECENT POSTS	
	
	class BT_Recent_Posts extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_recent_posts', // Base ID
				__( 'BT Recent Posts', 'bt_plugin' ), // Name
				array( 'description' => __( 'Recent posts with thumbnails.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			global $date_format;
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}

			$number = intval( trim( $instance['number'] ) );
			if ( $number < 1 ) {
				$number = 5;
			} else if ( $number > 30 ) {
				$number = 30;
			}
			
			echo '<div class="popularPosts"><ul>';
			
			$recent_posts = wp_get_recent_posts( array( 'numberposts' => $number, 'post_status' => 'publish' ) );
			foreach ( $recent_posts as $recent ) {
				$link = get_permalink( $recent['ID'] );
				$user_data = get_userdata( $recent['post_author'] );
				$user_url = $user_data->data->user_url;
				
				$post_format = get_post_format( $recent['ID'] );
				$images = bt_rwmb_meta( BTPFX . '_images', 'type=image', $recent['ID'] );
				if ( $images == null ) $images = array();
				
				$img = get_the_post_thumbnail( $recent['ID'], 'thumbnail' );
				
				if ( $post_format == 'image' && $img == '' ) {
					foreach ( $images as $img ) {
						$src = $img['full_url'];
						$img = '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( basename( $src ) ) . '">';
						break;
					}
				}					

				echo '<li><div class="ppImage"><a href="' . esc_url( $link ) . '">' . $img . '</a></div><div class="ppTxt"><h5><a href="' . esc_url( $link ) . '">' . esc_html( $recent['post_title'] ) . '</a></h5><p class="posted">' . date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d', $recent['ID'] ) ) ) . '</div></li>';
			}
			
			echo '</ul></div>';
				
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'bt_plugin' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '5';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';

			return $instance;
		}
	}
}


if ( ! class_exists( 'BT_Recent_Comments' ) ) {
	
	// RECENT COMMENTS	
	
	class BT_Recent_Comments extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_recent_comments', // Base ID
				__( 'BT Recent Comments', 'bt_plugin' ), // Name
				array( 'description' => __( 'Recent comments with avatars.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			global $date_format;
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}			

			$number = intval( trim( $instance['number'] ) );
			if ( $number < 1 ) {
				$number = 5;
			} else if ( $number > 30 ) {
				$number = 30;
			}
			
			echo '<div class="latestComments"><ul>';
			
			$comments_query = new WP_Comment_Query;
			$recent_comments = $comments_query->query( array( 'number' => $number, 'status' => 'approve' ) );
			if ( $recent_comments ) {
				foreach ( $recent_comments as $recent ) {
					echo '<li><h5><a href="' . esc_url( get_permalink( $recent->comment_post_ID ) ) . '">' . esc_html( get_the_title( $recent->comment_post_ID ) ) . '</a></h5><p class="posted">' . date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d', $recent->comment_date ) ) ) . ' &mdash; ' . __( 'by', 'bt_plugin' ) . ' <a href="' . esc_url( $recent->comment_author_url ) . '">' . $recent->comment_author . '</a></p></li>';
				}
			}

			echo '</div></ul>';
				
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Comments', 'bt_plugin' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '5';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of comments:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';

			return $instance;
		}
	}
}

if ( ! class_exists( 'BT_Instagram' ) ) {
	
	// INSTAGRAM	
	
	class BT_Instagram extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_instagram', // Base ID
				__( 'BT Instagram', 'bt_plugin' ), // Name
				array( 'description' => __( 'Instagram photos.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {

			wp_enqueue_script( 'bt_instagram', plugin_dir_url( __FILE__ ) . 'instafeed.min.js', array(), '', true );
			
			$number = intval( trim( $instance['number'] ) );
			if ( $number < 1 ) {
				$number = 4;
			} else if ( $number > 30 ) {
				$number = 30;
			}			
			
			$this->number = $number;
			$this->user_id = trim( $instance['user_id'] );
			$this->client_id = trim( $instance['client_id'] );
			$this->access_token = trim( $instance['access_token'] );
			
			if ( $this->number == '' || $this->user_id == '' || $this->client_id == '' || $this->access_token == '' ) {
				return;
			}

			add_action( 'wp_footer', array( $this, 'init_feed' ) );
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			echo '<div id="instafeed" class="instaGrid"></div>';
				
			echo $args['after_widget'];
		}
		
		public function init_feed() {
			echo '<script type="text/javascript">
				jQuery( document ).ready(function() {
					var feed = new Instafeed({
						get: "user",
						limit: ' . esc_js( $this->number ) . ',
						template: \'<span><a href="{{link}}"><img src="{{image}}" /></a></span>\',
						userId: ' . esc_js( $this->user_id ) . ',
						clientId: "' . esc_js( $this->client_id ) . '",
						accessToken: "' . esc_js( $this->access_token ) . '"
					});
					feed.run();
				});
			</script>';		
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Instagram', 'bt_plugin' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '4';
			$user_id = ! empty( $instance['user_id'] ) ? $instance['user_id'] : '';
			$client_id = ! empty( $instance['client_id'] ) ? $instance['client_id'] : '';
			$access_token = ! empty( $instance['access_token'] ) ? $instance['access_token'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of photos:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>"><?php _e( 'User ID:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_id' ) ); ?>" type="text" value="<?php echo esc_attr( $user_id ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'client_id' ) ); ?>"><?php _e( 'Client ID:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'client_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'client_id' ) ); ?>" type="text" value="<?php echo esc_attr( $client_id ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php _e( 'Access token:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token ); ?>">			
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
			$instance['user_id'] = ( ! empty( $new_instance['user_id'] ) ) ? strip_tags( $new_instance['user_id'] ) : '';
			$instance['client_id'] = ( ! empty( $new_instance['client_id'] ) ) ? strip_tags( $new_instance['client_id'] ) : '';
			$instance['access_token'] = ( ! empty( $new_instance['access_token'] ) ) ? strip_tags( $new_instance['access_token'] ) : '';

			return $instance;
		}
	}
}

if ( ! class_exists( 'BT_Twitter' ) ) {
	
	// TWITTER	
	
	class BT_Twitter extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_twitter', // Base ID
				__( 'BT Twitter', 'bt_plugin' ), // Name
				array( 'description' => __( 'Twitter feed.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
			
			$number = intval( trim( $instance['number'] ) );
			if ( $number < 1 ) {
				$number = 5;
			} else if ( $number > 30 ) {
				$number = 30;
			}

			$cache = intval( trim( $instance['cache'] ) );
			if ( $cache == 0 || $cache < 0 ) {
				$cache = 0;
			} else if ( $cache > 720 ) {
				$cache = 720;
			}		

			$this->number = $number;
			$this->cache = $cache;
			$this->username = trim( $instance['username'] );
			$this->consumer_key = trim( $instance['consumer_key'] );
			$this->consumer_secret = trim( $instance['consumer_secret'] );
			$this->access_token = trim( $instance['access_token'] );
			$this->access_token_secret = trim( $instance['access_token_secret'] );
			
			if ( $this->number == '' || $this->username == '' || $this->consumer_key == '' || $this->consumer_secret == '' || $this->access_token == '' || $this->access_token_secret == '' ) {
				return;
			}			
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			$trans_name = 'bt_tweets';

			if ( $cache == 0 ) {
				delete_transient( $trans_name );
			}

			if ( false == ( $twitter_data = unserialize( base64_decode( get_transient( $trans_name ) ) ) ) ) {
				require_once( 'twitteroauth.php' );
				$twitter_connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret );

				$twitter_data = $twitter_connection->get(
					'statuses/user_timeline',
					array(
						'screen_name'     => $this->username,
						'count'           => $this->number,
						'exclude_replies' => false
					)
				);

				if ( $twitter_connection->http_code != 200 ) {
					$twitter_data = unserialize( base64_decode( get_transient( $trans_name ) ) );
				}

				set_transient( $trans_name, base64_encode( serialize( $twitter_data ) ), 60 * $cache );
			}
			
			echo '<ul class="recentTweets">';

			foreach ( $twitter_data as $data ) {
				$link = 'https://twitter.com/' . $this->username . '/status/' . $data->id_str;
				
				$text = mb_convert_encoding( utf8_encode( $data->text ), 'HTML-ENTITIES', 'UTF-8' );

				$time = human_time_diff( strtotime( $data->created_at ) );

				echo '<li><p class="posted"><a href="' . esc_url( $link ) . '">@' . $this->username . ' - ' . $time . '</a></p>';
				echo '<p>' . $this->parse( $data->text ) . '</p></li>';
			}
			
			echo '</ul>';
				
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Twitter', 'bt_plugin' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '5';
			$cache = ! empty( $instance['cache'] ) ? $instance['cache'] : '0';
			$username = ! empty( $instance['username'] ) ? $instance['username'] : '';
			$consumer_key = ! empty( $instance['consumer_key'] ) ? $instance['consumer_key'] : '';
			$consumer_secret = ! empty( $instance['consumer_secret'] ) ? $instance['consumer_secret'] : '';
			$access_token = ! empty( $instance['access_token'] ) ? $instance['access_token'] : '';
			$access_token_secret = ! empty( $instance['access_token_secret'] ) ? $instance['access_token_secret'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of tweets:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e( 'Username:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>"><?php _e( 'Cache (minutes):', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>" type="text" value="<?php echo esc_attr( $cache ); ?>">			
			</p>			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'consumer_key' ) ); ?>"><?php _e( 'Consumer key:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'consumer_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumer_key' ) ); ?>" type="text" value="<?php echo esc_attr( $consumer_key ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'consumer_secret' ) ); ?>"><?php _e( 'Consumer secret:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'consumer_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumer_secret' ) ); ?>" type="text" value="<?php echo esc_attr( $consumer_secret ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php _e( 'Access token:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>"><?php _e( 'Access token secret:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token_secret' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token_secret ); ?>">			
			</p>			
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
			$instance['username'] = ( ! empty( $new_instance['username'] ) ) ? strip_tags( $new_instance['username'] ) : '';
			$instance['cache'] = ( ! empty( $new_instance['cache'] ) ) ? strip_tags( $new_instance['cache'] ) : '';
			$instance['consumer_key'] = ( ! empty( $new_instance['consumer_key'] ) ) ? strip_tags( $new_instance['consumer_key'] ) : '';
			$instance['consumer_secret'] = ( ! empty( $new_instance['consumer_secret'] ) ) ? strip_tags( $new_instance['consumer_secret'] ) : '';
			$instance['access_token'] = ( ! empty( $new_instance['access_token'] ) ) ? strip_tags( $new_instance['access_token'] ) : '';
			$instance['access_token_secret'] = ( ! empty( $new_instance['access_token_secret'] ) ) ? strip_tags( $new_instance['access_token_secret'] ) : '';

			return $instance;
		}
		
		private function parse( $text ) {
			$text = preg_replace( '/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i', '<a href="$1" class="twitter-link">$1</a>', $text );
			$text = preg_replace( '/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i', '<a href="http://$1" class="twitter-link">$1</a>', $text );

			$text = preg_replace( '/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i', '<a href="mailto://$1" class="twitter-link">$1</a>', $text );

			$text = preg_replace( '/([\.|\,|\:|\|\|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', '$1<a href="https://twitter.com/hashtag/$2" class="twitter-link">#$2</a>$3 ', $text );
			
			$text = preg_replace( '/([\.|\,|\:|\|\|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', '$1<a href="https://twitter.com/$2" class="twitter-user">@$2</a>$3 ', $text );			
			
			return $text;
		}
	}
}

if ( ! function_exists( 'register_bt_widgets' ) ) {
	function register_bt_widgets() {
		register_widget( 'BT_Our_Office' );
		register_widget( 'BT_Gallery' );
		register_widget( 'BT_Text_Image' );
		register_widget( 'BT_Recent_Posts' );
		register_widget( 'BT_Recent_Comments' );
		register_widget( 'BT_Instagram' );
		register_widget( 'BT_Twitter' );
	}
}
add_action( 'widgets_init', 'register_bt_widgets' );

// portfolio
if ( ! function_exists( 'bt_create_portfolio' ) ) {
	function bt_create_portfolio() {
		register_post_type( 'portfolio',
			array(
				'labels' => array(
					'name'          => __( 'Portfolio', 'bt_plugin' ),
					'singular_name' => __( 'Portfolio Item', 'bt_plugin' )
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => array( 'title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt' ),
				'rewrite'       => array( 'with_front' => false, 'slug' => 'portfolio' )
			)
		);
		register_taxonomy( 'portfolio_category', 'portfolio', array( 'hierarchical' => true, 'label' => __( 'Portfolio Categories', 'bt_plugin' ) ) );
	}
}
add_action( 'init', 'bt_create_portfolio' );

if ( ! function_exists( 'bt_rewrite_flush' ) ) {
	function bt_rewrite_flush() {
		// First, we "add" the custom post type via the above written function.
		// Note: "add" is written with quotes, as CPTs don't get added to the DB,
		// They are only referenced in the post_type column with a post entry, 
		// when you add a post of this CPT.
		bt_create_portfolio();

		// ATTENTION: This is *only* done during plugin activation hook in this example!
		// You should *NEVER EVER* do this on every page load!!
		flush_rewrite_rules();
	}
}
register_activation_hook( __FILE__, 'bt_rewrite_flush' );

// notifications
class BT_Notifications {
	
	private $message;
	private $published;
	
	public function __construct() {
		add_action( 'admin_init', array( $this, 'bt_notifications' ) );
		add_action( 'admin_footer', array( $this, 'bt_notifications_js' ) );
		add_action( 'wp_ajax_bt_notifications_ajax', array( $this, 'bt_notifications_ajax_callback' ) );
	}
	
	public function bt_notifications_ajax_callback() {
		update_option( 'boldthemes_notification', $this->published );
		die();
	}
	
	public function bt_admin_notice() {
		echo '<div class="notice updated is-dismissible boldthemes_notice">';
			echo '<p>'. $this->message .'</p>';
		echo '</div>';
	}
	
	public function bt_notifications_js() { ?>
		<script>
			jQuery( document ).on( 'click', '.boldthemes_notice .notice-dismiss', function() {
				jQuery.ajax({
					url: ajaxurl,
					data: {
						action: 'bt_notifications_ajax'
					}
				});
			});
		</script>
	<?php }

	public function bt_notifications() {
		$show = false;
		$response = wp_remote_get( 'http://bold-themes.com/feed/atom/?post_type=notification' );
		
		if ( is_array( $response ) ) {
			$body = $response['body'];

			$xml = @simplexml_load_string( $body );

			if ( $xml && is_object( $xml ) && is_object( $xml->entry[0] ) ) {
				$this->message = $xml->entry[0]->content;
				$this->published = (string)$xml->entry[0]->published;

				$last = get_option( 'boldthemes_notification' );
				if ( $this->message != '' && $last != $this->published && $last ) {
					$show = true;
				}
				if ( ! $last ) {
					update_option( 'boldthemes_notification', $this->published );
				}
			}

		}
		
		if ( $show ) {
			add_action( 'admin_notices', array( $this, 'bt_admin_notice' ) );
		}
		
	}
	
}

$n = new BT_Notifications();