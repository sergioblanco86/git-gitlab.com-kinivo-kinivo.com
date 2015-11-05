<?php
/*
Template Name: My Wishlist
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content();?>
			<?php endwhile; else : ?>
				<p>No content.</p>
			<?php endif;?>
			<!-- End Content! -->
			<?php get_footer(); ?>