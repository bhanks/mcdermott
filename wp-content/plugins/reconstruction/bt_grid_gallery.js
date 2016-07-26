(function( $ ) {

	$( document ).ready(function() {
	
		$( '.tilesWall' ).each(function() {
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
				bt_packery_tweak();
				setTimeout(function() {
					bt_packery_tweak();
				}, 150 );				
				$c.find( '.gridItem' ).css( 'opacity', '1' );
			});
		});
	});
	
	$( window ).load(function() {
		$( '.tilesWall' ).each(function() {
			var $c = $( this );
			bt_packery_tweak();
			setTimeout(function() {
				bt_packery_tweak();
			}, 100 );			
		});
	});
	
	$( window ).resize(function() {
		$( '.tilesWall' ).each(function() {
			var $c = $( this );
			bt_packery_tweak();
			setTimeout(function() {
				bt_packery_tweak();
			}, 100 );			
		});
	});

})( jQuery );