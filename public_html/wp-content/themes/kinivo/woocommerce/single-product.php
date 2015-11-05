<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly custom


get_header('shop'); ?>

	


	<?php while ( have_posts() ) : the_post(); ?>

	
		<?php woocommerce_get_template_part( 'content', 'single-product' );  ?>
	

	<?php endwhile; // end of the loop. ?>

	<div class="banner product-banner">
			<ul class="rslides slides1">
				<?php 
					wp_reset_query();
					$args = array(
						'post_type' => 'product_slide',
						'showposts' => '5',
						'meta_query' => array(
											array(
												'key' => 'product',
												'value' => '"' . $product->id . '"',
												'compare' => 'LIKE'
											)
										)
					);
					$the_query = new WP_Query( $args );
				?>
				<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

					<li style="background-image: url('<?php echo get_field('image'); ?>');background-position:<?php echo get_field('image_focus'); ?>;">
						<div class="product-caption"><h1><?php echo get_field('top_caption_text'); ?></h1> <br /><?php echo get_field('bottom_caption_text'); ?></div>
					</li>

				<?php endwhile; else : ?>

					<li style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/bg/bg0.jpg');">
						<div class="product-caption"><h1>About Product</h1> <br />No Slides Here</div>
					</li>

				<?php endif;?>

			</ul>

			<!-- <ul class="unique-pager"> -->
				<?php 
					// wp_reset_query();
					// $args = array(
					// 	'post_type' => 'product_slide',
					// 	'showposts' => '5',
					// 	'meta_query' => array(
					// 						array(
					// 							'key' => 'product',
					// 							'value' => '"' . $product->id . '"',
					// 							'compare' => 'LIKE'
					// 						)
					// 					)
					// );
					// $the_query = new WP_Query( $args );
				?>
				<?php //if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

					<!-- <li><a href="#" style="background-image: url('<?php echo get_field('image'); ?>');"></a></li> -->

				<?php //endwhile; else : ?>

					<!-- <li><a href="#" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/bg/bg0.jpg');"></a></li> -->

				<?php //endif;?>
			<!-- </ul> -->
		</div>

		<div class="product-page-information">
			<div class="wrapper wrap">
				<?php
		 			wp_reset_query();
					$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
					
					wp_reset_query();
					$args = array(
						'post_type' => 'product_specs',
						'showposts' => '1',
						'meta_query' => array(
											array(
												'key' => 'product',
												'value' => '"' . $product->id . '"',
												'compare' => 'LIKE'
											)
										)
					);
					$the_query = new WP_Query( $args );
				?>
				<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				 <div class="the-product">
				 	<h1>Product Specifications</h1>
				 	<h2><?php the_title(); ?></h2>
				 	
				 	
				 	<?php
				 		if(get_field('images') == '' ){
			 				echo "<img src='$image_link' />";
				 		}else{
				 			$specs_images = strip_tags(get_field('images'),'<li><img>');
				 	?>
				 		
				 		<ul class="rslides spec-slides">
							<?php echo $specs_images; ?>
				 		</ul>

				 	<?
			 			}
				 	?>
				 </div>
				 <div class="product-specifications">
				 	<div class="tabs">
				 		<a class="specifications active">Product Overview</a>
				 		<a class="technical">Technical Specifications</a>
				 	</div>
				 	<div class="tab-info t-specifications">
				 		
						<ul>

							
								
								<li class="active">
					 				<table class="mult-column">
					 					<tr>
					 						<td>
					 							<?php echo  get_field('product_specs1'); ?>
					 						</td>
					 						<td>
					 							<?php echo  get_field('product_specs2'); ?>
					 						</td>
					 					</tr>
					 					<tr>
					 						<td>
					 							<?php echo  get_field('product_specs3'); ?>
					 						</td>
					 						<td>
					 							<?php echo  get_field('product_specs4'); ?>
					 						</td>
					 					</tr>
					 				</table>

					 				<table class=" mult-column one-column">
					 					<thead>
					 						<tr>
						 						<th>
						 							Product Overview
						 						</th>
						 					</tr>
					 					</thead>
					 					<tbody>
						 					<tr>
						 						<td>
						 							<?php echo  get_field('product_specs1'); ?>
						 						</td>
						 					</tr>
						 					<tr>
						 						<td>
						 							<?php echo  get_field('product_specs2'); ?>
						 						</td>
						 					</tr>
						 					<tr>
						 						<td>
						 							<?php echo  get_field('product_specs3'); ?>
						 						</td>
						 					</tr>
						 					<tr>
						 						<td>
						 							<?php echo  get_field('product_specs4'); ?>
						 						</td>
						 					</tr>
					 					<tbody>
					 				</table>


					 				<table class=" mult-column one-column technical-table">
					 					<thead>
					 						<tr>
						 						<th>
						 							Technical Specifications
						 						</th>
						 					</tr>
					 					</thead>
					 					<tbody>
						 					<tr>
						 						<td>
						 							<?php echo  get_field('technical_specs'); ?>
						 						</td>
						 					</tr>
					 					<tbody>
					 				</table>
									

					 				<p class="legend"><?php echo  get_field('footer_text'); ?></p>
					 			</li>

					 			<li class="technical-specs">
					 				<?php echo  get_field('technical_specs'); ?>		
					 			</li>
						</ul>
				 	</div>
				 	<div class="tab-info t-technical"></div>
				 </div>
				 <?php endwhile; else : ?>
					
					<div class="active">No Specs Here.</div>

				<?php endif;?>	
			</div>
		</div>
		
		<div class="reviews">
			<ul class="rslides slides2">
				<?php
					wp_reset_query();
					$args = array(
						'post_type' => 'product_review',
						'showposts' => '8',
						'meta_query' => array(
											array(
												'key' => 'product',
												'value' => '"' . $product->id . '"',
												'compare' => 'LIKE'
											)
										)
					);

					$the_query = new WP_Query( $args );
				?>
				<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<li>
						<ul>
							<?php
								for($i=1;$i<=5;$i++){
									if($i<=get_field('qualification')){
										echo '<li><span data-icon="&#xf005" aria-hidden="true" class="color"></span></li>';
									}else{
										echo '<li><span data-icon="&#xf005" aria-hidden="true"></span></li>';
									}
								}
							?>
						</ul>
						<p>“<?php the_field('content'); ?>”</p>
						<p><a href="<?php if( get_field('link') != '' ){ the_field('link'); }else{ echo 'javascript:void(0)';} ?>" target="blank"><?php echo get_field('user_name') ?></a></p>
					</li>
				<?php endwhile; else : ?>
					<li>No Reviews.</li>
				<?php endif;?>
			</ul>
		</div>

	</div>
	
	<script type="text/javascript">
		jQuery(function($){
			var notice = jQuery("div.woocommerce-message").length;
			if(notice > 0){
				var s = jQuery("div.woocommerce-message").text();
				if( s.indexOf("successfully added to your") > -1 ){
					jQuery("div.product-added").fadeIn("fast", function(){
						setTimeout(function(){ jQuery("div.product-added").fadeOut("slow") }, 5000);
					});
				}
			}
		});
	</script>

<script>
	params = location.search.split("=");
	if ( params.length > 0 ){
		params[0] = params[0].replace("?","");
waitForButton = setInterval(
	function(){
		ccbox = document.getElementsByClassName("single_add_to_cart_button");
		if ( !(ccbox === null) ){
			ccbox = ccbox[0];
			clearInterval(waitForButton);
				if ( params[0] === "offer" ){
					console.log(ccbox);
					window.history.pushState("offerParsed", "ignore", "?useOffer="+params[1]);
					setTimeout(
						function(){
							ccbox.click();
						},1000
					);
				}
				if ( params[0] === "useOffer" ){
					window.location = "http://kinivo.com/cart?useOffer="+params[1];
				}
			
		}
	}
,100);
	}
</script>

<?php get_footer('shop'); ?>