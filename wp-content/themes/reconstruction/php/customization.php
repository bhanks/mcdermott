<?php
if ( ! class_exists( 'BT_Customize_Default' ) ) {

	class BT_Customize_Default {

		// GENERAL SETTINGS

		public static $favicon = ''; // 32x32

		public static $logo = '';
		
		public static $mobile_touch_icon = ''; // 196x196
		
		public static $light_skin = false;
		
		public static $menu_type = 'right';
		public static $boxed_menu = true;
		public static $hide_headline = true;

		public static $sticky_header = false;
	
		public static $sidebar = 'right';
		
		public static $accent_color = '';
		
		public static $body_font = 'no_change';
		public static $heading_font = 'no_change';
		
		public static $disable_preloader = true;
		public static $preloader_text = 'Loading...';

		public static $custom_css = '';
		public static $custom_js_top = '';
		public static $custom_js_bottom = '';
		
		// BLOG
		
		public static $blog_ghost_slider = false;
		public static $blog_grid_gallery_columns = '4';
		public static $blog_author = true;
		public static $blog_date = true;
		public static $blog_side_info = false;
		public static $blog_author_info = false;
		public static $blog_share_facebook = true;
		public static $blog_share_twitter = true;
		public static $blog_share_google_plus = true;
		public static $blog_share_linkedin = true;
		public static $blog_share_vk = true;
		public static $sticky_in_grid = false;
		
		// PORTFOLIO
		
		public static $pf_ghost_slider = true;
		public static $pf_grid_gallery_columns = '3';
		public static $pf_share_facebook = true;
		public static $pf_share_twitter = true;
		public static $pf_share_google_plus = true;
		public static $pf_share_linkedin = true;
		public static $pf_share_vk = true;
		public static $pf_settings_page_slug = '';
		
		// HEADER / FOOTER 
		
		public static $contact_facebook = '';
		public static $contact_twitter = '';
		public static $contact_linkedin = '';
		public static $contact_youtube = '';
		public static $contact_instagram = '';
		
		public static $contact_phone = '';
		public static $contact_email = '';
		public static $work_time = '';
		public static $contact_address = '';
		public static $contact_page_slug = '';
		
		public static $header_link_text_1 = '';
		public static $header_link_slug_1 = '';
		public static $header_link_icon_1 = '';
		
		public static $header_link_text_2 = '';
		public static $header_link_slug_2 = '';
		public static $header_link_icon_2 = '';
		
		public static $custom_text = '';
		
		
	}
}

if ( ! function_exists( 'bt_fa_icons' ) ) {
	get_template_part( 'php/bt_fa_icons' );
}

if ( ! function_exists( 'bt_get_option' ) ) {
	function bt_get_option( $opt ) {

		global $bt_options;
		global $bt_page_options;

		if ( isset( BT_Customize_Default::$$opt ) ) {
			if ( isset( $_GET[ $opt ] ) ) {
				$ret = $_GET[ $opt ];
				if ( $ret === 'true' ) {
					$ret = true;
				} else if ( $ret === 'false' ) {
					$ret = false;
				}
				return $ret;
			}			
		}
		if ( $bt_page_options !== null && array_key_exists( BTPFX . '_' . $opt, $bt_page_options ) && $bt_page_options[ BTPFX . '_' . $opt ] === 'null' ) {
			return BT_Customize_Default::$$opt;
		}
		if ( $bt_page_options !== null && array_key_exists( BTPFX . '_' . $opt, $bt_page_options ) ) {
			$ret = $bt_page_options[ BTPFX . '_' . $opt ];
			if ( $ret === 'true' ) {
				$ret = true;
			} else if ( $ret === 'false' ) {
				$ret = false;
			}
			return $ret;
		}
		if ( $bt_options !== null && $bt_options !== false && array_key_exists( $opt, $bt_options ) ) {
			$ret = $bt_options[ $opt ];
			if ( $ret === 'true' ) {
				$ret = true;
			} else if ( $ret === 'false' ) {
				$ret = false;
			}
			return $ret;
		} else { 
			if ( $bt_options !== null ) {
				return BT_Customize_Default::$$opt;
			} else {
				$bt_options = get_option( BTPFX . '_theme_options' );
				if ( is_array( $bt_options ) && array_key_exists( $opt, $bt_options ) ) {
					$ret = $bt_options[ $opt ];
					if ( $ret === 'true' ) {
						$ret = true;
					} else if ( $ret === 'false' ) {
						$ret = false;
					}
					return $ret;
				} else {
					return BT_Customize_Default::$$opt;
				}
			}
		}

	}
}

if ( ! function_exists( 'bt_logo' ) ) {
	function bt_logo( $type = 'header' ) {
		
		$logo = bt_get_option( 'logo' );

		$home_link = home_url();
		if ( $logo != '' ) {
			if ( $type == 'header' ) {
				$image = wp_get_attachment_image_src( attachment_url_to_postid( $logo ), 'full' );
				$width = $image[1];
				$height = $image[2];
				echo '<a href="' . esc_url_raw( $home_link ) . '"><img data-w="' . $width . '" data-h="' . $height . '" class="btMainLogo" src="' . esc_url_raw( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" />';
				echo '</a>';
			} else if ( $type == 'footer' ) {
				echo '<a href="' . esc_url_raw( $home_link ) . '"><img src="' . esc_url_raw( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '"></a>';
			} else if ( $type == 'preloader' ) {
				echo '<img class="preloaderLogo" src="' . esc_url_raw( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" />';
			}
		}
	}
}

if ( ! function_exists( 'bt_custom_controls' ) ) {
	function bt_custom_controls() {

		class BT_Customize_Textarea_Control extends WP_Customize_Control {
			public function render_content() {
				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<textarea rows="5" style="width:98%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value()); ?></textarea>
				</label>
				<?php
			}
		}
		
		class BT_Reset_Control extends WP_Customize_Control {
			public function render_content() {
				?>
				<div style="margin: 5px 0px 10px 0px">
				<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label>			
					<input type="submit" onclick="var c = confirm('<?php echo esc_js( __( 'Reset theme settings to default values?', 'bt_theme' ) ); ?>'); if (c != true) return false;var href=window.location.href;if (href.indexOf('?') > -1) {window.location.replace(href + '&bt_reset=reset')} else {window.location.replace(href + '?bt_reset=reset')};return false;" name="bt_reset" id="bt_reset" class="button" value="Reset">
				</div>
				<?php
			}
		}
	}
}
add_action( 'customize_register', 'bt_custom_controls' );

if ( ! function_exists( 'bt_customize_register' ) ) {
	function bt_customize_register( $wp_customize ) {
		global $wpdb;
		if ( isset( $_GET['bt_reset'] ) && $_GET['bt_reset'] == 'reset' ) {
			$wpdb->query( 'delete from ' . $wpdb->options . ' where option_name = "' . BTPFX . '_theme_options"' );
			header( 'Location: ' . wp_customize_url());
		}

		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'title_tagline' );
		$wp_customize->remove_section( 'nav' );
		$wp_customize->remove_section( 'static_front_page' );
		
		$wp_customize->add_section( BTPFX . '_general_section' , array(
			'title'      => __( 'General Settings', 'bt_theme' ),
			'priority'   => 10,
		));
		$wp_customize->add_section( BTPFX . '_background_section' , array(
			'title'      => __( 'Background', 'bt_theme' ),
			'priority'   => 30,
		));
		$wp_customize->add_section( BTPFX . '_blog_section' , array(
			'title'      => __( 'Blog', 'bt_theme' ),
			'priority'   => 50,
		));
		$wp_customize->add_section( BTPFX . '_pf_section' , array(
			'title'      => __( 'Portfolio', 'bt_theme' ),
			'priority'   => 52,
		));
		$wp_customize->add_section( BTPFX . '_footer_section' , array(
			'title'      => __( 'Header / Footer', 'bt_theme' ),
			'priority'   => 60,
		));
		
		/* GENERAL SETTINGS */
		
		// FAVICON
		$wp_customize->add_setting( BTPFX . '_theme_options[favicon]', array(
			'default'           => BT_Customize_Default::$favicon,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'favicon',
				array(
					'label'    => __( 'Favicon', 'bt_theme' ),
					'section'  => BTPFX . '_general_section',
					'settings' => BTPFX . '_theme_options[favicon]',
					'priority' => 10,
					'context'  => BTPFX . '_favicon'
				)
			)
		);
		
		// LOGO
		$wp_customize->add_setting( BTPFX . '_theme_options[logo]', array(
			'default'           => BT_Customize_Default::$logo,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'logo',
				array(
					'label'    => __( 'Logo', 'bt_theme' ),
					'section'  => BTPFX . '_general_section',
					'settings' => BTPFX . '_theme_options[logo]',
					'priority' => 20,
					'context'  => BTPFX . '_logo'
				)
			)
		);
		
	
		// MOBILE TOUCH ICON
		$wp_customize->add_setting( BTPFX . '_theme_options[mobile_touch_icon]', array(
			'default'           => BT_Customize_Default::$mobile_touch_icon,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'mobile_touch_icon',
				array(
					'label'    => __( 'Mobile Touch Icon', 'bt_theme' ),
					'section'  => BTPFX . '_general_section',
					'settings' => BTPFX . '_theme_options[mobile_touch_icon]',
					'priority' => 35,
					'context'  => BTPFX . '_mobile_touch_icon'
				)
			)
		);
		
		// MENU TYPE
		$wp_customize->add_setting( BTPFX . '_theme_options[menu_type]', array(
			'default'           => 'right',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'menu_type', array(
			'label'     => __( 'Menu Type', 'bt_theme' ),
			'section'   => BTPFX . '_general_section',
			'settings'  => BTPFX . '_theme_options[menu_type]',
			'priority'  => 60,
			'type'      => 'select',
			'choices'   => array(
				'left'     => __( 'Left', 'bt_theme' ),
				'centered' => __( 'Centered', 'bt_theme' ),
				'right'    => __( 'Right', 'bt_theme' )
			)
		));
		
		// HIDE HEADLINE
		$wp_customize->add_setting( BTPFX . '_theme_options[hide_headline]', array(
				'default'           => BT_Customize_Default::$hide_headline,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'hide_headline', array(
				'label'    => __( 'Hide Headline', 'bt_theme' ),
				'section'  => BTPFX . '_general_section',
				'settings' => BTPFX . '_theme_options[hide_headline]',
				'priority' => 64,
				'type'     => 'checkbox'
		));
		
		// BOXED MENU
		$wp_customize->add_setting( BTPFX . '_theme_options[boxed_menu]', array(
			'default'           => BT_Customize_Default::$boxed_menu,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'boxed_menu', array(
			'label'    => __( 'Boxed Menu', 'bt_theme' ),
			'section'  => BTPFX . '_general_section',
			'settings' => BTPFX . '_theme_options[boxed_menu]',
			'priority' => 65,
			'type'     => 'checkbox'
		));
		
		// STICKY HEADER
		$wp_customize->add_setting( BTPFX . '_theme_options[sticky_header]', array(
			'default'           => BT_Customize_Default::$sticky_header,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'sticky_header', array(
			'label'    => __( 'Sticky Header', 'bt_theme' ),
			'section'  => BTPFX . '_general_section',
			'settings' => BTPFX . '_theme_options[sticky_header]',
			'priority' => 80,
			'type'     => 'checkbox'
		));
		

		// SIDEBAR
		$wp_customize->add_setting( BTPFX . '_theme_options[sidebar]', array(
			'default'           => BT_Customize_Default::$sidebar,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'sidebar', array(
			'label'     => __( 'Sidebar', 'bt_theme' ),
			'section'   => BTPFX . '_general_section',
			'settings'  => BTPFX . '_theme_options[sidebar]',
			'priority'  => 93,
			'type'      => 'select',
			'choices'   => array(
				'no_sidebar' => __( 'No Sidebar', 'bt_theme' ),
				'left'       => __( 'Left', 'bt_theme' ),
				'right'      => __( 'Right', 'bt_theme' )
			)
		));
	
		// ACCENT COLOR
		$wp_customize->add_setting( BTPFX . '_theme_options[accent_color]', array(
			'default'        	   => BT_Customize_Default::$accent_color,
			'type'           	   => 'option',
			'capability'     	   => 'edit_theme_options',
			'sanitize_callback'    => 'sanitize_text_field'
		));
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
			'label'    => __( 'Accent Color', 'bt_theme' ),
			'section'  => BTPFX . '_general_section',
			'settings' => BTPFX . '_theme_options[accent_color]',
			'priority' => 95
		)));
		
		global $bt_fonts;
		get_template_part( 'php/web_fonts' );
		$choices = array( 'no_change' => __( 'No Change', 'bt_theme' ) );
		foreach ( $bt_fonts as $font ) {
			$choices[$font['css-name']] = $font['font-name'];
		}

		// BODY FONT
		$wp_customize->add_setting( BTPFX . '_theme_options[body_font]', array(
			'default'           => 'no_change',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'body_font', array(
			'label'     => __( 'Body Font', 'bt_theme' ),
			'section'   => BTPFX . '_general_section',
			'settings'  => BTPFX . '_theme_options[body_font]',
			'priority'  => 97,
			'type'      => 'select',
			'choices'   => $choices
		));
		
		// HEADING FONT
		$wp_customize->add_setting( BTPFX . '_theme_options[heading_font]', array(
			'default'           => 'no_change',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'heading_font', array(
			'label'     => __( 'Heading Font', 'bt_theme' ),
			'section'   => BTPFX . '_general_section',
			'settings'  => BTPFX . '_theme_options[heading_font]',
			'priority'  => 100,
			'type'      => 'select',
			'choices'   => $choices
		));
		
		// DISABLE PRELOADER
		$wp_customize->add_setting( BTPFX . '_theme_options[disable_preloader]', array(
			'default'           => BT_Customize_Default::$disable_preloader,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'disable_preloader', array(
			'label'    => esc_html__( 'Disable Preloader', 'bt_theme' ),
			'section'  => BTPFX . '_general_section',
			'settings' => BTPFX . '_theme_options[disable_preloader]',
			'priority' => 101,
			'type'     => 'checkbox'
		));			
		
		// PRELOADER TEXT
		$wp_customize->add_setting( BTPFX . '_theme_options[preloader_text]', array(
			'default'           => BT_Customize_Default::$preloader_text,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'preloader_text', array(
			'label'    => __( 'Preloader Text', 'bt_theme' ),
			'section'  => BTPFX . '_general_section',
			'settings' => BTPFX . '_theme_options[preloader_text]',
			'priority' => 102,
			'type'     => 'text'
		));

		// CUSTOM CSS
		$wp_customize->add_setting( BTPFX . '_theme_options[custom_css]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( new BT_Customize_Textarea_Control( 
			$wp_customize, 
			'custom_css', array(
				'label'    => __( 'Custom CSS', 'bt_theme' ),
				'section'  => BTPFX . '_general_section',
				'priority' => 104,
				'settings' => BTPFX . '_theme_options[custom_css]'
			)
		));
		
		// CUSTOM JS TOP
		$wp_customize->add_setting( BTPFX . '_theme_options[custom_js_top]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'bt_custom_js'
		));
		$wp_customize->add_control( new BT_Customize_Textarea_Control( 
			$wp_customize, 
			'custom_js_top', array(
				'label'    => __( 'Custom JS (Top)', 'bt_theme' ),
				'section'  => BTPFX . '_general_section',
				'priority' => 105,
				'settings' => BTPFX . '_theme_options[custom_js_top]'
			)
		));
		
		// CUSTOM JS BOTTOM
		$wp_customize->add_setting( BTPFX . '_theme_options[custom_js_bottom]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'bt_custom_js'
		));
		$wp_customize->add_control( new BT_Customize_Textarea_Control( 
			$wp_customize, 
			'custom_js_bottom', array(
				'label'    => __( 'Custom JS (Bottom)', 'bt_theme' ),
				'section'  => BTPFX . '_general_section',
				'priority' => 110,
				'settings' => BTPFX . '_theme_options[custom_js_bottom]'
			)
		));

		// RESET
		$wp_customize->add_setting( BTPFX . '_theme_options[reset]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( new BT_Reset_Control( 
			$wp_customize, 
			'reset', array(
				'label'    => __( 'Reset Theme Settings', 'bt_theme' ),
				'section'  => BTPFX . '_general_section',
				'priority' => 130,
				'settings' => BTPFX . '_theme_options[reset]'
			)
		));
		
		/* BLOG */
		
		// GHOST SLIDER
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_ghost_slider]', array(
			'default'           => BT_Customize_Default::$blog_ghost_slider,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_ghost_slider', array(
			'label'    => __( 'Ghost Slider', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_ghost_slider]',
			'priority' => 1,
			'type'     => 'checkbox'
		));
		
		// GRID GALLERY COLUMNS
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_grid_gallery_columns]', array(
			'default'           => BT_Customize_Default::$blog_grid_gallery_columns,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_grid_gallery_columns', array(
			'label'     => __( 'Grid Gallery Columns', 'bt_theme' ),
			'section'   => BTPFX . '_blog_section',
			'settings'  => BTPFX . '_theme_options[blog_grid_gallery_columns]',
			'priority'  => 6,
			'type'      => 'select',
			'choices'   => array(
				'3' => __( '3', 'bt_theme' ),
				'4' => __( '4', 'bt_theme' ),
				'5' => __( '5', 'bt_theme' ),
				'6' => __( '6', 'bt_theme' )				
			)
		));

		// AUTHOR
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_author]', array(
			'default'           => BT_Customize_Default::$blog_author,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_author', array(
			'label'    => __( 'Show Author', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_author]',
			'priority' => 8,
			'type'     => 'checkbox'
		));

		// DATE
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_date]', array(
			'default'           => BT_Customize_Default::$blog_date,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_date', array(
			'label'    => __( 'Show Post Date', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_date]',
			'priority' => 10,
			'type'     => 'checkbox'
		));
		
		// BLOG SIDE INFO
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_side_info]', array(
			'default'           => BT_Customize_Default::$blog_side_info,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_side_info', array(
			'label'    => __( 'Show Author Avatar', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_side_info]',
			'priority' => 12,
			'type'     => 'checkbox'
		));
		
		// AUTHOR INFO
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_author_info]', array(
			'default'           => BT_Customize_Default::$blog_author_info,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_author_info', array(
			'label'    => __( 'Show Author Information', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_author_info]',
			'priority' => 15,
			'type'     => 'checkbox'
		));

		// SHARE ON FACEBOOK
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_share_facebook]', array(
			'default'           => BT_Customize_Default::$blog_share_facebook,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_share_facebook', array(
			'label'    => __( 'Share on Facebook', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_share_facebook]',
			'priority' => 18,
			'type'     => 'checkbox'
		));
		
		// SHARE ON TWITTER
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_share_twitter]', array(
			'default'           => BT_Customize_Default::$blog_share_twitter,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_share_twitter', array(
			'label'    => __( 'Share on Twitter', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_share_twitter]',
			'priority' => 20,
			'type'     => 'checkbox'
		));

		// SHARE ON GOOGLE PLUS
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_share_google_plus]', array(
			'default'           => BT_Customize_Default::$blog_share_google_plus,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_share_google_plus', array(
			'label'    => __( 'Share on Google Plus', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_share_google_plus]',
			'priority' => 30,
			'type'     => 'checkbox'
		));

		// SHARE ON LINKEDIN
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_share_linkedin]', array(
			'default'           => BT_Customize_Default::$blog_share_linkedin,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_share_linkedin', array(
			'label'    => __( 'Share on LinkedIn', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_share_linkedin]',
			'priority' => 40,
			'type'     => 'checkbox'
		));
		
		// SHARE ON VK
		$wp_customize->add_setting( BTPFX . '_theme_options[blog_share_vk]', array(
			'default'           => BT_Customize_Default::$blog_share_vk,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'blog_share_vk', array(
			'label'    => __( 'Share on VK', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[blog_share_vk]',
			'priority' => 50,
			'type'     => 'checkbox'
		));
		
		// STICKY POSTS IN GRID/TILES
		$wp_customize->add_setting( BTPFX . '_theme_options[sticky_in_grid]', array(
			'default'           => BT_Customize_Default::$sticky_in_grid,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'sticky_in_grid', array(
			'label'    => __( 'Sticky Posts in Grid/Tiles', 'bt_theme' ),
			'section'  => BTPFX . '_blog_section',
			'settings' => BTPFX . '_theme_options[sticky_in_grid]',
			'priority' => 60,
			'type'     => 'checkbox'
		));		
		
		/* PORTFOLIO */
		
		// GHOST SLIDER
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_ghost_slider]', array(
			'default'           => BT_Customize_Default::$pf_ghost_slider,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_ghost_slider', array(
			'label'    => __( 'Ghost Slider', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_ghost_slider]',
			'priority' => 3,
			'type'     => 'checkbox'
		));
		
		// GRID GALLERY COLUMNS
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_grid_gallery_columns]', array(
			'default'           => BT_Customize_Default::$pf_grid_gallery_columns,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_grid_gallery_columns', array(
			'label'     => __( 'Grid Gallery Columns', 'bt_theme' ),
			'section'   => BTPFX . '_pf_section',
			'settings'  => BTPFX . '_theme_options[pf_grid_gallery_columns]',
			'priority'  => 5,
			'type'      => 'select',
			'choices'   => array(
				'3' => __( '3', 'bt_theme' ),
				'4' => __( '4', 'bt_theme' ),
				'5' => __( '5', 'bt_theme' ),
				'6' => __( '6', 'bt_theme' )				
			)
		));			
		
		// SHARE ON FACEBOOK
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_share_facebook]', array(
			'default'           => BT_Customize_Default::$pf_share_facebook,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_share_facebook', array(
			'label'    => __( 'Share on Facebook', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_share_facebook]',
			'priority' => 10,
			'type'     => 'checkbox'
		));
		
		// SHARE ON TWITTER
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_share_twitter]', array(
			'default'           => BT_Customize_Default::$pf_share_twitter,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_share_twitter', array(
			'label'    => __( 'Share on Twitter', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_share_twitter]',
			'priority' => 20,
			'type'     => 'checkbox'
		));

		// SHARE ON GOOGLE PLUS
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_share_google_plus]', array(
			'default'           => BT_Customize_Default::$pf_share_google_plus,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_share_google_plus', array(
			'label'    => __( 'Share on Google Plus', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_share_google_plus]',
			'priority' => 30,
			'type'     => 'checkbox'
		));

		// SHARE ON LINKEDIN
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_share_linkedin]', array(
			'default'           => BT_Customize_Default::$pf_share_linkedin,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_share_linkedin', array(
			'label'    => __( 'Share on LinkedIn', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_share_linkedin]',
			'priority' => 40,
			'type'     => 'checkbox'
		));
		
		// SHARE ON VK
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_share_vk]', array(
			'default'           => BT_Customize_Default::$pf_share_vk,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_share_vk', array(
			'label'    => __( 'Share on VK', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_share_vk]',
			'priority' => 50,
			'type'     => 'checkbox'
		));
		
		// SETTINGS PAGE SLUG
		$wp_customize->add_setting( BTPFX . '_theme_options[pf_settings_page_slug]', array(
			'default'           => BT_Customize_Default::$pf_settings_page_slug,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'pf_settings_page_slug', array(
			'label'    => __( 'Settings Page Slug', 'bt_theme' ),
			'section'  => BTPFX . '_pf_section',
			'settings' => BTPFX . '_theme_options[pf_settings_page_slug]',
			'priority' => 60,
			'type'     => 'text'
		));
		
		/* HEADER / FOOTER */
		
		// CONTACT FB
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_facebook]', array(
			'default'           => BT_Customize_Default::$contact_facebook,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_facebook', array(
			'label'    => __( 'Facebook', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_facebook]',
			'priority' => 40,
			'type'     => 'text'
		));

		// CONTACT TWITTER
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_twitter]', array(
			'default'           => BT_Customize_Default::$contact_twitter,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_twitter', array(
			'label'    => __( 'Twitter', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_twitter]',
			'priority' => 50,
			'type'     => 'text'
		));

		// CONTACT LINKEDIN
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_linkedin]', array(
			'default'           => BT_Customize_Default::$contact_linkedin,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_linkedin', array(
			'label'    => __( 'LinkedIn', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_linkedin]',
			'priority' => 70,
			'type'     => 'text'
		));
		
		// CONTACT YOUTUBE
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_youtube]', array(
			'default'           => BT_Customize_Default::$contact_youtube,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_youtube', array(
			'label'    => __( 'YouTube', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_youtube]',
			'priority' => 100,
			'type'     => 'text'
		));
		
		// CONTACT INSTAGRAM
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_instagram]', array(
			'default'           => BT_Customize_Default::$contact_instagram,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_instagram', array(
			'label'    => __( 'Instagram', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_instagram]',
			'priority' => 101,
			'type'     => 'text'
		));		
		
		// CONTACT PHONE
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_phone]', array(
			'default'           => BT_Customize_Default::$contact_phone,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_phone', array(
			'label'    => __( 'Phone', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_phone]',
			'priority' => 106,
			'type'     => 'text'
		));		
		
		// CONTACT EMAIL
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_email]', array(
			'default'           => BT_Customize_Default::$contact_email,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_email', array(
			'label'    => __( 'Email', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_email]',
			'priority' => 107,
			'type'     => 'text'
		));
		
		// WORK TIME
		$wp_customize->add_setting( BTPFX . '_theme_options[work_time]', array(
			'default'           => BT_Customize_Default::$work_time,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'work_time', array(
			'label'    => __( 'Work Time', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[work_time]',
			'priority' => 108,
			'type'     => 'text'
		));	

		// CONTACT ADDRESS
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_address]', array(
				'default'           => BT_Customize_Default::$contact_address,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( new BT_Customize_Textarea_Control(
				$wp_customize,
				'contact_address', array(
					'label'    => __( 'Address', 'bt_theme' ),
					'section'  => BTPFX . '_footer_section',
					'settings' => BTPFX . '_theme_options[contact_address]',
					'priority' => 109,
				)
		));		
		
		// CONTACT PAGE SLUG
		$wp_customize->add_setting( BTPFX . '_theme_options[contact_page_slug]', array(
			'default'           => BT_Customize_Default::$contact_page_slug,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'contact_page_slug', array(
			'label'    => __( 'Contact Page Slug', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[contact_page_slug]',
			'priority' => 115,
			'type'     => 'text'
		));

		// HEADER LINK TEXT 1
		$wp_customize->add_setting( BTPFX . '_theme_options[header_link_text_1]', array(
			'default'           => BT_Customize_Default::$header_link_text_1,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'header_link_text_1', array(
			'label'    => __( 'Header Link Text 1', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[header_link_text_1]',
			'priority' => 122,
			'type'     => 'text'
		));

		// HEADER LINK SLUG 1
		$wp_customize->add_setting( BTPFX . '_theme_options[header_link_slug_1]', array(
			'default'           => BT_Customize_Default::$header_link_slug_1,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'header_link_slug_1', array(
			'label'    => __( 'Header Link Page Slug 1', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[header_link_slug_1]',
			'priority' => 123,
			'type'     => 'text'
		));
		
		// HEADER LINK ICON 1
		$icon_arr = bt_fa_icons();
		$wp_customize->add_setting( BTPFX . '_theme_options[header_link_icon_1]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'header_link_icon_1', array(
			'label'    => __( 'Header Link Icon 1', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[header_link_icon_1]',
			'priority' => 124,
			'type'     => 'select',
			'choices'  => array_merge( array( '' => '' ), array_flip( $icon_arr ) )
		));

		// HEADER LINK TEXT 2
		$wp_customize->add_setting( BTPFX . '_theme_options[header_link_text_2]', array(
			'default'           => BT_Customize_Default::$header_link_text_2,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'header_link_text_2', array(
			'label'    => __( 'Header Link Text 2', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[header_link_text_2]',
			'priority' => 125,
			'type'     => 'text'
		));
		
		// HEADER LINK SLUG 2
		$wp_customize->add_setting( BTPFX . '_theme_options[header_link_slug_2]', array(
			'default'           => BT_Customize_Default::$header_link_slug_2,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'header_link_slug_2', array(
			'label'    => __( 'Header Link Page Slug 2', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[header_link_slug_2]',
			'priority' => 126,
			'type'     => 'text'
		));
		
		// HEADER LINK ICON 2
		$icon_arr = bt_fa_icons();
		$wp_customize->add_setting( BTPFX . '_theme_options[header_link_icon_2]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'header_link_icon_2', array(
			'label'    => __( 'Header Link Icon 2', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[header_link_icon_2]',
			'priority' => 127,
			'type'     => 'select',
			'choices'  => array_merge( array( '' => '' ), array_flip( $icon_arr ) )
		));
		
		// CUSTOM TEXT
		$wp_customize->add_setting( BTPFX . '_theme_options[custom_text]', array(
			'default'           => BT_Customize_Default::$custom_text,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'custom_text', array(
			'label'    => __( 'Custom Footer Text', 'bt_theme' ),
			'section'  => BTPFX . '_footer_section',
			'settings' => BTPFX . '_theme_options[custom_text]',
			'priority' => 130,
			'type'     => 'text'
		));		
	}
}
add_action( 'customize_register', 'bt_customize_register' );

if ( ! function_exists( 'bt_js_bottom' ) ) {
	function bt_js_bottom() {
		$j = bt_get_option( 'custom_js_bottom' );
		echo '<script>' . wp_kses_post( $j ) . '</script>';
	}
}

if ( ! function_exists( 'bt_customize_css_js' ) ) {
	function bt_customize_css_js() {

		echo '<style>';
		
		if ( bt_get_option( 'custom_css' ) != '' ) {
			echo bt_get_option( 'custom_css' );
		}
		
		echo '</style>';
		
		if ( bt_get_option( 'custom_js_top' ) != '' ) {
			$j = bt_get_option( 'custom_js_top' );
			echo '<script>' . wp_kses_post( $j ) . '</script>';
		}

		if ( bt_get_option( 'custom_js_bottom' ) != '' ) {
			add_action( 'wp_footer', 'bt_js_bottom' );
		}
		
	}
}
add_action( 'wp_head', 'bt_customize_css_js' );

function bt_custom_text( $text ) {
	return $text;
}

function bt_custom_js( $js ) {
	return trim( $js );
}