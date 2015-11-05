<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>


<div class="step3-content-order" style="display:none;">
<?php
if ( $order ) : ?>
<script type="text/javascript">
	jQuery(function($){
		jQuery(".shipping-box").removeClass("loader");
	});
</script>
	<?php if ( $order->has_status( 'failed' ) ) : ?>
	<div class="box clear">
		<!-- single-box -->
		<div class="single-box">
					<h1 class="thank-ok"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Order failed', 'woocommerce' ), $order ); ?></h1>
			<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

			<p><?php
				if ( is_user_logged_in() )
					_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
				else
					_e( 'Please attempt your purchase again.', 'woocommerce' );
			?></p>

			<p>
				<!-- <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a> -->
				<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	</div>
	<?php else : ?>
		<div class="box clear">
			<div class="single-box">
				<h1 class="thank-ok"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you for the order', 'woocommerce' ), $order ); ?></h1>
				<?php
					if ( !is_user_logged_in() ){
				?>
					<!-- <p class="create-account-pop">Please create an <a href="<?php bloginfo('url'); ?>/my-account">account</a> if you would like to manage this order</p> -->
				<?php
					}
				?>
				<p>You will receive an order confirmation email at <strong><?php echo $order->billing_email; ?></strong> within a few minutes. Please note that the payment has been processed by our subsidiary - BlueRigger LLC. Orders are usually shipped within 24 business hours. If you have any questions or concerns, please <a href="<?php bloginfo('url'); ?>/contact">contact</a> our friendly support team.</p>


			</div>
		</div>

		<div class="box clear order-details-mobile">
			<div class="single-box">
				<h2>Order Details</h2>
				<p>
					<strong><?php _e( 'Order Number: ', 'woocommerce' ); ?></strong>
					<?php echo $order->get_order_number(); ?><br />
					<strong><?php _e( 'Date: ', 'woocommerce' ); ?></strong>
					<?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?><br />
					<strong><?php _e( 'Order Total: ', 'woocommerce' ); ?></strong>
					<?php echo $order->get_formatted_order_total(); ?>
				</p>
				<h2>Shipping Information</h2>
				<p>
					<?php
						if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address();
					?>
					<br /><?php if (trim($order->billing_phone) != '') {
						echo 'Phone: '.$order->billing_phone;
					} ?>
				</p>
				<h2>Billing Information</h2>

				<ul class="order_details">
					<?php if ( $order->payment_method_title ) : ?>
					<li class="method">

						<?php //_e( 'Payment method: ', 'woocommerce' ); ?>
						<?php
							if ($order->payment_method_title == 'Amazon') {
						?>
								<img class="payment-image" src="<?php echo get_template_directory_uri(); ?>/img/misc/amazon_checkout_s1.png" width="130" />
						<?php
							}else{
								if ($order->payment_method_title == 'PayPal Express') {
						?>
									<img class="payment-image" src="<?php echo get_template_directory_uri(); ?>/img/misc/paypal_checkout_s1.png" width="130" />
						<?php
								}
							}
						?>
						<!-- <strong><?php echo $order->payment_method_title; ?></strong> -->
					</li>
					<?php endif; ?>
					<li class="method">
						<strong><?php _e( 'Account: ', 'woocommerce' ); ?></strong>
						<?php echo $order->billing_email; ?>
					</li>
				</ul>
				<p></p>
			</div>
		</div>

		<div class="box clear order-details-desktop">
			<h1 class="col3">Order Details</h1>
			<h1 class="col3 middle-col">Shipping Information</h1>
			<h1 class="col3">Billing Information</h1>
			<div class="box-cont col3 col-match">
				<ul class="order_details">
					<li class="order">
						<strong><?php _e( 'Order Number: ', 'woocommerce' ); ?></strong>
						<?php echo $order->get_order_number(); ?>
					</li>
					<li class="date">
						<strong><?php _e( 'Date: ', 'woocommerce' ); ?></strong>
						<?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?>
					</li>
					<li class="total">
						<strong><?php _e( 'Order Total: ', 'woocommerce' ); ?></strong>
						<?php echo $order->get_formatted_order_total(); ?>
					</li>
				</ul>
			</div>
			<div class="box-cont col3 middle-col col-match">
				<address>
					<?php
						if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address();
					?>
					<br /><?php if (trim($order->billing_phone) != '') {
						echo 'Phone: '.$order->billing_phone;
					} ?>
				</address>
			</div>
			<div class="box-cont col3 col-match">
				<ul class="order_details">
					<?php if ( $order->payment_method_title ) : ?>
					<li class="method">
						<?php //_e( 'Payment method: ', 'woocommerce' ); ?>
						<?php
							if ($order->payment_method_title == 'Amazon') {
						?>
								<img src="<?php echo get_template_directory_uri(); ?>/img/misc/amazon_checkout_s1.png" width="130" />
						<?php
							}else{
								if ($order->payment_method_title == 'PayPal Express') {
						?>
									<img src="<?php echo get_template_directory_uri(); ?>/img/misc/paypal_checkout_s1.png" width="130" />
						<?php
								}
							}
						?>
						<!-- <strong><?php echo $order->payment_method_title; ?></strong> -->
					</li>
					<?php endif; ?>
					<!-- <li class="method">
						<strong><?php _e( 'Account: ', 'woocommerce' ); ?></strong>
						<?php echo $order->billing_email; ?>
					</li> -->
				</ul>
			</div>
		</div>

		<div class="cart thankyou-cart-details-mobile">
			<div class="box responsive-cart desktop-cart thank-you-cart">
				<h1 class="multi-col">
					<span class="cart-col">Products</span>
				</h1>


				<div class="box-cont">
					<?php
					if ( sizeof( $order->get_items() ) > 0 ) {

						foreach( $order->get_items() as $item ) {
							$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
							$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );

							?>

						<div class="product-row">
							<div class="pic-and-number item cart-col">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $_product->is_visible() )
										echo $thumbnail;
									else
										printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
								?>
							</div>

							<div class="delete-and-info item cart-col">
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
								<br />
								<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?> <span>X</span> <span><?php echo $item['qty']; ?></span>

							</div>

							<div class="item cart-col">
								<?php echo $order->get_formatted_line_subtotal( $item ); ?>
							</div>
						</div>

					<?php
						}
					}
					?>
				</div>
			</div>
		</div>

		<div class="cart thankyou-cart-details-desktop">
			<div class="box responsive-cart desktop-cart thank-you-cart">
				<h1 class="multi-col">
					<span class="cart-col"></span>
					<span class="cart-col">Products</span>
					<span class="cart-col">Unit Price</span>
					<span class="cart-col">Quantity</span>
					<span class="cart-col">Subtotal</span>
				</h1>


				<div class="box-cont">
					<?php
					if ( sizeof( $order->get_items() ) > 0 ) {

						foreach( $order->get_items() as $item ) {
							$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
							$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );

							?>

						<div class="product-row">
							<div class="pic-and-number item cart-col">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $_product->is_visible() )
										echo $thumbnail;
									else
										printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
								?>
							</div>

							<div class="delete-and-info item cart-col">
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

							</div>

							<div class="item cart-col">
								<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
							</div>

							<div class="item cart-col">
								<span><?php echo $item['qty']; ?></span>
							</div>

							<div class="item cart-col">
								<?php echo $order->get_formatted_line_subtotal( $item ); ?>
							</div>
						</div>

					<?php
						}
					}
					?>
				</div>
			</div>
		</div>


		<div class="right">
			<div class="placeorder">
				<div class="box">
					<h1 style="color: white; b"><p>Cart Total</p></h1>
					<div class="box-cont cart-total thankyou-page-cont">

						<table celpading="0" celspacing="0">
						   <tfoot>
							   	<tr>
									<th scope="row">Order Summary</th>
									<td></td>
								</tr>
								<?php
									if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :

										if ($total['label'] == "Shipping:") {
											$split_shipping = explode("|", $total['value']);
											$sipping_label = $split_shipping[1];
											$sipping_cost = $split_shipping[0];
										?>
											<tr>
												<th scope="row" style="font-weight:normal;"><?php echo $sipping_label; ?></th>
												<td><?php echo $sipping_cost; ?></td>
											</tr>
										<?php
										}else{
										?>
										<tr>
											<th scope="row" style="font-weight:normal;"><?php echo $total['label']; ?></th>
											<td><?php echo $total['value']; ?></td>
										</tr>
										<?php
										}
									endforeach;
								?>
							</tfoot>
						</table>

					</div>
				</div>
			</div>

			<div class="kinivo-steps nav-bto home-page-bto">
				<a class="bto" href="<?php bloginfo('url'); ?>">GO TO HOMEPAGE</a>
			</div>
		</div>



		<!-- Facebook Conversion Code for Kinivo Checkouts -->
		<script>(function() {
		  var _fbq = window._fbq || (window._fbq = []);
		  if (!_fbq.loaded) {
		    var fbds = document.createElement('script');
		    fbds.async = true;
		    fbds.src = '//connect.facebook.net/en_US/fbds.js';
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(fbds, s);
		    _fbq.loaded = true;
		  }
		})();
		window._fbq = window._fbq || [];
		window._fbq.push(['track', '6011251732903', {'value':'20.00','currency':'USD'}]);
		</script>
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6011251732903&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>
	<div class="single-box">
		<h1 class="thank-ok"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></h1>

		<?php
			if ( !is_user_logged_in() ){
		?>
			<!-- <p class="create-account-pop">Please create an <a href="<?php bloginfo('url'); ?>/my-account">account</a> if you would like to manage this order</p> -->
		<?php
			}
		?>
		<p>You will receive an order confirmation email at <strong>xxxxxxxxxx</strong> within a few minutes. Please note that the payment has been processed by our subsidiary - BlueRigger LLC. Orders are usually shipped within 24 business hours. If you have any questions or concerns, please <a href="<?php bloginfo('url'); ?>/contact">contact</a> our friendly support team.</p>

	</div>

	<script type="text/javascript">
		jQuery("#checkout-title-change").html('Order Complete');
	</script>

	<!-- Facebook Conversion Code for Kinivo Checkouts -->
	<script>(function() {
	  var _fbq = window._fbq || (window._fbq = []);
	  if (!_fbq.loaded) {
	    var fbds = document.createElement('script');
	    fbds.async = true;
	    fbds.src = '//connect.facebook.net/en_US/fbds.js';
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(fbds, s);
	    _fbq.loaded = true;
	  }
	})();
	window._fbq = window._fbq || [];
	window._fbq.push(['track', '6011251732903', {'value':'20.00','currency':'USD'}]);
	</script>
	<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6011251732903&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
<?php endif; ?>
</div>
