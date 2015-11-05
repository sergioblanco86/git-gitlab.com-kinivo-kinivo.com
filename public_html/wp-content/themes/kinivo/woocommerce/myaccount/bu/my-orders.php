<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'posts_per_page' => $order_count,
	'meta_key'    => '_customer_user',
	'paged'       => $paged,
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

$customer_orders_num = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );



?>


	<table class="shop_table my_account_orders information-table">

		<thead>
			<tr>
				<th>Order Information</th>
				<th>Products Ordered</th>
				<th>
					<!-- <select name="" id="">
						<option value="">View All History</option>
						<option value="">View EX</option>
					</select> -->
				</th>
			</tr>
		</thead>

		<tbody><?php
		if ( $customer_orders ) :
			
			foreach ( $customer_orders as $customer_order ) {
				$order      = wc_get_order();
				$order->populate( $customer_order );
				$item_count = $order->get_item_count();

				?><tr>

					<td>
						<div>
							<span>Order Placed</span>
							<span class="order-placed"><time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time></span>
						</div>
						<div>
							<span>Order Number</span>
							<span><b><a class="show-modal" data-modal="view-detail" data-orderid="<?php echo $order->get_order_number(); ?>"><?php echo $order->get_order_number(); ?></a></b></span>
						</div>
						<div>
							<span>Order Status</span>
							<span><b><?php echo wc_get_order_status_name( $order->get_status() ); ?></b></span>
						</div>
						<div>
							<span><b>Total:</b> <?php echo sprintf( _n( '%s for %s item', '%s for %s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); ?></span>
						</div>
					</td>
					<td>
						<?php
						if ( sizeof( $order->get_items() ) > 0 ) {

							foreach( $order->get_items() as $item ) {
								$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
								$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );

								?>


								<?php
									$prod_name = '';
									if ( $_product && ! $_product->is_visible() )
										$prod_name .= apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
									else
										$prod_name .=  $item['name'];

									$prod_name .= apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );

									$image = wp_get_attachment_image_src( get_post_thumbnail_id( $item['product_id'] ) );
								?>
								<div class="history-product">
									<img src="<?php echo $image[0]; ?>" class="pr" alt="">
									<div class="product-info">
										<h1><?php echo $prod_name; ?></h1>
										<a href="<?php echo get_permalink( $item['product_id'] ); ?>">
											<img src="<?php echo get_template_directory_uri(); ?>/img/icn/small-car.png" alt="">BUY AGAIN
										</a>
									</div>
								</div>

								<?php
									// $item_meta->display();

									if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

										$download_files = $order->get_item_downloads( $item );
										$i              = 0;
										$links          = array();

										foreach ( $download_files as $download_id => $file ) {
											$i++;

											$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
										}

										echo '<br/>' . implode( '<br/>', $links );
									}
								?>
								<?php

									}
								}

								do_action( 'woocommerce_order_items_table', $order );
								?>
								
					</td>
					<td class="order-actions">
						<?php
							$actions = array();

							if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['pay'] = array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								);
							}

							if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ),
									'name' => __( 'Cancel', 'woocommerce' )
								);
							}

							$actions['view'] = array(
								'url'  => $order->get_view_order_url(),
								'name' => __( 'View', 'woocommerce' )
							);

							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

							if ($actions) {
								foreach ( $actions as $key => $action ) {
									echo '<a  class="button ' . sanitize_html_class( $key ) . ' green standar-nowidth show-modal" data-modal="view-detail" data-orderid="'.$order->get_order_number().'">' . esc_html( $action['name'] ) . '</a>';
								}
							}
						?>
							<div class="order-h-detail" style="display:none;" data-orderid="<?php echo $order->get_order_number(); ?>">

								<a class="close-modal cross"></a>
								<table class="information-table">
									<thead>
										<tr>
											<th>Order Details</th>
											<th>Products Ordered</th>
											<th>Order Summary</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div>
													<span>Order Placed</span>
													<span class="order-placed"><time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time></span>
												</div>
												<div>
													<span>Order Number</span>
													<span><?php echo $order->get_order_number(); ?></span>
												</div>
												<div>
													<span>Order Status</span>
													<span><?php echo wc_get_order_status_name( $order->get_status() ); ?></span>
												</div>
												<div>
													<a class="button green standar-nowidth">return/questions</a>
												</div>
											</td>
											<td>
												<div class="modal-products-scroll">
													<?php
													if ( sizeof( $order->get_items() ) > 0 ) {

														foreach( $order->get_items() as $item ) {
															$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
															$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );

															?>


															<?php
																$prod_name = '';
																if ( $_product && ! $_product->is_visible() )
																	$prod_name .= apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
																else
																	$prod_name .=  $item['name'];

																$prod_name .= apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );

																$image = wp_get_attachment_image_src( get_post_thumbnail_id( $item['product_id'] ) );
															?>

															<div class="history-product">
																<img src="<?php echo $image[0]; ?>" class="pr" alt="">
																<div class="product-info">
																	<h1><?php echo $prod_name; ?></h1>
																	<span class="price"><?php echo get_woocommerce_currency_symbol().$_product->get_price(); ?></span>
																</div>
															</div>

															<?php
																// $item_meta->display();

																if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

																	$download_files = $order->get_item_downloads( $item );
																	$i              = 0;
																	$links          = array();

																	foreach ( $download_files as $download_id => $file ) {
																		$i++;

																		$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
																	}

																	echo '<br/>' . implode( '<br/>', $links );
																}
															?>
													<?php

														}
													}

													do_action( 'woocommerce_order_items_table', $order );
													?>
												</div>
											</td>
											<td>
												<h2><?php if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->billing_first_name.' '.$order->billing_last_name; ?></h2>
												<p>
													<?php if ( ! $order->get_formatted_billing_address() ){ _e( 'N/A', 'woocommerce' );}else{
														echo $order->billing_address_1.'<br />';
														echo $order->billing_city.'<br />';
														echo $order->billing_state.'<br />';
														echo $order->billing_postcode.'<br />';
														echo $order->billing_country;
													} ?>
												</p>
												<table class="totals">
													<?php
														if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
															?>
															<tr>
																<td scope="row"><?php echo $total['label']; ?></td>
																<td><?php echo $total['value']; ?></td>
															</tr>
															<?php
														endforeach;
													?>
													<!-- <tr>
														<td>ORDER SUBTOTAL:</td>
														<td>$92.27</td>
													</tr>
													<tr>
														<td>SHIPPING:</td>
														<td>FREE</td>
													</tr>
													<tr>
														<td>PROMO DISCOUNT:</td>
														<td></td>
													</tr>
													<tr>
														<td>SALE TAX:</td>
														<td>$0.00</td>
													</tr>
													<tr>
														<td>GRAND TOTAL:</td>
														<td>$92.27</td>
													</tr> -->
												</table>
											</td>
										</tr>
									</tbody>
								</table>
										
							</div>
						
					</td>
				</tr><?php
			}
		?>
	<?php endif; ?>
	</tbody>

	</table>
	<?php
		$pages = ceil(count($customer_orders_num)/$order_count);
		$prev = $paged - 1;
		$prev_link = get_bloginfo('url').'/order-history/page/'.$prev;
		$next = $paged + 1;
		$next_link = get_bloginfo('url').'/order-history/page/'.$next;
	?>
	<div class="top">
		<?php if($paged > 1){?>
		<a class="prev" href="<?php echo $prev_link; ?>"></a>
		<?php }?>
		<span class="pages">Page <?php echo $paged; ?> - <?php echo $pages; ?></span>
		<?php if($paged < $pages){?>
		<a class="next" href="<?php echo $next_link; ?>"></a>
		<?php }?>
	</div>



