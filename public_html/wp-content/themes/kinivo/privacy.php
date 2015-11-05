<?php
/*
Template Name: Privacy
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			

			<div class="blog-content post-content">
				<div class="wrapper wrap">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<h1><?php echo strtoupper( get_the_title() ); ?></h1>
						<div class="post">
							<div class="body">
								<?php echo the_content(); ?>
							</div>
						</div>
					<?php endwhile; else : ?>
						<div class="blog-post-row">
							<div class="right">
								<p>No Posts here</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- End Content! -->
			<?php get_footer(); ?>