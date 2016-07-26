(function( $ ) {
	
	window.bt_packery_tweak = function() {
		$( '.tilesWall' ).each(function() {
			if ( window.bt_grid_type == 'classic' ) {
				bt_packery_tweak1();
				$( this ).packery();
				bt_packery_tweak3();
				bt_packery_tweak1();
				$( this ).packery();
				bt_packery_tweak3();
			} else {
				bt_packery_ttweak1();
				$( this ).packery();
				bt_packery_ttweak3();
			}		
		});
	}
	
	var bt_packery_tweak1 = function() {
	
		$( '.boldPhotoBox' ).each(function() {
			if ( $( this ).attr( 'data-hw' ) != undefined ) {
				$( this ).height( $( this ).outerWidth() * $( this ).attr( 'data-hw' ) );
			}
		});
		
		$( '.tilesWall' ).each(function() {
			var container_w = $( this ).width();
			var col = $( this ).data( 'col' );
			var grid_sizer_w = $( this ).find( '.gridSizer' ).width();
			if ( container_w < 481 ) {
				$( this ).find( '.gridSizer, .gridItem' ).each(function() {
					$( this ).css( 'width', '100%' );
					if ( grid_sizer_w > 0 ) {
						var new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );	
					}					
				});
			} else if ( container_w < 769 ) {
				$( this ).find( '.gridSizer, .gridItem' ).each(function() {
					$( this ).css( 'width', '50%' );
					if ( grid_sizer_w > 0 ) {
						var new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );
					}					
				});
			} else if ( container_w < 1150 ) {
				$( this ).find( '.gridSizer, .gridItem' ).each(function() {
					$( this ).css( 'width', '33.333333333%' );
					if ( grid_sizer_w > 0 ) {
						var new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );	
					}					
				});
			} else {
				$( this ).find( '.gridSizer, .gridItem' ).each(function() {
					if ( col == '3' ) {
						$( this ).css( 'width', '33.333333333%' );
					} else if ( col == '4' ) {
						$( this ).css( 'width', '25%' );
					} else if ( col == '5' ) {
						$( this ).css( 'width', '20%' );
					} else if ( col == '6' ) {
						$( this ).css( 'width', '16.666666667%' );
					}
					if ( grid_sizer_w > 0 ) {
						var new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );	
					}					
				});
			}
		});
	}
	
	var bt_packery_tweak2 = function() {
		$( '.tilesWall' ).each(function() {
			var container_w = $( this ).width();
			var grid_sizer_w = $( this ).find( '.gridSizer' ).width();
			$( this ).find( '.gridItem' ).each(function() {
				var l = $( this ).css( 'left' );
				var m = parseFloat( l ) / grid_sizer_w;
				$( this ).css( 'left', Math.round( m ) * Math.floor( grid_sizer_w ) );
				
				var w = $( this ).css( 'width' );
				m = parseFloat( w ) / grid_sizer_w;
				$( this ).width( Math.round( m ) * Math.floor( grid_sizer_w ) );
			});
		});
	}
	
	var bt_packery_tweak3 = function() {
		$( '.tilesWall' ).each(function() {
			var container_w = $( this ).width();
			$( this ).find( '.gridItem' ).each(function() {
				var l = $( this ).css( 'left' );
				var w = $( this ).width();
				var d = container_w - ( parseInt( l ) + w );
				if ( Math.abs( d ) < 15 ) {
					$( this ).width( parseInt( w ) + d );
				}
			});
		});
	}
	
	var bt_packery_ttweak1 = function() {
		$( '.tilesWall' ).each(function() {
			var container_w = $( this ).width();
			var col = $( this ).data( 'col' );
			var grid_sizer_w = $( this ).find( '.gridSizer' ).width();
			var new_w = 0;
			if ( container_w < 481 ) {
				$( this ).find( '.gridSizer, .gridItem.bt11, .gridItem.bt12' ).each(function() {
					$( this ).css( 'width', '100%' );
					if ( grid_sizer_w > 0 ) {
						new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );	
					}					
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt12' ) ) {
						$( this ).innerHeight( $( this ).width() * 2 );
					}
					if ( $( this ).hasClass( 'gridSizer' ) ) {
						$( this ).innerHeight( 0 );
					}
				});
				
				$( this ).find( '.gridItem.bt21, .gridItem.bt22' ).each(function() {
					$( this ).width( new_w );				
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt21' ) ) {
						$( this ).innerHeight( grid_sizer_w * .5 );
					} else if ( $( this ).hasClass( 'bt22' ) ) {
						$( this ).innerHeight( grid_sizer_w );
					}
 				});
			} else if ( container_w < 769 ) {
				$( this ).find( '.gridSizer, .gridItem.bt11, .gridItem.bt12' ).each(function() {
					$( this ).css( 'width', '50%' );
					if ( grid_sizer_w > 0 ) {
						new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );	
					}					
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt12' ) ) {
						$( this ).innerHeight( $( this ).width() * 2 );
					}
					if ( $( this ).hasClass( 'gridSizer' ) ) {
						$( this ).innerHeight( 0 );
					}
				});

				$( this ).find( '.gridItem.bt21, .gridItem.bt22' ).each(function() {
					$( this ).width( new_w * 2 );					
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt21' ) ) {
						$( this ).innerHeight( grid_sizer_w );
					} else if ( $( this ).hasClass( 'bt22' ) ) {
						$( this ).innerHeight( grid_sizer_w * 2 );
					}
				});
			} else if ( container_w < 1150 ) {
				
				$( this ).find( '.gridSizer, .gridItem.bt11, .gridItem.bt12' ).each(function() {
					$( this ).css( 'width', '33.333333333%' );
					
					if ( grid_sizer_w > 0 ) {
						new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );
					}
					
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt12' ) ) {
						$( this ).innerHeight( $( this ).width() * 2 );
					}
					if ( $( this ).hasClass( 'gridSizer' ) ) {
						$( this ).innerHeight( 0 );
					}
				});

				$( this ).find( '.gridItem.bt21, .gridItem.bt22' ).each(function() {
					$( this ).width( new_w * 2 );
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt21' ) ) {
						$( this ).innerHeight( grid_sizer_w );
					} else if ( $( this ).hasClass( 'bt22' ) ) {
						$( this ).innerHeight( grid_sizer_w * 2 );
					}
				});
			} else {

				$( this ).find( '.gridSizer, .gridItem.bt11, .gridItem.bt12' ).each(function() {
					if ( col == '3' ) {
						$( this ).css( 'width', '33.333333333%' );
					} else if ( col == '4' ) {
						$( this ).css( 'width', '25%' );
					} else if ( col == '5' ) {
						$( this ).css( 'width', '20%' );
					} else if ( col == '6' ) {
						$( this ).css( 'width', '16.666666667%' );
					}

					if ( grid_sizer_w > 0 ) {
						new_w = parseInt( $( this ).css( 'width' ) );
						if ( col * new_w > container_w ) new_w = new_w - 1;
						$( this ).width( new_w );	
					}
					
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt12' ) ) {
						$( this ).innerHeight( $( this ).width() * 2 );
					}
					if ( $( this ).hasClass( 'gridSizer' ) ) {
						$( this ).innerHeight( 0 );
					}
				});
				$( this ).find( '.gridItem.bt21, .gridItem.bt22' ).each(function() {
					$( this ).width( new_w * 2 );
		
					$( this ).innerHeight( $( this ).width() );
					if ( $( this ).hasClass( 'bt21' ) ) {
						$( this ).innerHeight( grid_sizer_w );
					} else if ( $( this ).hasClass( 'bt22' ) ) {
						$( this ).innerHeight( grid_sizer_w * 2 );
					}
					if ( $( this ).hasClass( 'gridSizer' ) ) {
						$( this ).innerHeight( 0 );
					}
				});
			}
		});
	}
	
	var bt_packery_ttweak2 = function() {
		$( '.tilesWall' ).each(function() {
			var container_w = $( this ).width();
			var grid_sizer_w = $( this ).find( '.gridSizer' ).width();
			$( this ).find( '.gridItem' ).each(function() {
				var l = $( this ).css( 'left' );
				var m = parseFloat( l ) / grid_sizer_w;
				$( this ).css( 'left', Math.round( m ) * Math.floor( grid_sizer_w ) );

				if ( container_w > 480 ) {
					var t = $( this ).css( 'top' );
					m = parseFloat( t ) / grid_sizer_w;
					$( this ).css( 'top', Math.round( m ) * Math.floor( grid_sizer_w ) );
				}
				
				var w = $( this ).css( 'width' );
				m = parseFloat( w ) / grid_sizer_w;
				$( this ).width( Math.round( m ) * Math.floor( grid_sizer_w ) );
			});
		});
	}
	
	var bt_packery_ttweak3 = function() {
		$( '.tilesWall' ).each(function() {
			var container_w = $( this ).width();
			$( this ).find( '.gridItem' ).each(function() {
				
				var new_l = parseFloat( $( this ).css( 'left' ) );
				new_l = Math.round( new_l );
				$( this ).css( 'left',  new_l + 'px' );
				
				var w = $( this ).width();
				var d = container_w - ( new_l + w );
				if ( Math.abs( d ) < 15 ) {
					$( this ).width( parseInt( w ) + d );
				}
			});
		});
	}

})( jQuery );