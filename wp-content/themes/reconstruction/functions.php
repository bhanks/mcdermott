<?php

if ( ! class_exists( 'BoldThemesTheme' ) ) {
	
	class BoldThemesTheme {
	
		/**
	     * Constructor
	     */
		function __construct() {
		
			// Register action/filter callbacks
			
			add_action( 'after_setup_theme', array( $this, 'init' ) );
			add_action( 'wp_head', array( $this, 'bt_set_global_uri' ) );
			add_action( 'widgets_init', array( $this, 'bt_widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'bt_enqueue_scripts_styles' ) );
			add_action( 'wp_footer', array( $this, 'bt_buggyfill_function' ), 20 );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'bt_media_script' ) );
			add_action( 'wp_print_scripts', array( $this, 'bt_de_script' ), 100 );
			add_action( 'wp_print_styles', array( $this, 'bt_load_fonts' ) );
			add_action( 'admin_head', array( $this, 'bt_admin_style' ) );
			add_action( 'customize_controls_print_styles', array( $this, 'bt_admin_customize_style' ) );
			add_action( 'tgmpa_register', array( $this, 'bt_theme_register_required_plugins' ) );
			
			add_filter( 'get_search_form', array( $this, 'bt_search_form' ) );
			add_filter( 'the_content_more_link', array( $this, 'bt_remove_more_link_scroll' ) );
			add_filter( 'wp_list_categories', array( $this, 'bt_cat_count_span' ) );
			add_filter( 'get_archives_link', array( $this, 'bt_arch_count_span' ) );
			add_filter( 'style_loader_tag', array( $this, 'bt_style_loader_tag_function' ) );
			add_filter( 'script_loader_tag', array( $this, 'bt_script_loader_tag_function' ) );
			add_filter( 'wp_nav_menu_items', array( $this, 'bt_remove_menu_item_whitespace' ) );
			add_filter( 'wp_video_shortcode', array( $this, 'bt_wp_video_shortcode' ), 10, 5 );
			add_filter( 'wp_video_shortcode_library', array( $this, 'bt_wp_video_shortcode_library' ) );
			add_filter( 'wp_audio_shortcode_library', array( $this, 'bt_wp_audio_shortcode_library' ) );
			add_filter( 'wp_title', array( $this, 'bt_title' ) );
		}
		
		/**
	     * Theme setup
	     */
		function init() {
		
			// add theme support
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'post-thumbnails', array( 'post', 'page', 'portfolio' ) );
			add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
			add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio', 'link', 'quote' ) );
			
			// register navigation menus
			register_nav_menus( array (
				'primary' => __( 'Primary Menu', 'bt_theme' ),
				'footer'  => __( 'Footer Menu', 'bt_theme' )
			));
			
			// load translated strings
			load_theme_textdomain( 'bt_theme', get_template_directory() . '/languages' );
			
			// date format
			global $date_format;
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'bt_theme', 'Date Format', get_option( 'date_format' ) );
				$date_format = icl_t( 'bt_theme', 'Date Format', get_option( 'date_format' ) );
			} else {
				$date_format = get_option( 'date_format' );
			}

			// image sizes
			update_option( 'thumbnail_size_w', 160 );
			update_option( 'thumbnail_size_h', 160 );
			update_option( 'medium_size_w', 320 );
			update_option( 'medium_size_h', 0 );
			update_option( 'large_size_w', 1200 );
			update_option( 'large_size_h', 0 );

			add_image_size( 'grid', 540 );

			add_image_size( 'grid_11', 540, 540, true );
			add_image_size( 'grid_22', 1080, 1080, true );
			add_image_size( 'grid_21', 1080, 540, true );
			add_image_size( 'grid_12', 540, 1080, true );

			add_image_size( 'latest_posts', 320, 240, true );			
			
		}
		
		// callbacks
		
		/**
		 * Set JS AJAX URL and JS text labels
		 */
		function bt_set_global_uri() {
			echo '<script>';
			echo 'window.BTURI = "' . esc_js( get_template_directory_uri() ) . '"; window.BTAJAXURL = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";';
			echo 'window.bt_text = [];';
			echo 'window.bt_text.previous = \'' . __( 'previous', 'bt_theme' ) . '\';';
			echo 'window.bt_text.next = \'' . __( 'next', 'bt_theme' ) . '\';';		
			echo '</script>';
		}
		
		/**
		 * Remove Recent Comments widget style and register sidebar and widget areas
		 */
		function bt_widgets_init() {  
			global $wp_widget_factory;  
			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
			
			register_sidebar( array (
				'name' 			=> __( 'Sidebar', 'bt_theme' ),
				'id' 			=> 'primary_widget_area',
				'description' 	=> '',
				'before_widget' => '<div class="btBox %2$s">',
				'after_widget' 	=> '</div>',
				'before_title' 	=> '<h4><span>',
				'after_title' 	=> '</span></h4>',
			));
			
			register_sidebar( array (
				'name' 			=> __( 'Footer Widgets', 'bt_theme' ),
				'id' 			=> 'footer_widgets',
				'description' 	=> '',
				'before_widget' => '<div class="btBox %2$s">',
				'after_widget' 	=> '</div>',
				'before_title' 	=> '<h4><span>',
				'after_title' 	=> '</span></h4>',
			));
		}
		
		/**
		 * Enqueue scripts and styles
		 */
		function bt_enqueue_scripts_styles() {
			if ( function_exists( 'csscrush_file' ) ) {
				csscrush_file( get_stylesheet_directory() . '/style.css', array( 'source_map' => true ) );
			}
			wp_enqueue_style( 'bt_style_css', get_template_directory_uri() . '/style.crush.css', array(), false );
			wp_enqueue_style( 'bt_buggyfill_css', get_template_directory_uri() . '/css/viewport-buggyfill.css', array(), false );
			
			wp_enqueue_script( 'bt_modernizr_js', get_template_directory_uri() . '/js/modernizr.custom.js', array( 'jquery' ), '', false );
			
			wp_enqueue_script( 'bt_buggyfill_js', get_template_directory_uri() . '/js/viewport-units-buggyfill.js', array( 'jquery' ), '', false );
			wp_enqueue_script( 'bt_buggyfill_hacks_js', get_template_directory_uri() . '/js/viewport-units-buggyfill.hacks.js', array( 'jquery' ), '', false );
			 
			wp_enqueue_script( 'bt_magnific_popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '', false );
			wp_enqueue_script( 'bt_slick_js', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '', false );
			wp_enqueue_script( 'bt_select_js', get_template_directory_uri() . '/js/fancySelect.js', array( 'jquery' ), '', false );
			wp_enqueue_script( 'bt_misc_js', get_template_directory_uri() . '/js/misc.js', array( 'jquery' ), '', false ); 
			wp_enqueue_script( 'bt_sliders_js', get_template_directory_uri() . '/js/sliders.js', array( 'jquery' ), '', false );
			
			wp_enqueue_script( 'bt_ie9_js1', 'http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.min.js', array(), false );
			wp_enqueue_script( 'bt_ie9_js2', 'http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js', array(), false );
			
			//custom accent color and font style

			$color = bt_get_option( 'accent_color' );
			$body_font = urldecode( bt_get_option( 'body_font' ) );
			$heading_font = urldecode( bt_get_option( 'heading_font' ) );

			$custom_css = '';
			
			if ( $color != '' ) {
				$custom_css .= "
					a {
						color: {$color};
					}

					h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {
						color: {$color};
					}

					h3 {
						color: {$color};
					}

					div.closeGhost a:hover {
						color: {$color};
					}

					.ssPort input[type='text']:focus, .ssPort input[type='text'].untouched:focus, .ssPort input[type='text'].touched {
						color: {$color} !important;
					}

					.sideSearchPort button:hover, .onSideSearch button:hover {
						background-color: {$color};
					}

					.boldTags a:hover, .widget_tag_cloud a:hover {
						background-color: {$color};
					}
					
					#wp-calendar th {
						background-color: {$color};
					}					

					#wp-calendar a:hover {
						color: {$color};
					}

					.btBox.widget_pages a:hover {
						color: {$color};
					}

					.btBox.widget_pages a:before {
						color: {$color};
					}

					.widget_rss ul li a.rsswidget {
						color: {$color};
					}

					li.recentcomments a {
						color: {$color};
					}

					li.recentcomments a:first-child:hover {
						color: {$color};
					}

					.widget_recent_entries ul li a:hover {
						color: {$color};
					}

					.widget_bt_recent_comments h5 a:hover {
						color: {$color};
					}

					.btBox .recentTweets li p.posted {
						color: {$color};
					}

					.btBox .recentTweets li a {
						color: {$color};
					}

					.breadCrumbs ul li a:hover {
						color: {$color};
					}

					p.comment-notes:before {
						color: {$color};
					}

					input[type='submit'] {
						color: {$color};
					}

					input[type='submit']:hover {
						background-color: {$color};
					}

					.aaAvatar:before {
						background-color: {$color};
					}

					.commentsBox h4:after {
						color: {$color};
					}

					.vcard h5 a:hover {
						color: {$color};
					}

					.vcard .posted:before {
						color: {$color};
					}

					.commentTxt p.edit-link, .commentTxt p.reply {
						color: {$color};
					}

					.commentTxt p.edit-link a, .commentTxt p.reply a {
						color: {$color};
					}

					.comment-navigation span {
						color: {$color};
					}

					.comment-navigation a:hover {
						color: {$color};
					}

					.comment-navigation a:before, .comment-navigation a:after {
						color: {$color};
					}

					.boldArticleBody ul li:before, .boldArticleBody ol ul li:before, .boldArticleBody ul ol ul li:before {
						color: {$color};
					}

					.boldArticleBody table caption {
						background-color: {$color};
					}

					span.enhanced.colored {
						color: {$color};
					}

					span.enhanced.ring {
						background-color: {$color};
					}

					.menuHolder .menuPort ul li a:after {
						background-color: {$color};
					}

					.ico.white a:before {
						color: {$color};
					}

					.ico.accent a:before, .iconsToggler {
						background-color: {$color};
						box-shadow: 0 0 0 0 {$color} inset;
					}

					.ico.accent a:hover:before, .iconsToggler:hover, .shown .iconsToggler {
						background-color: {$color};
					}

					.ico.accent a:hover:before, .iconsToggler:hover, .shown .iconsToggler {
						color: {$color};
					}

					.btFooterMenu ul li a:hover {
						color: {$color};
					}

					.widget_categories ul li a:hover, .widget_archive ul li a:hover {
						background-color: {$color};
					}

					p.boldSuperTitle {
						color: {$color};
					}

					.boldBtn.accent a, .boldBtn.accent button {
						background-color: {$color};
					}

					.boldBtn.accent a:hover, .boldBtn.accent button:hover {
						color: {$color};
					}

					.boldBtn.btnAccent a, .boldBtn.btnAccent button {
						background-color: {$color};
					}

					.boldBtn.btnGray a:hover, .boldBtn.btnGray button:hover {
						background-color: {$color};
					}

					.portfolioItem dl.articleMeta dd {
						color: {$color};
					}

					.btProgressContent .btProgressAnim {
						background-color: {$color};
					}

					.btTestimony h4 {
						color: {$color};
					}

					.psCats ul li a:after, .btCatFilter span:after {
						background-color: {$color};
					}

					.psCats ul li a:hover, .psCats ul li a.active, .btCatFilter span.active, .btCatFilter span:hover {
						color: {$color};
					}

					.psBtn a {
						background-color: {$color};
					}

					.tabsHeader li span:before {
						background-color: {$color};
					}

					.tabsVertical .tabAccordionTitle span:before {
						background-color: {$color};
					}

					.tabsVertical .tabAccordionTitle.on:before {
						background-color: {$color};
					}

					.boldArticle.articleListItem header h2 a:hover {
						color: {$color};
					}

					a.boldArticleComments:after {
						color: {$color};
					}

					p.boldContinue a:hover {
						color: {$color};
					}

					.boldPhotoBox blockquote, .wBoldLink {
						background-color: {$color};
					}

					.paging a:hover:after {
						background-color: {$color};
					}

					input.wpcf7-submit {
						color: #fff;
						background-color: {$color};
					}
					.ico a:hover:before {
						box-shadow: 0 0 0 0.9em {$color} inset;
						color: #fff;
					}
					.ico.white a:hover:before {
						box-shadow: 0 0 0 0.9em {$color} inset;
					}
					.ico a:before, .iconsToggler {
						box-shadow: 0 0 0 0 {$color} inset;
					}
					.bottomDash .dash {
						border-bottom: 5px solid {$color};
					}
					.topDash .dash {
						border-top: 5px solid {$color};
					}
					.slick-center .tcItem span {
						box-shadow: 0 0 0 4px {$color} inset;
					}
					.btBox h4 span {
						box-shadow: 0 -4px 0 0 {$color} inset;
					}
					.ui-slider .ui-slider-handle {
						background: {$color};
					}
					.btQuoteTotalCalc {
						color: {$color};
					}
					.btQuoteTotal {
						border-bottom: 4px solid {$color};
					}
					.btPriceTable .ptHeader {
						color: {$color};
					}
					p.ptPrice {
						background-color: {$color};
					}
					.ptSticker span {
						border: 2px solid {$color};
					}
					.btPriceTable .ptHeader h3:before {
						background-color: {$color};
					}
					span.closeSearch {
						color: {$color};
					}
					.ptFooter a:hover {
						background-color: {$color};
					}
					input[type=\"text\"]:focus, input[type=\"email\"]:focus, textarea:focus, .fancy-select .trigger.open {
						box-shadow: 5px 0 0 {$color} inset;
					}
					a.ui-datepicker-prev, a.ui-datepicker-next {
						background-color: {$color};
					}
					.ui-slider .ui-slider-handle.ui-state-active {
						background-color: {$color};
					}

					.portfolioItem .header .socialRow a {
						background-color: {$color};
					}
					
					.bpgPhoto:hover .btShowTitle {
						background-color: {$color};
					}
					
					.boldInfoBarMeta p strong {
						color: {$color};
					}
					
					.fullScreen .boldSubTitle.boldArticleMeta a:before {
						background-color: {$color};
					}
					
					.fullScreen .boldSubTitle.boldArticleMeta a.boldArticleCategory:hover {
						color: {$color};
					}
					
					.fullScreen .boldSubTitle.boldArticleMeta a.boldArticleComments:after {
						color: {$color};
					}
					
					.portfolioItem .header .socialRow a {
						border: 1px solid {$color};
						box-shadow: 0 0 0 0 {$color} inset;
					}
					
					.portfolioItem .header .socialRow a:hover {
						background-color: {$color};
						color: {$color};
					}

					.btContactFieldMandatory.btContactFieldError input, .btContactFieldMandatory.btContactFieldError .trigger {
						border: 1px solid {$color};
					}

					.btSubmitMessage {
						color: {$color};
					}
					
					.slided .slick-dots li button:hover:before, .slided .slick-dots li.slick-active button:before { 
						-webkit-box-shadow: 0 1px 0 2px {$color},0 -1px 0 2px {$color};
						box-shadow: 0 1px 0 2px {$color},0 -1px 0 2px {$color};
					}

					.btSlidePane .bottomDash .dash {
						border-bottom: 5px solid {$color};
					}					
					
					@media all and (max-width: 1200px) {
						.menuTrigger:before {
							color: {$color} !important;
						}
						.menuOn .menuTrigger {
							background-color: #fff !important;
						}
						.menuPort {
							background-color: {$color} !important;
						}
						.subToggler {
							color: {$color} !important;
						}
					}

					.bpgPhoto .bpbItem .btImage {
						background-color: {$color};
					}
				";
			}
			
			if ( $body_font != 'no_change' ) {
				$custom_css .= "
				body { 
					font-family:\"{$body_font}\", arial, tahoma;
				}";
			}
			
			if ( $heading_font != 'no_change' ) {
				$custom_css .= "
				h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
					font-family: \"{$heading_font}\";
				}

				div.closeGhost a {
					font-family: \"{$heading_font}\";
				}

				input[type=\"text\"], input[type=\"email\"], input[type=\"password\"], textarea, .fancy-select .trigger {
					font-family: \"{$heading_font}\";
				}

				.btBox .recentTweets li a {
					font-family: \"{$heading_font}\";
				}

				input[type=\"submit\"] {
					font-family: \"{$heading_font}\";
				}

				.boldArticleBody ul ol li:before {
					font-family: \"{$heading_font}\";
				}

				.boldArticleBody code {
					font-family: \"{$heading_font}\";
				}

				.boldArticleBody address {
					font-family: \"{$heading_font}\";
				}

				.boldArticleBody blockquote {
					font-family: \"{$heading_font}\";
				}

				.menuHolder .menuPort ul li a {
					font-family: \"{$heading_font}\";
				}

				.menuHolder .menuPort ul ul li a {
					font-family: \"{$heading_font}\";
				}

				.ico a {
					font-family: \"{$heading_font}\";
				}

				.btFooterMenu ul li a {
					font-family: \"{$heading_font}\";
				}

				.copyLine {
					font-family: \"{$heading_font}\";
				}

				.brTxt .posted, .ppTxt .posted {
					font-family: \"{$heading_font}\";
				}

				.widget_categories ul li a, .widget_archive ul li a {
					font-family: \"{$heading_font}\";
				}

				.boldSubTitle, .bgiTxt .boldArticleMeta {
					font-family: \"{$heading_font}\";
				}

				p.boldSuperTitle {
					font-family: \"{$heading_font}\";
				}

				.psCats ul li, .btCatFilter span {
					font-family: \"{$heading_font}\";
				}

				.btShowTitle strong {
					font-family: \"{$heading_font}\";
				}

				.btShowTitle span a {
					font-family: \"{$heading_font}\";
				}

				.tabsHeader li span {
					font-family: \"{$heading_font}\";
				}

				p.boldContinue {
					font-family: \"{$heading_font}\";
				}

				.paging a {
					font-family: \"{$heading_font}\";
				}

				p.bgiCat {
					font-family: \"{$heading_font}\";
				}

				.tilesWall.classic p.bgiCat {
					font-family: \"{$heading_font}\";
				}

				.btGridShare a:before {
					font-family: \"{$heading_font}\";
				}";
			}		

			if ( $color != '' || $body_font != 'no_change' || $heading_font != 'no_change' ) {
				wp_add_inline_style( 'bt_style_css', $custom_css );
			}
		}
		
		/**
		 * Buggyfill script
		 */
		function bt_buggyfill_function() {
			echo '<script>window.viewportUnitsBuggyfill.init({

			// milliseconds to delay between updates of viewport-units
			// caused by orientationchange, pageshow, resize events
			refreshDebounceWait: 250,

			// provide hacks plugin to make the contentHack property work correctly.
			hacks: window.viewportUnitsBuggyfillHacks

			});</script>';
		}
		
		/**
		 * Custom media manager script
		 */
		function bt_media_script() {
			wp_enqueue_media();
			wp_enqueue_script( 'bt_media_manager', get_template_directory_uri() . '/js/media_manager.js', array( ), '1.0', true );
		}
		
		/**
		 * Dequeue MetaBox clone script
		 */
		function bt_de_script() {
			wp_dequeue_script( 'rwmb-clone' );
			wp_deregister_script( 'rwmb-clone' );
		}
		
		/**
		 * Loads custom Google Fonts
		 */
		function bt_load_fonts() {
			$body_font = bt_get_option( 'body_font' );
			$heading_font = bt_get_option( 'heading_font' );
			$fonts = $body_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic' . '|' . $heading_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			if ( $body_font == '' ) {
				$fonts = $heading_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			} else if ( $heading_font == '' ) {
				$fonts = $body_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}
			if ( $body_font != 'no_change' || $heading_font != 'no_change' ) {
				wp_register_style( 'bt_fonts', 'http://fonts.googleapis.com/css?family=' . $fonts );
				wp_enqueue_style( 'bt_fonts' );
			}
		}

		/**
		 * MetaBox custom style
		 */
		function bt_admin_style() {
			echo '<style>
				.rwmb-meta-box input[type="text"], .rwmb-meta-box select {
					width:250px;
				}
				.rwmb-meta-box input[type="text"].bt_bttext {
					width:250px;
				}
			</style>';
		}
		
		/**
		 * Customize custom style
		 */
		function bt_admin_customize_style() {
			echo '<style>
				.customize-control-image, .customize-control-text, .customize-control-select, 
				.customize-control-radio, .customize-control-checkbox, .customize-control-color {
					padding-top:5px;
					padding-bottom:5px;
				}
			</style>';
		}
		
		/**
		 * Register the required plugins for this theme
		 */
		function bt_theme_register_required_plugins() {

			$plugins = array(
		 
				array(
					'name'               => 'ReConstruction', // The plugin name.
					'slug'               => 'reconstruction', // The plugin slug (typically the folder name).
					'source'             => get_template_directory() . '/plugins/reconstruction.zip', // The plugin source.
					'required'           => true, // If false, the plugin is only 'recommended' instead of required.
					'version'            => '1.0.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
					'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
					'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
					'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				),
				array(
					'name'               => 'Rapid Composer', // The plugin name.
					'slug'               => 'rapid_composer', // The plugin slug (typically the folder name).
					'source'             => get_template_directory() . '/plugins/rapid_composer.zip', // The plugin source.
					'required'           => true, // If false, the plugin is only 'recommended' instead of required.
					'version'            => '1.0.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
					'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
					'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
					'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				),
				array(
					'name'               => 'BoldThemes WordPress Importer', // The plugin name.
					'slug'               => 'bt' . '_wordpress_importer', // The plugin slug (typically the folder name).
					'source'             => get_template_directory() . '/plugins/' . 'bt' . '_wordpress_importer.zip', // The plugin source.
					'required'           => true, // If false, the plugin is only 'recommended' instead of required.
					'version'            => '1.0.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
					'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
					'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
					'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				),
				array(
					'name'               => 'Contact Form 7', // The plugin name.
					'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
					'required'           => true, // If false, the plugin is only 'recommended' instead of required.
					'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
					'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				),				
				array(
					'name'               => 'Meta Box', // The plugin name.
					'slug'               => 'meta-box', // The plugin slug (typically the folder name).
					'required'           => true, // If false, the plugin is only 'recommended' instead of required.
					'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
					'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				)			

			);
		 
			$config = array(
				'default_path' => '',                      // Default absolute path to pre-packaged plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                   // Automatically activate plugins after installation or not.
				'message'      => '',                      // Message to output right before the plugins table.
				'strings'      => array(
					'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
					'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
					'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
					'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
					'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
					'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
					'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
					'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
					'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
					'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
					'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
					'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
					'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
					'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
					'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
					'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
				)
			);
		 
			tgmpa( $plugins, $config );
		 
		}
		

		/**
		 * Custom search form
		 *
		 * @return string
		 */
		function bt_search_form( $form ) {
			$form = '<div class="onSideSearch"><div class="osSearch" role="search"><form action="' . home_url() . '" method="get"><input type="text" name="s" placeholder="' . esc_attr( __( 'Looking for...', 'bt_theme' ) ) . '" class="untouched"><button type="submit" data-icon="&#xf105;"></button></form></div></div>';
			return $form;
		}

		/**
		 * Removes more link scroll
		 *
		 * @return string
		 */
		function bt_remove_more_link_scroll( $link ) {
			$link = preg_replace( '|#more-[0-9]+|', '', $link );
			return $link;
		}
		
		/**
		 * Category list custom HTML
		 *
		 * @return string
		 */
		function bt_cat_count_span($links) {
			$links = str_replace('</a> (', '</a> <strong>', $links);
			$links = str_replace(')', '</strong>', $links);
			return $links;
		}

		/**
		 * Archive link custom HTML
		 *
		 * @return string 
		 */
		function bt_arch_count_span($links) {
			$links = str_replace('&nbsp;(', ' <strong>', $links);
			$links = str_replace(')', '</strong>', $links);
			return $links;
		}
		
		/**
		 * Style loader tag
		 *
		 * @return string
		 */
		function bt_style_loader_tag_function( $tag ) {
			if ( strpos( $tag, 'bt_buggyfill_css' ) === false ) {
				$tag = substr( $tag, 0, -3 ) . 'data-viewport-units-buggyfill="ignore" />';
			}
			return $tag;
		}

		/**
		 * Script loader tag
		 *
		 * @return string 
		 */
		function bt_script_loader_tag_function( $tag ) {
			if ( strpos( $tag, 'html5shiv' ) !== false || strpos( $tag, 'respond.min' ) !== false ) {
				$tag = '<!--[if lt IE 9]>' . $tag . '<![endif]-->';
			}
			return $tag;
		}
		
		/**
		 * Removes whitespace between tags in menu items
		 */
		function bt_remove_menu_item_whitespace( $items ) {
			return preg_replace( '/>(\s|\n|\r)+</', '><', $items );
		}
		
		/**
		 * Video shortcode custom HTML
		 *
		 * @return string
		 */
		function bt_wp_video_shortcode( $item_html, $atts, $video, $post_id, $library ) {
			$replace_value = 'width: ' . $atts['width'] . 'px';
			$replace_with  = 'width: 100%';
			return str_ireplace( $replace_value, $replace_with, $item_html );
		}

		/**
		 * Enqueue video shortcode custom JS
		 *
		 * @return string 
		 */
		function bt_wp_video_shortcode_library() {
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'bt_video_shortcode', get_template_directory_uri() . '/js/video_shortcode.js', array( 'mediaelement' ), '', true );
			return 'bt_mejs';
		}

		/**
		 * Enqueue audio shortcode custom JS
		 *
		 * @return string 
		 */
		function bt_wp_audio_shortcode_library() {
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'bt_audio_shortcode', get_template_directory_uri() . '/js/audio_shortcode.js', array( 'mediaelement' ), '', true );
			return 'bt_mejs';
		}
		
		/**
		 * Custom wp_title
		 *
		 * @return string
		 */
		function bt_title( $title ) {
			if ( empty( $title ) && ( is_home() || is_front_page() ) ) {
				$title = __( 'Home', 'bt_theme' );
			}
			return trim( $title ) . ' / ' . get_bloginfo( 'name' );
		}
		
	}

	$boldthemestheme = new BoldThemesTheme();

}

// set content width
if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}

// define prefix
if ( ! defined( 'BTPFX' ) ) {
	define( 'BTPFX', 'bt_theme' );
}

if ( file_exists( get_template_directory() . '/css-crush/CssCrush.php' ) ) {
	require_once( 'css-crush/CssCrush.php' );
}
require_once( get_template_directory() . '/config-meta-boxes.php' );
require_once( get_template_directory() . '/php/breadcrumbs.php' );
require_once( get_template_directory() . '/php/customization.php' );
require_once( get_template_directory() . '/editor-buttons/editor-buttons.php' );
require_once( get_template_directory() . '/class-tgm-plugin-activation.php' );
require_once( get_template_directory() . '/php/Mobile_Detect/Mobile_Detect.php' );

/**
 * Pagination output for post archive
 */
if ( ! function_exists( 'bt_pagination' ) ) {
	function bt_pagination() {
	
		$prev = get_previous_posts_link( __( 'Newer Posts', 'bt_theme' ) );
		$next = get_next_posts_link( __( 'Older Posts', 'bt_theme' ) );
		
		$pattern = '/(<a href=".*">)(.*)(<\/a>)/';
		
		echo '<div class="btPagination">';
			if ( $prev != '' ) {
				echo '<div class="paging onLeft">';
					echo '<p class="pagePrev">';
						echo preg_replace( $pattern, '<span class="nbsItem"><span class="nbsTitle">$2</span></span>', $prev );
					echo '</p>';
				echo '</div>';
			}
			if ( $next != '' ) {
				echo '<div class="paging onRight">';
					echo '<p class="pageNext">';
						echo preg_replace( $pattern, '<span class="nbsItem"><span class="nbsTitle">$2</span></span>', $next );
					echo '</p>';
				echo '</div>';
			}
		echo '</div>';
	}
}

/**
 * Custom MetaBox input used for Override Global Settings
 */
if ( ! class_exists( 'RWMB_BTText_Field' ) && class_exists( 'RWMB_Field' ) ) {
	class RWMB_BTText_Field extends RWMB_Field {
	
		static function admin_enqueue_scripts() {			
			wp_enqueue_script( 
				'bt_text',
				get_template_directory_uri() . '/js/bt_text.js',
				array( 'jquery' ),
				'',
				true
			);
		}

		static function html( $meta, $field ) {	
			$meta_key = substr( $meta, 0, strpos( $meta, ':' ) );
			$meta_value = substr( $meta, strpos( $meta, ':' ) + 1 );
			$vars = get_class_vars( 'BT_Customize_Default' );
			$select = '<select class="bt_key_select" style="vertical-align:baseline;height:auto;">';
			$select .= '<option value=""></option>';
			foreach ( $vars as $key => $var ) {
				$selected_html = '';
				if ( BTPFX . '_' . $key == $meta_key ) {
					$selected_html = 'selected="selected"';
				}
				$select .= '<option value="' . esc_attr( BTPFX . '_' . $key ) . '" ' . $selected_html . '>' . esc_html( $key ) . '</option>';
			}
			$select .= '</select>';
			$input = ' <input type="text" class="bt_value" value="' . esc_attr( $meta_value ) . '">';
			return sprintf(
				'<input type="hidden" class="rwmb-text" name="%s" id="%s" value="%s" placeholder="%s" %s>%s',
				$field['field_name'],
				$field['id'],
				$meta,
				$field['placeholder'],
				'',
				self::datalist_html($field)
			) . $select . $input;
		}

		static function normalize_field( $field ) {
			$field = wp_parse_args( $field, array(
				'size'        => 30,
				'datalist'    => false,
				'placeholder' => '',
			) );
			return $field;
		}

		static function datalist_html( $field ) {
			return '';
		}
	}
}

/**
 * Custom MetaBox input used for custom key-value pairs
 */
if ( ! class_exists( 'RWMB_BTText1_Field' ) && class_exists( 'RWMB_Field' ) ) {
	class RWMB_BTText1_Field extends RWMB_Field {
	
		static function admin_enqueue_scripts() {			
			wp_enqueue_script( 
				'bt_text',
				get_template_directory_uri() . '/js/bt_text.js',
				array( 'jquery' ),
				'',
				true
			);
		}

		static function html( $meta, $field ) {
		
			$meta_key = substr( $meta, 0, strpos( $meta, ':' ) );
			$meta_value = substr( $meta, strpos( $meta, ':' ) + 1 );
			
			$vars = get_class_vars( 'BT_Customize_Default' );
			
			$key_input = '<input type="text" class="bt_key" value="' . esc_attr( $meta_key ) . '">';
			
			$input = ' <input type="text" class="bt_value" value="' . esc_attr( $meta_value ) . '">';
			
			return sprintf(
				'<input type="hidden" class="rwmb-text" name="%s" id="%s" value="%s" placeholder="%s" %s>%s',
				$field['field_name'],
				$field['id'],
				$meta,
				$field['placeholder'],
				! $field['datalist'] ?  '' : "list='{$field['datalist']['id']}'",
				self::datalist_html( $field )
			) . $key_input . $input;
		}
		
		static function normalize_field( $field ) {
			$field = wp_parse_args( $field, array(
				'size'        => 30,
				'datalist'    => false,
				'placeholder' => '',
			) );
			return $field;
		}

		static function datalist_html( $field ) {
			if ( ! $field['datalist'] )
				return '';
			$datalist = $field['datalist'];
			$item_html = sprintf(
				'<datalist id="%s">',
				$datalist['id']
			);

			foreach( $datalist['options'] as $option ) {
				$item_html .= sprintf( '<option value="%s"></option>', $option );
			}

			$item_html .= '</datalist>';

			return $item_html;
		}
	}
}

/**
 * Custom comments HTML output
 */
if ( ! function_exists( 'bt_theme_comment' ) ) {
	function bt_theme_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php _e( 'Pingback:', 'bt_theme' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'bt_theme' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
				break;
			default :
			// Proceed with normal comments.
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class = "">
				<?php $avatar_html = get_avatar( $comment, 140 ); 
					if ( $avatar_html != '' ) {
						echo '<div class="commentAvatar">' . $avatar_html . '</div>';
					}
				?>
				<div class="commentTxt">
					<div class="vcard divider">
						<?php
							printf( '<h5 class="author"><span class="fn">%1$s</span></h5>', get_comment_author_link() );
							echo '<p class="posted">' . sprintf( __( '%1$s at %2$s', 'bt_theme' ), get_comment_date(), get_comment_time() ) . '</p>';
						?>
					</div>

					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'bt_theme' ); ?></p>
					<?php endif; ?>

					<div class="comment">
						<?php comment_text();
						if ( comments_open() ) {
							echo '<p class="reply">';
								comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'bt_theme' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
							echo '</p>';
						}
						edit_comment_link( __( 'Edit', 'bt_theme' ), '<p class="edit-link">', '</p>' ); ?>
					</div>
				</div>
			</article>
		<?php
			break;
		endswitch;
	}
}

/**
 * Returns attachment id by url
 *
 * @param string 
 * @return int 
 */
if ( ! function_exists( 'bt_get_attachment_id_from_url' ) ) {
	function bt_get_attachment_id_from_url( $attachment_url = '' ) {
	 
		global $wpdb;
		$attachment_id = false;
	 
		if ( '' == $attachment_url ) {
			return;
		}
	 
		$upload_dir_paths = wp_upload_dir();

		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
 
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
 
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	 
		return $attachment_id;
	}
}

/**
 * Get array of data for a range of posts, used in grid layout
 * @param int $number
 * @param int $offset
 * @param string $cat_slug Category slug
 * @param string $post_type 'blog' or 'portfolio'
 * @return array Array of data for a range of posts
 */
if ( ! function_exists( 'bt_get_posts_data' ) ) {
	function bt_get_posts_data( $number, $offset, $cat_slug, $post_type = 'blog' ) {
		
		$posts_data1 = array();
		$posts_data2 = array();
		
		$sticky = false;
		
		if ( $offset == 0 && intval( bt_get_option( 'sticky_in_grid' ) == 1 ) && $post_type != 'portfolio' && $cat_slug == '' ) {
			$sticky = true;
			$recent_posts_q_sticky = new WP_Query( array( 'post__in' => get_option( 'sticky_posts' ), 'post_status' => 'publish' ) );
			$posts_data1 = bt_get_posts_array( $recent_posts_q_sticky, $post_type, array() );
		}
		
		if ( $number > 0 ) {
			if ( $post_type == 'portfolio' ) {
				if ( $cat_slug != '' ) {
					$recent_posts_q = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => $number, 'offset' => $offset, 'tax_query' => array( array( 'taxonomy' => 'portfolio_category', 'field' => 'slug', 'terms' => array( $cat_slug ) ) ), 'post_status' => 'publish' ) );
				} else {
					$recent_posts_q = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => $number, 'offset' => $offset, 'post_status' => 'publish' ) );
				}
			} else {
				if ( $cat_slug != '' ) {
					$recent_posts_q = new WP_Query( array( 'posts_per_page' => $number, 'offset' => $offset, 'category_name' => $cat_slug, 'post_status' => 'publish' ) );
				} else {
					$recent_posts_q = new WP_Query( array( 'posts_per_page' => $number, 'offset' => $offset, 'post_status' => 'publish' ) );
				}
			}
		}
		
		if ( $sticky ) {
			$posts_data2 = bt_get_posts_array( $recent_posts_q, $post_type, get_option( 'sticky_posts' ) );
		} else {
			$posts_data2 = bt_get_posts_array( $recent_posts_q, $post_type, array() );
		}		
		
		return array_merge( $posts_data1, $posts_data2 );

	}
}

/**
 * bt_get_posts_data helper function
 *
 * @param object
 * @param string
 * @param array 
 * @return array 
 */
if ( ! function_exists( 'bt_get_posts_array' ) ) {
	function bt_get_posts_array( $recent_posts_q, $post_type = 'blog', $sticky_arr ) {
		
		$posts_data = array();

		while ( $recent_posts_q->have_posts() ) {
			$recent_posts_q->the_post();
			$post = get_post();
			$post_author = $post->post_author;
			$post_id = get_the_ID();
			if ( in_array( $post_id, $sticky_arr ) ) {
				continue;
			}
			$posts_data[] = bt_get_posts_array_item( $post_type, $post_id, $post_author );
		}
		
		wp_reset_postdata();
		
		return $posts_data;
	}
}

/**
 * Returns post excerpt by post id
 *
 * @param int
 * @return string 
 */
if ( ! function_exists( 'bt_get_the_excerpt' ) ) {
	function bt_get_the_excerpt( $post_id ) {
		global $post;  
		$save_post = $post;
		$post = get_post( $post_id );
		$output = get_the_excerpt();
		$post = $save_post;
		return $output;
	}
}

/**
 * bt_get_posts_array helper function
 *
 * @return array
 */
if ( ! function_exists( 'bt_get_posts_array_item' ) ) {
	function bt_get_posts_array_item( $post_type = 'blog', $post_id, $post_author ) {
		global $date_format;
		
		$post_data = array();
		$post_data['permalink'] = get_permalink( $post_id );
		$post_data['format'] = get_post_format( $post_id );
		$post_data['title'] = get_the_title( $post_id );
		
		$post_data['excerpt'] = bt_get_the_excerpt( $post_id );
		
		$post_data['date'] = date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d', $post_id ) ) );
		
		$user_data = get_userdata( $post_author );
		if ( $user_data ) {
			$author = $user_data->data->display_name;
			$author_url = get_author_posts_url( $post_author );
			$post_data['author'] = '<a href="' . esc_url_raw( $author_url ) . '">' . esc_html( $author ) . '</a>';
		} else {
			$post_data['author'] = '';
		}

		if ( $post_type == 'portfolio' ) {
			$categories = wp_get_post_terms( $post_id, 'portfolio_category' );
		} else {
			$categories = get_the_category( $post_id );
		}
		$categories_html = '';
		if ( $categories ) {
			foreach ( $categories as $cat ) {
				if ( $post_type == 'portfolio' ) {
					$categories_html .= esc_html( $cat->name ) . ', ';
				} else {
					$categories_html .= '<a href="' . esc_url_raw( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>' . ', ';
				}
			}
			$categories_html = trim( $categories_html, ', ' );
		}

		$post_data['category'] = $categories_html;
		
		$comments_open = comments_open( $post_id );
		$comments_number = get_comments_number( $post_id );
		if ( ! $comments_open && $comments_number == 0 ) {
			$comments_number = false;
		}			
		
		$post_data['images'] = bt_rwmb_meta( BTPFX . '_images', 'type=image', $post_id );
		if ( $post_data['images'] == null ) $post_data['images'] = array();
		$post_data['video'] = bt_rwmb_meta( BTPFX . '_video', array(), $post_id );
		$post_data['audio'] = bt_rwmb_meta( BTPFX . '_audio', array(), $post_id );
		$post_data['grid_gallery'] = bt_rwmb_meta( BTPFX . '_grid_gallery', array(), $post_id );
		$post_data['link_title'] = bt_rwmb_meta( BTPFX . '_link_title', array(), $post_id );
		$post_data['link_url'] = bt_rwmb_meta( BTPFX . '_link_url', array(), $post_id );
		$post_data['quote'] = bt_rwmb_meta( BTPFX . '_quote', array(), $post_id );
		$post_data['quote_author'] = bt_rwmb_meta( BTPFX . '_quote_author', array(), $post_id );
		$post_data['tile_format'] = bt_rwmb_meta( BTPFX . '_tile_format', array(), $post_id );
		$post_data['comments'] = $comments_number;
		$post_data['ID'] = $post_id;
		
		return $post_data;
	}
}

/**
 * Returns share icons HTML
 *
 * @return string
 */
if ( ! function_exists( 'bt_get_share_html' ) ) {
	function bt_get_share_html( $permalink, $type = 'blog' ) {
		$share_facebook = bt_get_option( $type . '_share_facebook' );
		$share_twitter = bt_get_option( $type . '_share_twitter' );
		$share_google_plus = bt_get_option( $type . '_share_google_plus' );
		$share_linkedin = bt_get_option( $type . '_share_linkedin' );
		$share_vk = bt_get_option( $type . '_share_vk' );

		get_template_part( 'php/share' );

		$share_html = '';
		if ( $share_facebook || $share_twitter || $share_google_plus || $share_linkedin || $share_vk ) {
			
			if ( $share_facebook ) {
				$share_html .= '<a href="' . esc_url_raw( bt_get_share_link( 'facebook', $permalink ) ) . '" title="Facebook" data-icon-fa="&#xf09a;"></a>';
			}
			if ( $share_twitter ) {
				$share_html .= '<a href="' . esc_url_raw( bt_get_share_link( 'twitter', $permalink ) ) . '" title="Twitter" data-icon-fa="&#xf099;"></a>';
			}
			if ( $share_linkedin ) {
				$share_html .= '<a href="' . esc_url_raw( bt_get_share_link( 'linkedin', $permalink ) ) . '" title="LinkedIn" data-icon-fa="&#xf0e1;"></a>';
			}
			if ( $share_google_plus ) {
				$share_html .= '<a href="' . esc_url_raw( bt_get_share_link( 'google_plus', $permalink ) ) . '" title="Google Plus" data-icon-fa="&#xf0d5;"></a>';
			}
			if ( $share_vk ) {
				$share_html .= '<a href="' . esc_url_raw( bt_get_share_link( 'vk', $permalink ) ) . '" title="VK" data-icon-fa="&#xf189;"></a>';
			}
		}
		return $share_html;
	}
}

/**
 * Custom MetaBox getter function
 *
 * @return string
 */
if ( ! function_exists( 'bt_rwmb_meta' ) ) {
	function bt_rwmb_meta( $key, $args = array(), $post_id = null ) {
		if ( function_exists( 'rwmb_meta' ) ) {
			return rwmb_meta( $key, $args, $post_id );
		} else {
			return null;
		}
	}
}

/**
 * Returns page id by slug
 *
 * @return string
 */
if ( ! function_exists( 'bt_get_id_by_slug' ) ) {
	function bt_get_id_by_slug( $page_slug ) {
		$page = get_posts(
			array(
				'name'      => $page_slug,
				'post_type' => 'page'
			)
		);
		return $page[0]->ID;
	}
}

/**
 * Creates override of global options for individual posts
 */
if ( ! function_exists( 'bt_set_override' ) ) {
	function bt_set_override() {
		global $bt_options;
		$bt_options = get_option( BTPFX . '_theme_options' );

		global $bt_page_options;
		$bt_page_options = array();
		 
		if ( ! is_404() ) {
			$tmp_bt_page_options = bt_rwmb_meta( BTPFX . '_override' );
			$tmp_bt_page_options1 = '';
			if ( ( is_search() || is_archive() || is_home() || is_singular( 'post' ) ) && get_option( 'page_for_posts' ) != 0 ) {
				$tmp_bt_page_options1 = bt_rwmb_meta( BTPFX . '_override', array(), get_option( 'page_for_posts' ) );
			} else if ( ( is_post_type_archive( 'portfolio' ) || is_singular( 'portfolio' ) ) && isset( $bt_options['pf_settings_page_slug'] ) && $bt_options['pf_settings_page_slug'] != '' ) {
				$tmp_bt_page_options1 = bt_rwmb_meta( BTPFX . '_override', array(), bt_get_id_by_slug( $bt_options['pf_settings_page_slug'] ) );
			}
			
			if ( ! is_array( $tmp_bt_page_options ) ) $tmp_bt_page_options = array();

			if ( is_array( $tmp_bt_page_options1 ) ) {
				if ( is_singular() ) {
					$tmp_bt_page_options = array_merge( bt_transform_override( $tmp_bt_page_options1 ), bt_transform_override( $tmp_bt_page_options ) );
				} else {
					$tmp_bt_page_options = bt_transform_override( $tmp_bt_page_options1 );
				}
			} else if ( count( $tmp_bt_page_options ) > 0 ) {
				$tmp_bt_page_options = bt_transform_override( $tmp_bt_page_options );
			}

			foreach ( $tmp_bt_page_options as $key => $value ) {
				$bt_page_options[ $key ] = $value;
			}
		}
	}
}

/**
 * bt_set_override helper function
 *
 * @param array
 * @return string
 */
if ( ! function_exists( 'bt_transform_override' ) ) {
	function bt_transform_override( $arr ) {
		$new_arr = array();
		foreach( $arr as $item ) {
			$key = substr( $item, 0, strpos( $item, ':' ) );
			$value = substr( $item, strpos( $item, ':' ) + 1 );
			$new_arr[ $key ] = $value;
		}
		return $new_arr;
	}
}

/**
 * Header contact links HTML (right side)
 *
 * @return string
 */
if ( ! function_exists( 'bt_get_header_right' ) ) {
	function bt_get_header_right() {
		
		$facebook = bt_get_option( 'contact_facebook' );
		$twitter = bt_get_option( 'contact_twitter' );
		$linkedin = bt_get_option( 'contact_linkedin' );
		$youtube = bt_get_option( 'contact_youtube' );
		$instagram = bt_get_option( 'contact_instagram' );	
		
		$phone = bt_get_option( 'contact_phone' );
		$email = bt_get_option( 'contact_email' );
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
				$work_time_html = '<div class="ico white"><a href="' . esc_url_raw( get_permalink( $contact_page[0]->ID ) ) . '" data-ico-bold="&#xe637;"><strong>' . $work_time . '</strong></a></div>';
			} else {
				$work_time_html = '<div class="ico white"><a href="#" data-ico-bold="&#xe637;"><strong>' . $work_time . '</strong></div>';
			}
		} else {
			$work_time_html = '<div class="ico white"><a href="#" data-ico-bold="&#xe637;"><strong>' . $work_time . '</strong></div>';
		}		
		
		$contact_html_right = '';
		
		if ( $phone != '' ) {
			$contact_html_right .= '<div class="ico white"><a href="' . esc_url_raw( 'tel:' . $phone ) . '" data-ico-bold="&#xe60f;"><span>' . $phone . '</span></a></div>';
		}

		if ( $email != '' ) {
			$contact_html_right .= '<div class="ico white"><a href="' . esc_url_raw( 'mailto:' . $email ) . '" data-ico-bold="&#xe616;"><span>' . $email . '</span></a></div>';
		}
		
		if ( $work_time != '' ) {
			$contact_html_right .= $work_time_html;
		}

		if ( $facebook != '' ) {
			$contact_html_right .= '<div class="ico"><a href="' . esc_url_raw( $facebook ) . '" data-ico-bold="&#xe618;"></a></div>';
		}

		if ( $twitter != '' ) {
			$contact_html_right .= '<div class="ico"><a href="' . esc_url_raw( $twitter ) . '" data-ico-bold="&#xe646;"></a></div>';
		}

		if ( $linkedin != '' ) {
			$contact_html_right .= '<div class="ico"><a href="' . esc_url_raw( $linkedin ) . '" data-ico-bold="&#xe632;"></a></div>';
		}

		if ( $youtube != '' ) {
			$contact_html_right .= '<div class="ico"><a href="' . esc_url_raw( $youtube ) . '" data-ico-bold="&#xe64c;"></a></div>';
		}
		
		if ( $instagram != '' ) {
			$contact_html_right .= '<div class="ico"><a href="' . esc_url_raw( $instagram ) . '" data-ico-fa="&#xf16d;"></a></div>';
		}		

		$contact_html_right .= '<div class="ico accent" id="btSearchIcon"><a href="#" data-ico-bold="&#xe645;"></a></div>';
		
		return $contact_html_right;
	}
}

/**
 * Returns header links HTML (left side)
 *
 * @return string 
 */
if ( ! function_exists( 'bt_get_header_left' ) ) {
	function bt_get_header_left() {
		$header_link_text_1 = bt_get_option( 'header_link_text_1' );
		$header_link_slug_1 = bt_get_option( 'header_link_slug_1' );
		$header_link_icon_1 = bt_get_option( 'header_link_icon_1' );
		
		$header_link_text_2 = bt_get_option( 'header_link_text_2' );
		$header_link_slug_2 = bt_get_option( 'header_link_slug_2' );
		$header_link_icon_2 = bt_get_option( 'header_link_icon_2' );
		
		$contact_html_left = '';

		if ( $header_link_text_1 != '' && $header_link_icon_1 != '' ) {
			if ( $header_link_slug_1 != '' ) {
				$link_1 = get_posts(
					array(
						'name'      => $header_link_slug_1,
						'post_type' => 'page'
					)
				);
				if ( ( is_array( $link_1 ) && isset( $link_1[0]->ID ) ) || substr( $header_link_slug_1, 0, 4 ) == 'http' ) {
					if ( substr( $header_link_slug_1, 0, 4 ) == 'http' ) {
						$link = $header_link_slug_1;
					} else {
						$link = get_permalink( $link_1[0]->ID );
					}			
					$contact_html_left .= '<div class="ico borderless"><a href="' . esc_url_raw( $link ) . '" data-ico-fa="&#x' . esc_attr( $header_link_icon_1 ) . ';"><span>' . $header_link_text_1 .'</span></a></div>';
				} else {
					$contact_html_left .= '<div class="ico borderless"><a href="#" data-ico-fa="&#x' . esc_attr( $header_link_icon_1 ) . ';"><span>' . $header_link_text_1 . '</span></a></div>';
				}
			} else {
				$contact_html_left .= '<div class="ico borderless"><a href="#" data-ico-fa="&#x' . esc_attr( $header_link_icon_1 ) . ';"><span>' . $header_link_text_1 . '</span></a></div>';
			}
		}

		if ( $header_link_text_2 != '' && $header_link_icon_2 != '' ) {
			if ( $header_link_slug_2 != '' ) {
				$link_2 = get_posts(
					array(
						'name'      => $header_link_slug_2,
						'post_type' => 'page'
					)
				);
				if ( ( is_array( $link_2 ) && isset( $link_2[0]->ID ) ) || substr( $header_link_slug_2, 0, 4 ) == 'http' ) {
					if ( substr( $header_link_slug_2, 0, 4 ) == 'http' ) {
						$link = $header_link_slug_2;
					} else {
						$link = get_permalink( $link_2[0]->ID );
					}
					$contact_html_left .= '<div class="ico borderless"><a href="' . esc_url_raw( $link ) . '" data-ico-fa="&#x' . $header_link_icon_2 . ';"><span>' . $header_link_text_2 .'</span></a></div>';
				} else {
					$contact_html_left .= '<div class="ico borderless"><a href="#" data-ico-fa="&#x' . esc_attr( $header_link_icon_2 ) . ';"><span>' . $header_link_text_2 . '</span></a></div>';
				}
			} else {
				$contact_html_left .= '<div class="ico borderless"><a href="#" data-ico-fa="&#x' . esc_attr( $header_link_icon_2 ) . ';"><span>' . $header_link_text_2 . '</span></a></div>';
			}
		}
		
		return $contact_html_left;
	}
}

/**
 * Header meta tags output
 */
if ( ! function_exists( 'bt_header_meta' ) ) {
	function bt_header_meta() {
		$desc = bt_rwmb_meta( BTPFX . '_description' );
		
		if ( $desc != '' ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">';
		}
		
		if ( is_single() ) {
			echo '<meta property="twitter:card" content="summary">';

			echo '<meta property="og:title" content="' . get_the_title() . '" />';
			echo '<meta property="og:type" content="article" />';
			echo '<meta property="og:url" content="' . get_permalink() . '" />';
			
			$img = null;
			
			$bt_featured_slider = bt_get_option( 'blog_ghost_slider' ) && has_post_thumbnail();
			if ( $bt_featured_slider ) {
				$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
				$img = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
				$img = $img[0];
			} else {
				$images = bt_rwmb_meta( BTPFX . '_images', 'type=image' );
				if ( is_array( $images ) ) {
					foreach ( $images as $img ) {
						$img = $img['full_url'];
						break;
					}
				}
			}
			if ( $img ) {
				echo '<meta property="og:image" content="' . esc_attr( $img ) . '" />';
			}
			
			if ( $desc != '' ) {
				echo '<meta property="og:description" content="' . esc_attr( $desc ) . '" />';
			}
		}
	
		$favicon = bt_get_option( 'favicon' );
		$mobile_touch_icon = bt_get_option( 'mobile_touch_icon' );
		
		if ( strpos( $favicon, '/wp-content' ) === 0 ) $favicon = get_site_url() . $favicon;
		if ( strpos( $mobile_touch_icon, '/wp-content' ) === 0 ) $mobile_touch_icon = get_site_url() . $mobile_touch_icon;
		
		if ( bt_get_option( 'favicon' ) != '' ) {
			echo '<link rel="shortcut icon" href="' . esc_url_raw( $favicon ) . '" type="image/x-icon">';
		}
		
		if ( bt_get_option( 'mobile_touch_icon' ) != '' ) {
			echo '<link rel="icon" href="' . esc_url_raw( $mobile_touch_icon ) . '">';
			echo '<link rel="apple-touch-icon-precomposed" href="' . esc_url_raw( $mobile_touch_icon ) . '">';
		}
		
		echo '<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">';
		
	}
}

/**
 * Header menu output
 */
if ( ! function_exists( 'bt_nav_menu' ) ) {
	function bt_nav_menu() {
		if ( bt_rwmb_meta( BTPFX . '_menu_name' ) != '' ) {
			wp_nav_menu( array( 'menu' => bt_rwmb_meta( BTPFX . '_menu_name' ), 'items_wrap' => '%3$s', 'container' => '', 'depth' => 3, 'fallback_cb' => false )); 
		} else {
			wp_nav_menu( array( 'theme_location' => 'primary', 'items_wrap' => '%3$s', 'container' => '', 'depth' => 3, 'fallback_cb' => false ) );
		}
	}
}

/**
 * Returns custom header class
 *
 * @return string
 */
if ( ! function_exists( 'bt_get_header_class' ) ) {
	function bt_get_header_class() {
		$extra_class = array( 'pageWrap' );
		
		$menu_type = bt_get_option( 'menu_type' );
		if ( $menu_type == 'centered' ) {
			$extra_class[] = 'boldMenuCenter'; 
		} else if ( $menu_type == 'right' ) {
			$extra_class[] = 'boldMenuRight';
		} else if ( $menu_type == 'left' ) {
			$extra_class[] = 'boldMenuLeft';
		}

		if ( bt_get_option( 'sticky_header' ) ) {
			$extra_class[] = 'stickyEnabled';
		}
		
		$extra_class[] = 'belowMenu';
		
		$bt_sidebar = bt_get_option( 'sidebar' );
		
		global $bt_has_sidebar;

		if ( ! ( ( $bt_sidebar == 'left' || $bt_sidebar == 'right' ) && ! is_404() && ! is_search() ) ) {
			$bt_has_sidebar = false;
			$extra_class[] = 'noSidebar';
		} else {
			$bt_has_sidebar = true;
			if ( $bt_sidebar == 'left' ) {
				$extra_class[] = 'sidebar sidebarLeft';
			} else {
				$extra_class[] = 'sidebar sidebarRight';
			}
		}
		
		return $extra_class;
	}
}

/**
 * Enqueue comment script
 */
if ( ! function_exists( 'bt_header_init' ) ) {
	function bt_header_init() {
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}
 	}
}

/**
 * Custom header menu class output
 */
if ( ! function_exists( 'bt_header_menu_class' ) ) {
	function bt_header_menu_class() {
		$boxed_menu = bt_get_option( 'boxed_menu' );
		if ( $boxed_menu ) {
			echo ' gutter';
		}
	}
}

/**
 * Header headline output
 */
if ( ! function_exists( 'bt_header_headline' ) ) {
	function bt_header_headline() {
		$hide_headline = bt_get_option( 'hide_headline' );
		if ( ! $hide_headline && ! is_archive() && ! is_404() && ! is_single()  ) { ?>
			<h1><?php wp_title( '' ); ?></h1>
		<?php }
 	}
}