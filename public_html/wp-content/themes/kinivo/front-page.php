<?php
/*
Template Name: Home Page
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			<?php get_template_part('loop','sliderhome'); ?>
			<div class="products">
				<?php
					$args = array(
						'post_type' => 'home_content',
						'showposts' => '1',
					);
					$the_query = new WP_Query( $args );
				?>
				<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="row row8" style="background-image:url(<?php echo get_field('content_1_image'); ?>);">
						<a class="touch-view-more"></a>
						<div class="row-description" style="background-color: rgba(149,176,2,<?php echo get_field('hover_background_color_opacity'); ?>);">
							<h1><?php echo get_field('content_1_name'); ?><span data-icon="<?php echo get_field('content_1_icon'); ?>" aria-hidden="true"></h1>
							<p><?php echo get_field('content_1_text'); ?></p>
							<a href="<?php echo get_field('content_1_link'); ?>">Check out the <span class="bold"><?php echo get_field('content_1_link_text'); ?></span></a>
							<a class="touch-close-more"></a>
						</div>
					</div>
					<div class="row row4" style="background-image:url(<?php echo get_field('content_2_image'); ?>);">
						<a class="touch-view-more"></a>
						<div class="row-description" style="background-color: rgba(149,176,2,<?php echo get_field('hover_background_color_opacity'); ?>);">
							<h1>Hang out with us on social media</h1>
							<ul class="social">
					        	<li><a target="blank" href="<?php echo $GLOBALS['facebook']; ?>" class="tw"><img src="<?php echo get_template_directory_uri(); ?>/img/icn/fb-icon.png"></a></li>
					        	<li><a target="blank" href="https://twitter.com/<?php echo $GLOBALS['twitter']; ?>" class="ig"><img src="<?php echo get_template_directory_uri(); ?>/img/icn/tw-icon.png"></a></li>
					        </ul>
					        <a class="touch-close-more"></a>
						</div>
					</div>

					<div class="row row6" style="background-image:url(<?php echo get_field('content_3_image'); ?>);">
						<a class="touch-view-more"></a>
						<div class="row-description" style="background-color: rgba(149,176,2,<?php echo get_field('hover_background_color_opacity'); ?>);">
							<h1><?php echo get_field('content_3_name'); ?><span data-icon="<?php echo get_field('content_3_icon'); ?>" aria-hidden="true"></h1>
							<p><?php echo get_field('content_3_text'); ?></p>
							<a href="<?php echo get_field('content_3_link'); ?>">Check out the <span class="bold"><?php echo get_field('content_3_link_text'); ?></span></a>
							<a class="touch-close-more"></a>
						</div>
					</div>
					<div class="row row6" style="background-image:url(<?php echo get_field('content_4_image'); ?>);">
						<a class="touch-view-more"></a>
						<div class="row-description" style="background-color: rgba(149,176,2,<?php echo get_field('hover_background_color_opacity'); ?>);">
							<h1><?php echo get_field('content_4_name'); ?><span data-icon="<?php echo get_field('content_4_icon'); ?>" aria-hidden="true"></h1>
							<p><?php echo get_field('content_4_text'); ?></p>
							<a href="<?php echo get_field('content_4_link'); ?>">Check out the <span class="bold"><?php echo get_field('content_4_link_text'); ?></span></a>
							<a class="touch-close-more"></a>
						</div>
					</div>
				<?php endwhile; else : ?>
					<div>No Home Content.</div>
				<?php endif;?>
			</div>
			<?php get_template_part('loop','review'); ?>
			<!-- End Content! -->


<div id='alertmessage' style="background:#ffcccc; border:1px solid #ff8888; position:absolute; top:100px; left:50%; margin-left:-275px; width:550px; border-radius:16px; padding:4px; box-shadow:0px 2px 2px rgba(0,0,0,0.5); text-align:center; z-index:128; display:none;">
Due to technical issues, support.kinivo.com may be be unavailable for parts of the day, or certain features may be unavailable.<BR>
Phone Support: 855-454-6486<BR>
Email Support: support@kinivo.com
</div>

			<?php get_footer(); ?>
