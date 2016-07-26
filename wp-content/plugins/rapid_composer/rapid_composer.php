<?php

/**
 * Plugin Name: Rapid Composer
 * Description: Efficient visual content builder.
 * Version: 1.0.7
 * Author: bitorbit
 * Author URI: http://codecanyon.net/user/bitorbit
 */
 
 /**
 * Enqueue scripts and styles
 */

function bt_rapid_composer() {
	wp_enqueue_style( 'rc_fa_css', plugins_url() . '/rapid_composer/css/font-awesome.min.css' );
	wp_enqueue_style( 'rc_css', plugins_url() . '/rapid_composer/css/rapid_composer.min.css' );
	wp_enqueue_script( 'rc_react_script', plugins_url() . '/rapid_composer/react.min.js' );
	wp_enqueue_script( 'rc_script', plugins_url() . '/rapid_composer/script.min.js', array( 'jquery' ), true );
	wp_enqueue_script( 'rc_jsx_script', plugins_url() . '/rapid_composer/build/jsx.min.js', array( 'jquery' ), true );
	wp_enqueue_script( 'rc_autosize_script', plugins_url() . '/rapid_composer/autosize.min.js' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'bt_rapid_composer' );

function bt_rc_activate() {
	update_option( 'bt_rc_settings', array( 'tag_as_name' => '0' ) );
}
register_activation_hook( __FILE__, 'bt_rc_activate' );

function bt_rc_deactivate() {
	delete_option( 'bt_rc_settings' );
}
register_deactivation_hook( __FILE__, 'bt_rc_deactivate' );

function bt_rc_admin_init() {
    register_setting( 'bt_rc_settings', 'bt_rc_settings' );
}
add_action( 'admin_init', 'bt_rc_admin_init' );

function rapid_composer_load_plugin_textdomain() {

	$domain = 'rapid_composer';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'rapid_composer_load_plugin_textdomain' );

/**
 * Save mapping
 */

function bt_rc_save_mapping() {
	$base = $_POST['base'];
	$map = $_POST['map'];
	$opt_arr = get_option( 'bt_rc_mapping_secondary' );
	if ( ! is_array( $opt_arr ) ) {
		$opt_arr = array();
	}
	if ( $map != '' ) {
		$opt_arr[ $base ] = $map;
	} else {
		unset( $opt_arr[ $base ] );
	}
	update_option( 'bt_rc_mapping_secondary', $opt_arr );
	echo 'ok';
	die();
}

function bt_rc_delete_mapping() {
	$base = $_POST['base'];
	$opt_arr = get_option( 'bt_rc_mapping_secondary' );
	if ( ! is_array( $opt_arr ) ) {
		$opt_arr = array();
	}
	unset( $opt_arr[ $base ] );
	update_option( 'bt_rc_mapping_secondary', $opt_arr );
	echo 'ok';
	die();
}

add_action( 'wp_ajax_bt_rc_save_mapping', 'bt_rc_save_mapping' );
add_action( 'wp_ajax_nopriv_bt_rc_save_mapping', 'bt_rc_save_mapping' );
add_action( 'wp_ajax_bt_rc_delete_mapping', 'bt_rc_delete_mapping' );
add_action( 'wp_ajax_nopriv_bt_rc_delete_mapping', 'bt_rc_delete_mapping' );

/**
 * Settings menu
 */

function bt_rc_menu() {
	add_options_page( __( 'Rapid Composer Settings', 'rapid_composer' ), __( 'Rapid Composer', 'rapid_composer' ), 'manage_options', 'bt_rc_settings', 'bt_rc_settings' );
}
add_action( 'admin_menu', 'bt_rc_menu' );

/**
 * Settings page
 */
function bt_rc_settings() {
	
	$options = get_option( 'bt_rc_settings' );
	$tag_as_name = $options['tag_as_name'];
	
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );

	?>
		<div class="wrap">
			<h2><?php _e( 'Rapid Composer Settings', 'rapid_composer' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'bt_rc_settings' ); ?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><?php _e( 'Show shortcode tag instead of mapped name', 'rapid_composer' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Show shortcode tags instead of mapped names', 'rapid_composer' ); ?></span></legend>
						<p><label><input name="bt_rc_settings[tag_as_name]" type="radio" value="0" <?php echo $tag_as_name != '1' ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'rapid_composer' ); ?></label><br>
						<label><input name="bt_rc_settings[tag_as_name]" type="radio" value="1" <?php echo $tag_as_name == '1' ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'rapid_composer' ); ?></label></p>
						</fieldset></td>					
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Post Types', 'rapid_composer' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Post Types', 'rapid_composer' ); ?></span></legend>
						<p>
						<?php 
						$n = 0;
						foreach ( $post_types as $pt ) {
							$n++;
							$checked = '';
							if (  ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
								$checked = ' ' . 'checked="checked"';
							}
							echo '<input type="hidden" name="bt_rc_settings[' . $pt . ']" value="0">';
							echo '<label><input name="bt_rc_settings[' . $pt . ']" type="checkbox" value="1"' . $checked . '> ' . $pt . '</label>';
							if ( $n < count( $post_types ) ) echo '<br>';
						} ?>
						</p>
						</fieldset></td>					
					</tr>					
					</tbody>
				</table>

				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save', 'rapid_composer' ); ?>"></p>
			</form>
		</div>
	<?php

}

/**
 * Settings
 */

function bt_rc_js_settings() {
	$options = get_option( 'bt_rc_settings' );
	$tag_as_name = $options['tag_as_name'];
	
	echo '<script>';
	echo 'window.bt_rc_plugins_url = "' . plugins_url() . '";';
	echo 'window.bt_rc_settings = [];';
	echo 'window.bt_rc_settings.tag_as_name = "' . esc_js( $tag_as_name ) . '";';
	
	echo 'window.BTAJAXURL = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";';
	
	global $shortcode_tags;
	$all_sc = $shortcode_tags;
	ksort( $all_sc );
	
	echo 'window.bt_rc.all_sc = ' . bt_json_encode( array_keys( $all_sc ) ) . ';';
	
	echo '</script>';
}
add_action( 'admin_footer', 'bt_rc_js_settings' );

/**
 * Translate
 */

function bt_rc_translate() {
	echo '<script>';
	echo 'window.bt_rc_text = [];';
	echo 'window.bt_rc_text.toggle = "' . __( 'Toggle', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.add = "' . __( 'Add', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.edit = "' . __( 'Edit', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.edit_content = "' . __( 'Edit Content', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.clone = "' . __( 'Clone', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.delete = "' . __( 'Delete', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.layout_error = "' . __( 'Layout error!', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.add_element = "' . __( 'Add Element', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.select_layout = "' . __( 'Select Layout', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.select = "' . __( 'Select', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.submit = "' . __( 'Submit', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.copy = "' . __( 'Copy', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.copy_plus = "' . __( 'Copy +', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.paste = "' . __( 'Paste', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.export = "' . __( 'Export', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.import = "' . __( 'Import', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.not_allowed = "' . __( 'Not allowed!', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.manage_cb = "' . __( 'Manage Clipboard', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.filter = "' . __( 'Filter...', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.sc_mapper = "' . __( 'Shortcode Mapper', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.insert_mapping = "' . __( 'Insert Mapping', 'rapid_composer' ) . '";';
	echo 'window.bt_rc_text.save = "' . __( 'Save', 'rapid_composer' ) . '";';
	
	echo '</script>';
}
add_action( 'admin_footer', 'bt_rc_translate' );
 
/**
 * Map shortcodes
 */
 
global $bt_rc_map;
$bt_rc_map = array();

function bt_rc_map_action() {
	global $bt_rc_map;
	echo '<script>';
		do_action( 'bt_rc_map_action' );
	echo '</script>';
	echo '<script>';
		$opt_arr = get_option( 'bt_rc_mapping_secondary' );
		if ( is_array( $opt_arr ) ) {
			foreach( $opt_arr as $k => $v ) {
				if ( shortcode_exists( $k ) ) {
					echo 'window.bt_rc_map["' . $k . '"] = ' . stripslashes( $v ) . ';';
					$bt_rc_map[ $k ] = json_decode( stripslashes( $v ), true );
					echo 'window.bt_rc_map_secondary["' . $k . '"] = true;';
				}
			}
		}
	echo '</script>';
}
add_action( 'admin_head', 'bt_rc_map_action' ); 
 
function bt_rc_map( $base, $params, $priority = 10 ) {
	$proxy = new BT_RC_Map_Proxy( $base, $params );
	add_action( 'bt_rc_map_action', array( $proxy, 'js_map' ), $priority );
}

global $bt_rc_root_base;
$bt_rc_root_base = array();

class BT_RC_Map_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$params['base'] = $base;
		$this->params = $params;

		global $bt_rc_root_base;
		if ( isset( $params['root'] ) && $params['root'] === true && isset( $params['base'] ) ) {
			$bt_rc_root_base[] = $params['base'];
		}
	}

	public function js_map() {
		global $bt_rc_map;
		if ( shortcode_exists( $this->base ) ) {
			echo 'window.bt_rc_map["' . $this->base . '"] = window.bt_rc_map_primary.' . $this->base . ' = ' . bt_json_encode( $this->params ) . ';';
			$bt_rc_map[ $this->base ] = $this->params;
		}
	}
}

/**
 * Remove wpautop
 */
function bt_rc_wpautop( $content ) {
	global $bt_rc_root_base;
	$rc_content = false;
	foreach ( $bt_rc_root_base as $item ) {
		if ( strpos( $content, '[' . $item ) === 0 ) {
			$rc_content = true;
		}
	}
	if ( $rc_content ) {
		remove_filter( 'the_content', 'wpautop' );
	}	
	return $content;
}
add_filter( 'the_content', 'bt_rc_wpautop', 1 );

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function rapid_composer_add_meta_box() {

	$options = get_option( 'bt_rc_settings' );
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach ( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}

	$screens = $active_pt;

	foreach ( $screens as $screen ) {
		add_meta_box(
			'rapid_composer_sectionid',
			__( 'Rapid Composer', 'rapid_composer' ),
			'rapid_composer_meta_box_callback',
			$screen,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'rapid_composer_add_meta_box' );

/**
 * Initial data.
 */
class BT_RC_Data_Proxy {
	function __construct( $data ) {
		$this->data = $data;
	}

	public function js() {
		echo '<script>
			var bt_rc_data = {	
				title: "_root",
				base: "_root",
				key: "' . uniqid( 'bt_rc_' ) . '",
				children: ' . $this->data . '
			}
		</script>';
	}
}

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function rapid_composer_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'rapid_composer_meta_box', 'rapid_composer_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	$content = $post->post_content;

	global $bt_rc_array;
	$bt_rc_array = array();

	bt_rc_do_shortcode( $content );

	$json_content = bt_json_encode( $bt_rc_array );

	echo '<div id="bt_rc"></div><div id="bt_rc_add_root"><i class="fa fa-plus-square"></i></div>';
	
	echo '<div id="bt_rc_dialog">';
		echo '<div class="bt_rc_dialog_header"><div class="bt_rc_dialog_close"><i class="fa fa-close"></i></div><span></span></div>';
		echo '<div class="bt_rc_dialog_header_tools"></div>';
		echo '<div class="bt_rc_dialog_content">';
		echo '</div>';
		echo '<div class="bt_rc_dialog_tinymce">';
			echo '<div class="bt_rc_dialog_tinymce_editor_container">';
				wp_editor( '' , 'bt_rc_tinymce', array( 'textarea_rows' => 12 ) );
			echo '</div>';
			echo '<input type="button" class="bt_rc_dialog_button bt_rc_edit button button-small" value="' . __( 'Submit', 'rapid_composer' ) . '">';
		echo '</div>';
	echo '</div>';
	
	echo '<div id="bt_rc_main_toolbar">';
	echo '<i class="fa fa-undo" title="' . __( 'Undo', 'rapid_composer' ) . '"></i>';
	echo '<i class="fa fa-repeat" title="' . __( 'Redo', 'rapid_composer' ) . '"></i>';
		echo '<span class="bt_rc_separator">|</span>';
	echo '<i class="fa fa-paste" title="' . __( 'Paste', 'rapid_composer' ) . '"></i>';
	echo '<span class="bt_rc_cb_items"></span>';
	echo '<i class="fa fa-exchange" title="' . __( 'Clipboard Manager', 'rapid_composer' ) . '"></i>';
		echo '<span class="bt_rc_separator">|</span>';
	echo '<i class="fa bt_rc_sc_mapper" title="' . __( 'Shortcode Mapper', 'rapid_composer' ) . '">[...]</i>';
		echo '<span class="bt_rc_separator">|</span>';
	echo '<i class="fa fa-binoculars" title="' . __( 'Preview', 'rapid_composer' ) . '"></i>';
	echo '<i class="fa fa-upload" title="' . __( 'Save', 'rapid_composer' ) . '"></i>';
	echo '</div>';

	add_action( 'admin_footer', array( new BT_RC_Data_Proxy( $json_content ), 'js' ) );

}

function bt_rc_do_shortcode( $content ) {
	global $shortcode_tags;
	if ( ! ( (  empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) ) ) {
		$pattern = get_shortcode_regex();
		
		global $bt_rc_array;
		
		$callback = new BT_RC_Callback( $bt_rc_array );
		
		$preg_cb = preg_replace_callback( "/$pattern/s", array( $callback, 'bt_rc_do_shortcode_tag' ), $content );
	}
}

class BT_RC_Callback {

	private $bt_rc_array;

    function __construct( &$bt_rc_array ) {
        $this->bt_rc_array = &$bt_rc_array;
    }

	function bt_rc_do_shortcode_tag( $m ) {
		global $shortcode_tags;
		
		global $bt_rc_map;

		// allow [[foo]] syntax for escaping a tag
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return $m[0];
		}

		$tag = $m[2];
		$attr = shortcode_parse_atts( $m[3] );

		if ( is_array( $attr ) ) {
			$this->bt_rc_array[] = array( 'title' => $tag, 'base' => $tag, 'key' => str_replace( '.', '', uniqid( 'bt_rc_', true ) ), 'attr' => bt_json_encode( $attr ), 'children' => array() );
		} else {
			$this->bt_rc_array[] = array( 'title' => $tag, 'base' => $tag, 'key' => str_replace( '.', '', uniqid( 'bt_rc_', true ) ), 'children' => array() );
		}

		if ( isset( $m[5] ) && $m[5] != '' ) {
			// enclosing tag - extra parameter
			$pattern = get_shortcode_regex();
			
			if ( isset( $bt_rc_map[$m[2]]['accept']['_content'] ) && $bt_rc_map[ $m[2] ]['accept']['_content'] ) {
				$r = $m[5];
			} else {
				$callback = new BT_RC_Callback( $this->bt_rc_array[ count( $this->bt_rc_array ) - 1 ]['children'] );
				$r = preg_replace_callback( "/$pattern/s", array( $callback, 'bt_rc_do_shortcode_tag' ), $m[5] );
				$r = trim( $r );
			}
		
			if ( $r != '' ) {
				$this->bt_rc_array[ count( $this->bt_rc_array ) - 1 ]['children'][0] = array( 'title' => '_content', 'base' => '_content', 'content' => $r, 'children' => array() );
			}
		}
	}
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function rapid_composer_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['rapid_composer_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['rapid_composer_meta_box_nonce'], 'rapid_composer_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['rapid_composer_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['rapid_composer_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_my_meta_value_key', $my_data );
}
add_action( 'save_post', 'rapid_composer_save_meta_box_data' );

require_once 'JSON.php';

function bt_json_encode($arg)
{
	global $services_json;
	if (!isset($services_json)) {
		$services_json = new BT_Services_JSON();
	}
	return $services_json->encode($arg);
}