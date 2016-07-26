<?php
add_action( 'init', 'bt_buttons' );
if ( ! function_exists( 'bt_buttons' ) ) {
	function bt_buttons() {
		add_filter( 'mce_external_plugins', 'bt_add_buttons' );
		add_filter( 'mce_buttons_3', 'bt_register_buttons' );
		add_filter( 'mce_external_languages', 'bt_add_tinymce_lang' );
	}
}
if ( ! function_exists( 'bt_add_buttons' ) ) {
	function bt_add_buttons( $plugin_array ) {
		$plugin_array['boldthemes'] = get_template_directory_uri() . '/editor-buttons/editor-buttons-plugin.js';
		return $plugin_array;
	}
}
if ( ! function_exists( 'bt_register_buttons' ) ) {
	function bt_register_buttons( $buttons ) {
		array_push( $buttons, 'drop_cap' );
		array_push( $buttons, 'highlight' );
		return $buttons;
	}
}
if ( ! function_exists( 'bt_add_tinymce_lang' ) ) {
	function bt_add_tinymce_lang( $arr ) {
		$arr['boldthemes'] = get_template_directory() . '/editor-buttons/editor-lang.php';
		return $arr;
	}
}