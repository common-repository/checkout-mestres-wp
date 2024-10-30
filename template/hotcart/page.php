<?php
get_header();
while ( have_posts() ) :
	the_post();
	echo "<div class='content_checkout_mwp'>";
	echo do_shortcode('[woocommerce_checkout]');
	echo "</div>";
endwhile;
get_footer();
