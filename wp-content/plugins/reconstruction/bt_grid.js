(function( $ ) {

	window.bt_no_posts = false;
	window.bt_loading_grid = false;
	
	//window.bt_current_num = 0;
	
	window.bt_ajax_req = [];

	$( document ).ready(function() {
	
		$( '.btGridContainer' ).height( window.innerHeight );
	
		$( '.tilesWall' ).each(function() {
			window.bt_scroll_loading = $( this ).data( 'scroll-loading' ) == 'yes' ? true : false;
			window.bt_grid_type = $( this ).data( 'grid-type' );
			window.bt_tiled_format = $( this ).data( 'format' ) + '';
			if ( window.bt_tiled_format != '' ) {
				window.bt_tiled_format = window.bt_tiled_format.split( ',' );
			} else {
				window.bt_tiled_format = [];
			}
			var $c = $( this );
			$c.imagesLoaded(function() {
				$c.packery({
					itemSelector: '.gridItem',
					columnWidth: '.gridSizer',
					gutter: 0,
					percentPosition: true,
					transitionDuration: 0,
					isResizeBound: false
				});

				bt_load_posts( document.querySelector( '.tilesWall' ) );
				
			});
		});
		
		$( '.btCatFilterItem.all' ).addClass( 'active' );
		
		$( '.btCatFilterItem' ).click(function() {
			$( '.btCatFilterItem' ).removeClass( 'active' );
			$( this ).addClass( 'active' );
			$( '.tilesWall' ).data( 'cat-slug', $( this ).data( 'slug' ) );
			for ( var n = 0; n < window.bt_ajax_req.length; n++ ) {
				window.bt_ajax_req[ n ].abort();
			}
			window.bt_ajax_req = [];
			
			$( '.btGridContainer' ).height( $( '.btGridContainer' ).height() );
			
			var $container = $( '.tilesWall' ).packery();
			$container.packery( 'remove', $( '.gridItem' ) );
			$container.packery();
			//$( '.gridItem' ).remove();
			//bt_packery_tweak();
			window.bt_grid_offset = 0;
			//window.bt_current_num = 0;
			window.bt_no_posts = false;
			$( '.bt_no_more' ).hide();
			$( '.bt_loader_grid' ).show();
			bt_load_posts( document.querySelector( '.tilesWall' ) );
		});
		
	});
	
	$( window ).resize(function() {
		$( '.tilesWall' ).each(function() {
			bt_packery_tweak();
			setTimeout(function() {
				bt_packery_tweak();
			}, 150 );
		});
	});
	
	$( window ).scroll(function() {
		if ( bt_is_load_scroll() && window.bt_scroll_loading && ! window.bt_no_posts && ! window.bt_loading_grid ) {
			window.bt_loading_grid = true;
			bt_load_posts( document.querySelector( '.tilesWall' ) );
		}
	});

	var bt_is_load_scroll = function() {
		if ( $( window ).scrollTop() + $( window ).height() >= $( document ).height() - 400 ) {
			return true;
		}
		return false;
	}

	// ajax loader
	
	window.bt_ajax_elems_all = [];
	
	var bt_load_posts = function( target ) {
		if ( typeof window.bt_grid_offset === 'undefined' ) window.bt_grid_offset = 0;
		var num = $( target ).data( 'num' );
		var data = {
			'action': 'bt_get_grid',
			'number': num,
			'offset': window.bt_grid_offset,
			'cat_slug': $( target ).data( 'cat-slug' ),
			'post_type': $( target ).data( 'post-type' ),
			'grid_type': $( target ).data( 'grid-type' ),
			'tiles_title': $( target ).data( 'tiles-title' ),
			'format': window.bt_tiled_format.slice( window.bt_grid_offset, window.bt_grid_offset + num ).join()
		};
		//window.bt_current_num++;
		window.bt_grid_offset = window.bt_grid_offset + num;
		$( '.bt_loader_grid' ).css( 'opacity', '1' );
		window.bt_ajax_req.push($.ajax({
			type: 'POST',
			url: window.BTAJAXURL,
			data: data,
			async: true,
			success: function( response ) {
			
				var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
				
				if ( ! window.bt_scroll_loading ) $( '.bt_loader_grid' ).hide();

				if ( response.indexOf( 'no_posts' ) == 0 ) {
					$( '.bt_loader_grid' ).css( 'opacity', '0' );
					$( '.bt_loader_grid' ).hide();
					if ( window.bt_scroll_loading ) $( '.bt_no_more' ).fadeIn();
					window.bt_no_posts = true;
					return;
				}
				
				$post = JSON.parse( response );

				window.bt_ajax_elems = [];
				
				$( '.bt_loader_grid' ).css( 'opacity', '0' );

				for ( var i = 0; i < $post.length; i++ ) {
					var elem = document.createElement( 'div' );
					elem.className = $post[ i ].container_class;

					$( elem ).append( $post[ i ].html );
					
					window.bt_ajax_elems.push( elem );
					window.bt_ajax_elems_all.push( elem );
				}

				for ( var i = 0; i < window.bt_ajax_elems.length; i++ ) {
					
					$( window.bt_ajax_elems[i] ).attr( 'data-i', i );

					$( target ).append( window.bt_ajax_elems[ i ] );
					
					if ( window.bt_grid_type == 'classic' ) {
						$( '.boldPhotoBox' ).each(function() {
							if ( $( this ).attr( 'data-hw' ) != undefined ) {
								$( this ).height( $( this ).outerWidth( true ) * $( this ).attr( 'data-hw' ) );
							}
						});
					}
					
					var $container = $( '.tilesWall' ).packery();
					$container.packery( 'appended', window.bt_ajax_elems[ i ] );
					
					bt_packery_tweak();

					$( '.boldPhotoSlide' ).slick({
						dots: false,
						arrows: true,
						infinite: false,
						speed: 300,
						slide: '.bpbItem',
						slidesToShow: 1,
						nextArrow: '<button type="button" class="slick-next"></button>',
						prevArrow: '<button type="button" class="slick-prev"></button>'
					});
					
					imagesLoaded( window.bt_ajax_elems[ i ], function() {
						var i = $( this.elements[0] ).attr( 'data-i' );
						$( window.bt_ajax_elems[ i ] ).css( { 'transition-delay': i * .1 + 's', 'transition-duration': '.4s', 'transition-property': 'opacity', 'opacity': '1' } );
					});
				}		

				setTimeout(function() {
					for ( var i = 0; i < window.bt_ajax_elems_all.length; i++ ) {
						$( window.bt_ajax_elems_all[ i ] ).css( { 'transition-delay': i * .1 + 's', 'transition-duration': '.4s', 'transition-property': 'opacity', 'opacity': '1' } );
					}
				}, 5000 );
				
				window.bt_loading_grid = false;
				
				$( '.btGridContainer' ).css( 'height', 'auto' );

				if ( bt_is_load_scroll() && window.bt_scroll_loading && ! window.bt_no_posts ) {
					bt_load_posts( document.querySelector( '.tilesWall' ) );
				}

			},
			error: function( xhr, status, error ) {
				$( '.bt_loader_grid' ).css( 'opacity', '0' );
			}
			
		}));
	}

})( jQuery );