<?php
/*
Template Name: Multiple Address
*/
?>
<?php get_header(); 

// Retrieve The Post's Author ID
$author_id = $GLOBALS['current_user']->ID;
// Set the image size. Accepts all registered images sizes and array(int, int)
$size = 'thumbnail';

// Get the image URL using the author ID and image size params
$imgURL = get_cupp_meta($author_id, $size);
?>
<?php do_action( 'woocommerce_before_my_account' ); ?>
<div class="contentWeb">
			
			<div class="my-account-content">
				<div class="wrapper wrap">
					<div class="bread">Home / My Account</div>
					<div class="dash">
						<div class="top">
							<?php get_template_part('content','menuresponsive-myaccount'); ?>
							<h1>Shipping Addresses</h1>
							<div class="search">
								<input type="text" placeholder="TITLE, DEPARTMENT, RECIPIENT">
								<input type="submit" value="search">
							</div>
						</div>
						<?php get_template_part('content','menu-myaccount'); ?>
						<div class="personal-information">
							<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile; else : ?>
								
								<p>No Posts here</p>
								

							<?php endif; ?>
						</div>
						
					</div>
				</div>
			</div>
<?php do_action( 'woocommerce_after_my_account' ); ?>	
<?php get_footer(); ?>