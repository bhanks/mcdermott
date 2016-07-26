<?php

get_header();

the_post();

the_content();

if ( comments_open() || get_comments_number() ) {
	echo '<section class="boldSection bt_comments">';
		echo '<div class="port">';
			comments_template();
		echo '</div>';
	echo '</section>';
}

get_footer();