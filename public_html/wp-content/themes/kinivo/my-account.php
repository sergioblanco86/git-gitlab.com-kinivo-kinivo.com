<?php
/*
Template Name: My Account
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			<?php $GLOBALS['bgimg'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content();?>
			<?php endwhile; else : ?>
				<p>No content.</p>
			<?php endif;?>
			<!-- End Content! -->
			<?php get_footer(); ?>