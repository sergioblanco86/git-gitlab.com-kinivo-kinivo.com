<?php
/*
Template Name: Category Page
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	global $wp_query;
	// get the query object
	$cat_obj = $wp_query->get_queried_object();
?>
<?php get_header( 'shop' ); ?>

		<div class="contentWeb" id="contentWeb">
			<div class="banner category-banner">
				<div class="cotegory_bread bread"><span><a href="<?php bloginfo('url'); ?>">Home</a> / <?php echo ucfirst($cat_obj->name); ?></span></div>
				<?php 
					

					$args = array(
						'post_type' => 'category_featured',
						'showposts' => '1',
						'meta_key' => 'category',
						'meta_value' => $cat_obj->term_id
					);
					$the_query = new WP_Query( $args );
				?>
				<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					

					<div class="wrapper wrap">
						<div class="the-image">
							<img src="<?php echo get_field('product_big_image'); ?>" alt="">
						</div>
						
						<div class="category-addcart-banner">
							<h1><?php echo get_field('text'); ?></h1>
							<?php  
								$post_objects = get_field('product');
								foreach( $post_objects as $post_object):
							?>
								<a class="button green" href="<?php echo get_permalink($post_object->ID); ?>">VIEW PRODUCT</a>
							<?
								endforeach;
							?>
						</div>
					</div>
					

				<?php endwhile; else : ?>

					<div class="wrapper wrap">
						<div class="category-addcart-banner">
							<h1>No feaured product here</h1>
						</div>
					</div>
					<img src="<?php echo get_template_directory_uri(); ?>/img/misc/category-banner-dummy.jpg" alt="">

				<?php endif;?>

			</div>

		
			<div class="category-products">
				<div class="wrapper wrap">
					
					<?php do_action( 'woocommerce_archive_description' ); ?>
					
						<?php if ( have_posts() ) : ?>

							<?php woocommerce_product_loop_start(); ?>

								<?php woocommerce_product_subcategories(); ?>

								<?php while ( have_posts() ) : the_post(); ?>

									<?php woocommerce_get_template_part( 'content', 'product' ); ?>
								
								<?php endwhile; // end of the loop. ?>

							<?php woocommerce_product_loop_end(); ?>

							<?php
								/**
								 * woocommerce_after_shop_loop hook
								 *
								 * @hooked woocommerce_pagination - 10
								 */
								
							?>

						<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

							<?php echo 'no posts'; ?>

						<?php endif; ?>

				</div>

			</div>
			<div class="banner category-banner">
				<ul class="rslides slides1">
					<?php 

						$args = array(
							'post_type' => 'category_slide',
							'showposts' => '5',
							'meta_key' => 'category',
							'meta_value' => $cat_obj->term_id
						);
						$the_query = new WP_Query( $args );
					?>
					<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

						<li style="background-image: url('<?php echo get_field('image'); ?>');">
							<p class="cat-slider"><?php echo get_field('top_caption_text'); ?><br/><span><?php echo get_field('bottom_caption_text'); ?></span></p>
						</li>

					<?php endwhile; else : ?>

						<li style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/bg/bg0.jpg');">
							<p class="cat-slider">No Slider</p>
						</li>

					<?php endif;?>
				</ul>
			</div>
			<div class="reviews cat-review">
				<div class="wrapper wrap">
					<?php  
						$thumbnail_id = get_woocommerce_term_meta( $cat_obj->term_id, 'thumbnail_id', true );
	    				$image = wp_get_attachment_url( $thumbnail_id );
					?>
					<img src="<?php echo $image; ?>" >
					<ul class="rslides slides2">
						<?php 

							$args = array(
								'post_type' => 'category_reviews',
								'showposts' => '5',
								'meta_key' => 'category',
								'meta_value' => $cat_obj->term_id
							);
							$the_query = new WP_Query( $args );
						?>
						<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

							<li>
								<div>
									<p><?php echo get_field('text');?></p>
								</div>
							</li>

						<?php endwhile; else : ?>

							<li>
								<div>
									<p>No reviews here.</p>
								</div>
							</li>

						<?php endif;?>
					</ul>

				</div>
			</div>
			<?php get_footer( 'shop' ); ?>
			