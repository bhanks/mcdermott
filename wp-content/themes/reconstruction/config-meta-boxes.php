<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'bt_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
if ( ! function_exists( 'bt_register_meta_boxes' ) ) {
	function bt_register_meta_boxes( $meta_boxes ) {
		/**
		 * Prefix of meta keys (optional)
		 * Use underscore (_) at the beginning to make keys hidden
		 * Alt.: You also can make prefix empty to disable it
		 */
		// Better has an underscore as last sign
		
		$prefix = BTPFX . '_';
		
		// PAGE
		$meta_boxes[] = array(
			// Meta box id, UNIQUE per meta box. Optional since 4.1.5
			'id' => 'bt_meta_settings',

			// Meta box title - Will appear at the drag and drop handle bar. Required.
			'title' => __( 'Settings', 'bt_theme' ),

			// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
			'pages' => array( 'page' ),

			// Where the meta box appear: normal (default), advanced, side. Optional.
			'context' => 'normal',

			// Order of meta box: high (default), low. Optional.
			'priority' => 'high',

			// Auto save: true, false (default). Optional.
			'autosave' => true,

			// List of meta fields
			'fields' => array(
				array(
					'name' => __( 'Menu Name', 'bt_theme' ),
					'id'   => "{$prefix}menu_name",
					'type' => 'text'
				),
				array(
					// Field name - Will be used as label
					'name'  => __( 'Override Global Settings', 'bt_theme' ),
					// Field ID, i.e. the meta key
					'id'    => "{$prefix}override",
					// Field description (optional)
					'desc'  => '',
					'type'  => 'bttext',
					// CLONES: Add to make the field cloneable (i.e. have multiple value)
					'clone' => true,
				)		
			)
		);
		
		// BLOG
		$meta_boxes[] = array(
			// Meta box id, UNIQUE per meta box. Optional since 4.1.5
			'id' => 'bt_meta_blog_settings',

			// Meta box title - Will appear at the drag and drop handle bar. Required.
			'title' => __( 'Settings', 'bt_theme' ),

			// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
			'pages' => array( 'post' ),

			// Where the meta box appear: normal (default), advanced, side. Optional.
			'context' => 'normal',

			// Order of meta box: high (default), low. Optional.
			'priority' => 'high',

			// Auto save: true, false (default). Optional.
			'autosave' => true,

			// List of meta fields
			'fields' => array(
				array(
					'name' => __( 'Meta Description', 'bt_theme' ),
					'id'   => "{$prefix}description",
					'type' => 'textarea'
				),
				array(
					'name' => __( 'Images', 'bt_theme' ),
					'id'   => "{$prefix}images",
					'type' => 'image_advanced'
				),
				array(
					'name' => __( 'Grid Gallery', 'bt_theme' ),
					'id'   => "{$prefix}grid_gallery",
					'type' => 'checkbox'
				),
				array(
					'name'  => __( 'Grid Gallery Format', 'bt_theme' ),
					'id'    => "{$prefix}grid_gallery_format",
					'type'  => 'text'
				),
				array(
					'name'  => __( 'Video', 'bt_theme' ),
					'id'    => "{$prefix}video",
					'type'  => 'text'
				),
				array(
					'name'  => __( 'Audio', 'bt_theme' ),
					'id'    => "{$prefix}audio",
					'type'  => 'text'
				),
				array(
					'name'  => __( 'Link', 'bt_theme' ),
					'id'    => "{$prefix}link_title",
					'type'  => 'text'
				),
				array(
					'name'  => __( 'Link URL', 'bt_theme' ),
					'id'    => "{$prefix}link_url",
					'type'  => 'text'
				),				
				array(
					'name'  => __( 'Quote', 'bt_theme' ),
					'id'    => "{$prefix}quote",
					'type'  => 'text'
				),
				array(
					'name'  => __( 'Quote Author', 'bt_theme' ),
					'id'    => "{$prefix}quote_author",
					'type'  => 'text'
				),
				array(
					'name' => __( 'Tile Format', 'bt_theme' ),
					'id'   => "{$prefix}tile_format",
					'type' => 'select',
					'options' => array( '11' => '1:1', '21' => '2:1', '22' => '2:2', '12' => '1:2' )
				),			
				array(
					// Field name - Will be used as label
					'name'  => __( 'Override Global Settings', 'bt_theme' ),
					// Field ID, i.e. the meta key
					'id'    => "{$prefix}override",
					// Field description (optional)
					'desc'  => '',
					'type'  => 'bttext',
					// CLONES: Add to make the field cloneable (i.e. have multiple value)
					'clone' => true,
				)
			)
		);
		
		// PORTFOLIO
		$meta_boxes[] = array(
			// Meta box id, UNIQUE per meta box. Optional since 4.1.5
			'id' => 'bt_meta_portfolio_settings',

			// Meta box title - Will appear at the drag and drop handle bar. Required.
			'title' => __( 'Settings', 'bt_theme' ),

			// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
			'pages' => array( 'portfolio' ),

			// Where the meta box appear: normal (default), advanced, side. Optional.
			'context' => 'normal',

			// Order of meta box: high (default), low. Optional.
			'priority' => 'high',

			// Auto save: true, false (default). Optional.
			'autosave' => true,

			// List of meta fields
			'fields' => array(
				array(
					'name'  => __( 'Subheading', 'bt_theme' ),
					'id'    => "{$prefix}subheading",
					'type'  => 'text'
				),			
				array(
					'name' => __( 'Meta Description', 'bt_theme' ),
					'id'   => "{$prefix}description",
					'type' => 'textarea'
				),
				array(
					'name' => __( 'Images', 'bt_theme' ),
					'id'   => "{$prefix}images",
					'type' => 'image_advanced'
				),
				array(
					'name' => __( 'Grid Gallery', 'bt_theme' ),
					'id'   => "{$prefix}grid_gallery",
					'type' => 'checkbox'
				),
				array(
					'name'  => __( 'Grid Gallery Format', 'bt_theme' ),
					'id'    => "{$prefix}grid_gallery_format",
					'type'  => 'text'
				),			
				array(
					'name'  => __( 'Video', 'bt_theme' ),
					'id'    => "{$prefix}video",
					'type'  => 'text'
				),
				array(
					'name'  => __( 'Audio', 'bt_theme' ),
					'id'    => "{$prefix}audio",
					'type'  => 'text'
				),
				array(
					'name' => __( 'Tile Format', 'bt_theme' ),
					'id'   => "{$prefix}tile_format",
					'type' => 'select',
					'options' => array( '11' => '1:1', '21' => '2:1', '22' => '2:2', '12' => '1:2' )
				),
				array(
					// Field name - Will be used as label
					'name'  => __( 'Custom Fields', 'bt_theme' ),
					// Field ID, i.e. the meta key
					'id'    => "{$prefix}custom_fields",
					// Field description (optional)
					'desc'  => '',
					'type'  => 'bttext1',
					// CLONES: Add to make the field cloneable (i.e. have multiple value)
					'clone' => true,
				),		
				array(
					// Field name - Will be used as label
					'name'  => __( 'Override Global Settings', 'bt_theme' ),
					// Field ID, i.e. the meta key
					'id'    => "{$prefix}override",
					// Field description (optional)
					'desc'  => '',
					'type'  => 'bttext',
					// CLONES: Add to make the field cloneable (i.e. have multiple value)
					'clone' => true,
				)
			)
		);		

		return $meta_boxes;
	}
}