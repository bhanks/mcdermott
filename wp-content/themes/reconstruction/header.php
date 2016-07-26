<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>

    <title><?php wp_title( '' ); ?></title>
	
	<?php
	
	bt_set_override();
	bt_header_init();
	bt_header_meta();

	wp_head();
	
	?>
	
</head>

<body <?php 

$body_class_arr = array();
$body_class_arr[] = 'bodyPreloader';

$detect = new Mobile_Detect;
if ( $detect->isMobile() || $detect->isTablet() ) {
	$body_class_arr[] = 'btIsMobile';
}

body_class( $body_class_arr ); 

?>>
<?php if ( ! bt_get_option( 'disable_preloader' ) ) { ?>
	<div id="boldPreloader" class="fullScreen">
		<div class="animation">
			<div><?php bt_logo( 'preloader' ); ?></div>
			<div class="bt_loader"></div>
			<p><?php echo bt_get_option( 'preloader_text' ); ?></p>
		</div>
	</div><!-- /.preloader -->  
<?php } ?>

<div class="<?php echo implode( ' ', bt_get_header_class() ); ?>" id="top">
	
    <header class="mainHeader divider">
        <div class="topBar<?php bt_header_menu_class(); ?>">
            <div class="topBarPort port">
                <div class="topTools ttLeft">
                    <?php  echo bt_get_header_left(); ?>
                </div><!-- /.topTools -->
                <div class="topTools ttRight">
                    <?php echo bt_get_header_right(); ?>
                </div><!-- /.topTools -->
            </div><!-- /topBarPort -->
        </div><!-- /topBar -->
		<div class="menuHolder<?php bt_header_menu_class(); ?>">
			<div class="port">
				<span class="menuTrigger"></span>
                <div class="logo">
                    <span>
                        <?php bt_logo( 'header' ); ?>
                    </span>
                </div><!-- /logo -->
                <div class="menuPort">
                    <nav>
                        <ul>
                            <?php bt_nav_menu(); ?>
                        </ul>
                    </nav>
                </div><!-- .menuPort -->
            </div><!-- /port -->
		</div>
		
    </header><!-- /.mainHeader -->
	<div class="contentWrap">
		<div class="contentHolder">
			<div class="bt_content">
			<?php bt_header_headline() ?>