		</div><!-- /bt_content -->
<?php

global $bt_has_sidebar;

if ( $bt_has_sidebar ) {
	echo '<aside class="bt_sidebar">';
		dynamic_sidebar( 'primary_widget_area' );
	echo '</aside>';					
}

?> 
	</div><!-- /contentHolder -->
</div><!-- /contentWrap -->

<?php

$custom_text_html = '';
$custom_text = bt_get_option( 'custom_text' );
if ( $custom_text != '' ) {
	$custom_text_html = '<p class="copyLine">' . $custom_text . '</p>';
}

if ( is_active_sidebar( 'footer_widgets' ) ) {
	echo '
	<section class="boldSection boldSiteFooterWidgets gutter">
		<div class="port">
			<div class="boldRow">';
			dynamic_sidebar( 'footer_widgets' );
	echo '	
			</div>
		</div>
	</section>';
} 

?>

<footer class="boldSiteFooter gutter">
	<div class="port">
		<div class="copy">
			<?php echo wp_kses_post( $custom_text_html ); ?>
			<div class="btFooterMenu">
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'ul', 'depth' => 1, 'fallback_cb' => false ) ); ?>
			</div>
		</div><!-- /copy -->
	</div><!-- /port -->
</footer>

</div><!-- /pageWrap -->

<div role="search" class="ssPort">
	<span class="closeSearch" id="btCloseSearch"></span>
	<form method="get" action="<?php echo home_url(); ?>">
		<input type="text" value="<?php _e( 'Search term...', 'bt_theme' ); ?>" name="s" class="untouched">
	</form>
</div><!-- /ssPort -->

<?php 

wp_footer();

?>
</body>
</html>