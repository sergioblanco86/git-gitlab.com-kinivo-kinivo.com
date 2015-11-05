<?php
session_start();
require_once('../../../wp-load.php');
?>
<?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
 

   <h1><?php echo get_option('wmc_orderinfo_label') ? __(get_option('wmc_orderinfo_label'),'woocommerce-multistep-checkout') : __('Order Information', 'woocommerce-multistep-checkout'); ?></h1>
    <section>
        
        	<table class="shop_table">
				<thead>
					<tr>
						<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
						<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
					</tr>
				</thead>
				<tfoot>

					<tr class="cart-subtotal">
						<th><strong>Order Summary</strong></th>
						<td></td>
					</tr>

					<tr class="cart-subtotal">
						<td><?php _e( 'Items('.WC()->cart->cart_contents_count.')', 'woocommerce' ); ?></td>
						<td><?php wc_cart_totals_subtotal_html(); ?></td>
					</tr>

					<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
						<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
							<td><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
							<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

						<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

						<?php wc_cart_totals_shipping_html(); ?>

						<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

					<?php endif; ?>

					<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
						<tr class="fee">
							<th><?php echo esc_html( $fee->name ); ?></th>
							<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
						<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
							<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
								<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
									<td><?php echo esc_html( $tax->label ); ?></td>
									<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr class="tax-total">
								<td><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></td>
								<td><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></td>
							</tr>
						<?php endif; ?>
					<?php endif; ?>

					<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
						<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
							<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
							<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

					<tr class="order-total">
						<td><?php _e( 'Order Total', 'woocommerce' ); ?></td>
						<td><?php wc_cart_totals_order_total_html(); ?></td>
					</tr>

					<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

				</tfoot>
				<tbody>
					<?php
						do_action( 'woocommerce_review_order_before_cart_contents' );

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								?>
								<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
									<td class="product-name">
										<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
										<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
										<?php echo WC()->cart->get_item_data( $cart_item ); ?>
									</td>
									<td class="product-total">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
									</td>
								</tr>
								<?php
							}
						}

						do_action( 'woocommerce_review_order_after_cart_contents' );
					?>
				</tbody>
			</table>
			<p><b>Your privacy is important to us.</b> KINIVO does not sell or share your personal information with third parties. Please review our privacy statement to understand the way in which Kinivo protects your personal information. Email us with questions at <a class="link-k" href="mailto:support@kinivo.com">Support@kinivo.com</a></p>
        	<?php if ( ! is_user_logged_in() ) : ?>

				<?php if ( $checkout->enable_guest_checkout ) : ?>

					<p class="form-row form-row-wide create-account check-box-create-account">
						<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account password to manage this order', 'woocommerce' ); ?></label>
					</p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

				<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

					<div class="create-account woo-create-account">

						<p class="form-row validate-required" id="billing_email_field">
							<label for="billing_email" class="">Account email <abbr class="required" title="required">*</abbr></label>
							<input type="email" class="input-text " name="billing_email" id="billing_email" placeholder="Email" value="">
						</p>

						<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

							<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

						<?php endforeach; ?>

						<div class="clear"></div>

					</div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

			<?php endif; ?>
    </section>
	

	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>
    <h1><?php echo get_option('wmc_paymentinfo_label') ? __(get_option('wmc_paymentinfo_label'), 'woocommerce-multistep-checkout') : __('Payment Info','woocommerce-multistep-checkout'); ?></h1>
    <section>
    <div id="payment">
		<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="payment_methods methods">
			<li><p>Complete your order by paying with one of the secure payment methods below. The PayPal option allows you to pay with credit card, if you don’t have a PayPal account</p></li>
			<?php
				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				if ( ! empty( $available_gateways ) ) {

					// Chosen Method
					if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$available_gateways[ WC()->session->chosen_payment_method ]->set_current();
					} elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$available_gateways[ get_option( 'woocommerce_default_gateway' ) ]->set_current();
					} else {
						current( $available_gateways )->set_current();
					}

					foreach ( $available_gateways as $gateway ) {
						?>
						<li class="payment_method_<?php echo $gateway->id; ?>">
							<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
							<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
									$gateway->payment_fields();
									echo '</div>';
								endif;
							?>
						</li>
						<?php
					}
				} else {

					if ( ! WC()->customer->get_country() )
						$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
					else
						$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );

					echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';

				}
			?>
		</ul>
		<?php endif; ?>

		<div class="form-row place-order">

			<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

			<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<?php
			$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );

			echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt submit-button green standar-nowidth" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
			?>

			<?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) { 
				$terms_is_checked = apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) );
				?>
				<p class="form-row terms">
					<label for="terms" class="checkbox"><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank">terms &amp; conditions</a>', 'woocommerce' ), esc_url( get_permalink( wc_get_page_id( 'terms' ) ) ) ); ?></label>
					<input type="checkbox" class="input-checkbox" name="terms" <?php checked( $terms_is_checked, true ); ?> id="terms" />
				</p>
			<?php } ?>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		</div>

		<div class="clear"></div>

	</div>

    </section>
	<?php do_action( 'woocommerce_review_order_after_payment' ); ?>
<script>

 jQuery(function(){
     var payment_method = jQuery(".payment_methods li input:checked").attr('id');
     jQuery("#order_review section:eq(1)").remove();
     jQuery("#order_review section:eq(1)").prev("h1").hide();
     jQuery("#order_review > h1").hide();
     if(payment_method){
         jQuery("#"+payment_method).attr("checked", "checked");
     }
     
 })
   </script>