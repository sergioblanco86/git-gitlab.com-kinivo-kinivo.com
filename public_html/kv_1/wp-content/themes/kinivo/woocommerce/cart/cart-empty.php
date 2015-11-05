<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wc_print_notices();

?>

<div class="shopping-cart-content empty-cart">
	<div class="wrapper wrap">
		<h1 class="main-title">Shopping Cart</h1>
		
		<div class="cart">
			<span>Your Cart is Empty</span>
			<?php do_action( 'woocommerce_cart_is_empty' ); ?>
			<a class="button green standar-nowidth wc-backward" href="<?php bloginfo('url'); ?>">Return to Home</a>
		</div>
		<div class="cart-shipping">
			<div class="box responsive-cart empty-cart">
				<span>Your Cart is Empty</span>
				<?php do_action( 'woocommerce_cart_is_empty' ); ?>
				<p class="return-to-shop"><a class="button green standar-nowidth wc-backward" href="<?php bloginfo('url'); ?>">Return to Home</a></p>
			</div>
		</div>
	</div>
</div>

<div class="suggested-products">
		<h1>You May Also Like</h1>
		<div class="wrapper wrap">
			<div id="carousel-featured" class="suggested-products-slide flexslider">
				<!-- <a class="suggested-products-arrows left-arrow"></a>
		 		<a class="suggested-products-arrows right-arrow"></a> -->
				<ul class="slides">
					<?php
						$args = array(
							'post_type' => 'product',
							'meta_key' => '_featured',
							'meta_value' => 'yes',
							'posts_per_page' => 15
						);
						$loop = new WP_Query( $args );
						if ( $loop->have_posts() ) {
							while ( $loop->have_posts() ) : $loop->the_post();
								woocommerce_get_template_part( 'content', 'productfeatured' );
							endwhile;
						} else {
					?>	
						<li>No products here</li>	
					<?php
						}
						wp_reset_postdata();
					?>
		 		</ul>
			</div>
		</div>
	</div>