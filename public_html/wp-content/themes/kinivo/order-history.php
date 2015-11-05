<?php
/*
Template Name: Order History
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
<?php $GLOBALS['bgimg'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<?php do_action( 'woocommerce_before_my_account' ); ?>
<div class="contentWeb">
			
			<div class="my-account-content" style="background-image:url(<?php echo $GLOBALS['bgimg'][0]; ?>);">
				<div class="wrapper wrap">
					<div class="bread">Home / My Account</div>
					<div class="dash">
						<div class="top">
							<?php get_template_part('content','menuresponsive-myaccount'); ?>
							<h1>Order History</h1>
							<!-- <div class="search">
								<input type="text" placeholder="TITLE, DEPARTMENT, RECIPIENT">
								<input type="submit" value="search">
							</div> -->
						</div>
						<?php get_template_part('content','menu-myaccount'); ?>
						<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => 5 ) ); ?>
						
					</div>
				</div>
			</div>
<?php do_action( 'woocommerce_after_my_account' ); ?>	
<?php get_footer(); ?>