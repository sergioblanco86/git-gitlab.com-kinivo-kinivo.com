<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $flatsome_opt;

 wc_print_notices();

?>

<?php do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
	
	<div class="shopping-cart-content">
		<div class="wrapper wrap">
			<h1 class="main-title">Shopping Cart</h1>
			
			<div class="cart">
				<div class="cart-header">
					<span>Items</span>
					<span>Price</span>
					<span>Quantity</span>
				</div>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>

					<div class="cart-product">
						<div class="cart-shape">
							<!-- <img src="<?php echo get_template_directory_uri(); ?>/img/misc/shopping-dummy-prod.jpg" alt=""> -->
							<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $_product->is_visible() )
									echo $thumbnail;
								else
									printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
							?>
							<span class="cart-prod-name">
								<?php
									if ( ! $_product->is_visible() )
										echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
									else
										echo apply_filters( 'woocommerce_cart_item_name', sprintf( '%s', $_product->get_title() ), $cart_item, $cart_item_key );

									// Meta data
									// echo WC()->cart->get_item_data( $cart_item );

		               				// Backorder notification
		               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
		               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
								?>
							</span>
							<!-- <span class="cart-prod-color">Color: Black</span> -->
						</div>
						<div class="cart-shape">
							<span class="cart-prod-price">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								?>
							</span>
						</div>
						<div class="cart-shape">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '<span class="cart-prod-quantity">1</span> <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									), $_product, false );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
						</div>
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove cart-trashcan" title="%s"></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
						?>
					</div>

					<?php
					}
				}

				do_action( 'woocommerce_cart_contents' );
				?>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				
				<div class="cart-grand-total">
					<?php woocommerce_cart_totals(); ?>
					<input type="submit" class="submit-button green standar-nowidth" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
					<input type="submit" class="submit-button green standar-nowidth" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />
				</div>
			</div>

			<div class="cart-shipping">
				<?php do_action('woocommerce_cart_collaterals'); ?>
				<div class="box responsive-cart">
					<h1>Shopping Cart</h1>
					<div class="box-cont">
						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							?>

							<div class="product-row">
								<div class="pic-and-number">
									<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $_product->is_visible() )
											echo $thumbnail;
										else
											printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
									?>
									<div class="more-less">
										<?php
											if ( $_product->is_sold_individually() ) {
												$product_quantity = sprintf( '<span class="cart-prod-quantity">1</span> <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
											} else {
												$product_quantity = woocommerce_quantity_input( array(
													'input_name'  => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
												), $_product, false );
											}

											echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
										?>
									</div>
								</div>
								<div class="delete-and-info">
									<h2 class="product-name">
										<?php
											if ( ! $_product->is_visible() )
												echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
											else
												echo apply_filters( 'woocommerce_cart_item_name', sprintf( '%s', $_product->get_title() ), $cart_item, $cart_item_key );

				               				// Backorder notification
				               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
				               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
										?>
									</h2>
									<?php echo WC()->cart->get_item_data( $cart_item ); ?>
									<span>Price: <span class="price-info"> <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?> </span></span>
									<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove delete-can" title="%s"></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
									?>
								</div>
							</div>

						<?php
							}
						}
						?>
					</div>
				</div>

				

				<div class="box calculate-shipping">
					<?php woocommerce_shipping_calculator(); ?>
				</div>

				<!-- <div class="box shipping-method">
					<h1>Shipping Method</h1>
					<div class="box-cont with-table">
						<table class="cart-shipping-table">
							<tr>
								<td>
									<label for="radiobtn-opt-1"><input id="radiobtn-opt-1" type="radio" name="shp-opt"> Standard Shipping</label>
								</td>
								<td>
									<span>FREE</span>
									Received Within 3 Business Days
								</td>
							</tr>
							<tr>
								<td>
									<label for="radiobtn-opt-2"><input id="radiobtn-opt-2" type="radio" name="shp-opt"> 2-Day Shipping</label>
								</td>
								<td>
									<span>$22.99</span>
									Received Within 3 Business Days
								</td>
							</tr>
							<tr>
								<td>
									<label for="radiobtn-opt-3"><input id="radiobtn-opt-3" type="radio" name="shp-opt"> Overnight Shipping</label>
								</td>
								<td>
									<span>$33.99</span>
									Received Within 3 Business Days
								</td>
							</tr>
						</table>
					</div>
				</div> -->
				
				<?php do_action('woocommerce_proceed_to_checkout'); ?>
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="box promo-code">
						<h1>Do you have a promo Code?</h1>
						<div class="box-cont">
							<fieldset>
								<input type="text" name="coupon_code"  id="coupon_code" value="" placeholder="ENTER YOUR CODE">
								<input type="submit" class="submit-button green standar-nowidth" name="apply_coupon" value="<?php _e( 'APPLY', 'woocommerce' ); ?>" />
								<?php do_action('woocommerce_cart_coupon'); ?>
							</fieldset>
						</div>
					</div>
				<?php } ?>

				<div class="calculate-responsive small-bottom">
					<?php woocommerce_cart_totals(); ?>
					<!-- <span>Stimate Shipping Total:</span>
					<span>$74.99</span> -->
				</div>

				<div class="calculate-responsive small-bottom no-top">
					<input type="submit" class="submit-button green standar-nowidth" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
					<input type="submit" class="submit-button green standar-nowidth" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="suggested-products">
		<h1>You May Also Like</h1>
		<div class="wrapper wrap">
			<div id="carousel-featured" class="suggested-products-slide flexslider">
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

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>