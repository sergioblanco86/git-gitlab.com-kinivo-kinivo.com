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



?>

<?php do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
	
	<div class="shopping-cart-content" style="background-image:url(<?php echo $GLOBALS['bgimg'][0]; ?>);">
		<div class="wrapper wrap">
			
			
			<div class="cart">
				<?php wc_print_notices(); ?>
				<div class="box responsive-cart desktop-cart">
					<h1><?php echo $GLOBALS['cart_title']; ?></h1>
					<div class="box-cont">
						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							?>

							<div class="product-row">
								<div class="pic-and-number item">
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
								<div class="delete-and-info item">
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
									<?php
										$variations = WC()->cart->get_item_data( $cart_item , true);
									    $colors = preg_replace("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", "", $variations);
									    $colors = str_replace('color','',$colors);
									    $colors = str_replace(':','',$colors);
									?>

									<?php if ( WC()->cart->get_item_data( $cart_item ) ){ ?>
									<span>Color: <span class="price-info"><?php echo $colors; ?></span></span>
									<?php } ?>
									<span>Unit Price: <span class="price-info"> <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?> </span></span>
									<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove delete-can" title="%s"></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
									?>
									<input type="submit" class="submit-button green standar-nowidth cart-btos update-cart-submit" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
								</div>
							</div>

						<?php
							}
						}
						?>
					</div>
				</div>
				<?php
				  do_action( 'woocommerce_cart_contents' );
				?>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				
				<div class="cart-grand-total">
					
					
					<!-- <input type="submit" class="submit-button green standar-nowidth cart-btos" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" /> -->
					
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
								<div class="pic-and-number item">
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
								<div class="delete-and-info item">
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
									<?php
										$variations = WC()->cart->get_item_data( $cart_item , true);
									    $colors = preg_replace("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", "", $variations);
									    $colors = str_replace('color','',$colors);
									    $colors = str_replace(':','',$colors);
									?>

									<?php if ( WC()->cart->get_item_data( $cart_item ) ){ ?>
									<span>Color: <span class="price-info"><?php echo $colors; ?></span></span>
									<?php } ?>
									<span>Unit Price: <span class="price-info"> <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?> </span></span>
									<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove delete-can" title="%s"></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
									?>
									<input type="submit" class="submit-button green standar-nowidth cart-btos update-cart-submit" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
								</div>
							</div>

						<?php
							}
						}
						?>
					</div>
				</div>

				<div class="box cart-grand-total">
					<?php woocommerce_cart_totals(); ?>
				</div>

				<!-- <div class="box calculate-shipping">
					<?php woocommerce_shipping_calculator(); ?>
				</div> -->

			
				
				
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="box promo-code">
						<h1><?php echo $GLOBALS['promo_code_title']; ?></h1>
						<div class="box-cont">
							<fieldset>
								<input type="text" name="coupon_code"  id="coupon_code" value="" placeholder="Enter promo code">
								<input type="submit" class="submit-button green standar-nowidth" name="apply_coupon" value="<?php _e( 'APPLY', 'woocommerce' ); ?>" />
								<?php do_action('woocommerce_cart_coupon'); ?>
							</fieldset>
						</div>
					</div>
				<?php } ?>

				<div class="box secure-payment">
					<h1><?php echo $GLOBALS['cart_payment_title']; ?>
						<div class="question-mark">?
								<div class="pop">
									<span class="close">X</span>
									<?php echo $GLOBALS['cart_payment_question_mark']; ?>
								</div>
						</div>
					</h1>
					<div class="box-cont">
						<div class="payment-general-legend"><?php echo $GLOBALS['payment_general_legend']; ?></div>
						<div class="amazon-l legend"><?php echo $GLOBALS['amazon_legend']; ?></div>
						<div class="pay-pal-l legend"><?php echo $GLOBALS['paypal_legend']; ?></div>
						<div class="pay-pal-l legend">
							<p><img src="<?php echo get_template_directory_uri(); ?>/img/misc/paypalcards.jpg" alt=""></p>
						</div>
						<?php do_action('woocommerce_proceed_to_checkout'); ?>
					</div>
				</div>

				<!-- <div class="calculate-responsive small-bottom">
					<?php woocommerce_cart_totals(); ?>
				</div> -->

				<div class="calculate-responsive small-bottom no-top">
					<!-- <input type="submit" class="submit-button green standar-nowidth" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" /> -->
				</div>
			</div>
		</div>
	</div>

	<div class="suggested-products">
		<h1>You May Also Like…</h1>
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