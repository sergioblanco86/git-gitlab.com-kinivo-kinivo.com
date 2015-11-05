<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', "Your Kinivo order is complete!" ); ?>

<style>
	/*@media only screen and (min-width: 320px) and (max-width: 479px){
		.text-email-welcome{
			width: 100%;
		}
		.email-image{
			display: none;
			width: 0%;
		}
	}*/
</style>

<table>
	<tr>
		<td class="text-email-welcome">
			<p style="line-height:25px;"><?php printf( __( "Your recent order on Kinivo.com has been completed and is in the process of being shipped. The order details are shown below for your reference.", 'woocommerce' ), get_option( 'blogname' ) ); ?></p>
		</td>
	</tr>
	<tr>
		<td class="email-image" align="center">
			<img style="widht:100%;height:auto;" src="<?php echo get_template_directory_uri(); ?>/img/bg/complete_order_guys.jpg" alt="">
		</td>
	</tr>
</table>


<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php echo __( 'Order:', 'woocommerce' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( true, false, true ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;

					if ($total['label'] == "Shipping:") {
						$split_shipping = explode("|", $total['value']);
						$sipping_label = $split_shipping[1];
						$sipping_cost = $split_shipping[0];
									
					?>
						<tr>
							<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>">Shipping:</th>
							<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $sipping_cost; ?> | <?php echo $sipping_label; ?></td>
						</tr>
					<?php }else{ ?>
						<tr>
							<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
							<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
						</tr>
					<?php
					}
				}
			}
		?>
		
	</tfoot>
</table>

<h2><?php _e( 'Tracking Information', 'woocommerce' ); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<?php 
		//$my_order_meta = get_post_custom( $order->get_order_number());
        $FBATrackingId = get_post_meta ($order->id, "FBA_Tracking_ID", TRUE);
        $FBACarrier = get_post_meta ($order->id, "FBA_Carrier", TRUE);
	 ?>
	<tr>
		<th scope="row" style="text-align:left; border: 1px solid #eee;">Tracking ID
		
		<a style="float: right; color:#95b002;" href="http://www.google.com/search?q=Tracking <?php echo $FBATrackingId; ?> <?php  echo $FBACarrier; ?>">Track it!</a>

		</th>
		<td style="text-align:left; border: 1px solid #eee;">
			<?php  echo $FBATrackingId; ?>
		</td>
	</tr>
	<tr>
		<th scope="row" style="text-align:left; border: 1px solid #eee;">Carrier</th>
		<td style="text-align:left; border: 1px solid #eee;"><?php  echo $FBACarrier; ?></td>
	</tr>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php _e( 'Customer Details', 'woocommerce' ); ?></h2>

<?php if ($order->billing_email) : ?>
	<p><strong><?php _e( 'Email:', 'woocommerce' ); ?></strong> <?php echo $order->billing_email; ?></p>
<?php endif; ?>
<?php if ($order->billing_phone) : ?>
	<p><strong><?php _e( 'Tel:', 'woocommerce' ); ?></strong> <?php echo $order->billing_phone; ?></p>
<?php endif; ?>

<?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>

<div style="font-size:12px; color:#000000; background:#dddddd; border-radius:10px; padding:4px; margin-top:4px; margin-bottom:4px;">
	Please <a href="https://www.surveymonkey.com/r/JGLFPWM">give us feedback</a> and enter to win a free speaker. If you have questions, please call 855 454 6486 (Toll Free) or email <a href="mailto:support@kinivo.com">support@kinivo.com</A> .
</div>

<?php do_action( 'woocommerce_email_footer' ); ?>
