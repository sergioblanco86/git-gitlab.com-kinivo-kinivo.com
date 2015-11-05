<?php
/*
Template Name: Check Out
*/
$amazon_payment_start = '';
?>
<?php get_header(); ?>
<?php $GLOBALS['bgimgCheck'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
		<!-- Content! -->
		<?php if($_GET['amazon_payments_advanced']){ ?>
		<style>
			#wizard .col2-set{
				margin-bottom: 20px;
			}

			#wizard h1:nth-of-type(1), #wizard h1:nth-of-type(2){
				display: none;
			}

			#payment .payment_methods.methods{
				margin-bottom: 20px;
				width: 100%;
				background-color: #FFF;
				padding: 20px;
				box-sizing: border-box;
				border-radius: 10px;
				margin-top: 20px;
			}

			.payment_method_amazon_payments_advanced img{
				position: absolute;
				margin-top: -3px;
				margin-left: 5px;
			}
		</style>
		<?php } ?>

		<div class='load_overlay' style='position: fixed; background-color:rgba(191,191,191,0.6); width:100%; height:100%; width:100%; ;left:0; top:0; opacity:1; z-index:9999;'>
			<div style="color:#353535;font-size: 14px; width:116px; height:auto; text-align: center; position: absolute; left:50%; top:50%; margin-left:-58px; margin-top: -20px;">
				<img style="display:inline-block; position: relative; margin-bottom:10px;" src="<?php echo get_template_directory_uri(); ?>/img/misc/circular-loading.GIF" width="40" height="40" alt="">
			</div>
		</div>
		<div class="contentWeb">

			


			<div class="shipping-process-content <?php echo $amazon_payment_start; ?>" style="background-image:url(<?php echo $GLOBALS['bgimgCheck'][0]; ?>);">
				<div class="wrapper wrap">

					<div class="kinivo-steps">
						<?php if(isset($_GET['key'])){ ?>
						<script type="text/javascript">
						// jQuery( window ).load(function() {
						// 	jQuery(".shipping-box").addClass("loader");
						// });
						</script>
							<div class="step" data-step="1">
								<div class="number">1</div>
								<div class="name"><span>Billing & Shipping</span></div>
							</div>
							<div class="step" data-step="2">
								<div class="number">2</div>
								<div class="name"><span>Place Order</span></div>
							</div>
							<div class="step active" data-step="3">
								<div class="number">3</div>
								<div class="name"><span>Order Confirmation</span></div>
							</div>
						<?php }else{ ?>
							<?php if(isset($_GET['pp_action'])){ ?>
							<script type="text/javascript">
							// jQuery( window ).load(function() {
							// 	jQuery(".shipping-box").addClass("loader");
							// });
							</script>
								<div class="step" data-step="1">
									<div class="number">1</div>
									<div class="name"><span><img class="paypal-step1-logo" src="<?php echo get_template_directory_uri(); ?>/img/misc/paypal_checkout_s1.png" width="80" /></span></div>
								</div>
								<div class="step active" data-step="2">
									<div class="number">2</div>
									<div class="name"><span>Place Order</span></div>
								</div>
								<div class="step" data-step="3">
									<div class="number">3</div>
									<div class="name"><span>Order Confirmation</span></div>
								</div>
							<?php }else{ ?>
								<div class="step step1-head active" data-step="1">
									<div class="number">1</div>
									<div class="name"><span>Billing & Shipping</span></div>
								</div>
								<div class="step" data-step="2">
									<div class="number">2</div>
									<div class="name"><span>Place Order</span></div>
								</div>
								<div class="step" data-step="3">
									<div class="number">3</div>
									<div class="name"><span>Order Confirmation</span></div>
								</div>
							<?php } ?>
						<?php } ?>

					</div>

					<div class="content-step active" data-step="1"></div>

					<?php if( !isset($_GET['pp_action']) ){ ?>
					<div class="content-step" data-step="2">
						<div class="left">
							<?php if(!isset($_GET['amazon_payments_advanced'])){ ?>
							<div class="address">
								<div class="box clear">
									<h1 class="col2">Shipping Information <a class="link bill-shipp-info" href="<?php bloginfo('url'); ?>/cart">Modify</a></h1>
									<h1 class="col2">Billing Information <a class="link bill-shipp-info" href="<?php bloginfo('url'); ?>/cart">Modify</a></h1>
									<div id="shipping-address-content" class="box-cont col2"></div>
									<div id="billing-address-content" class="box-cont col2"></div>
								</div>
							</div>
							<?php } ?>
							<div class="cart">
								<div class="box responsive-cart desktop-cart">
									<h1>Shopping Cart <a class="link" href="<?php bloginfo('url'); ?>/cart">Modify</a></h1>

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
													<span>Quantity: <span class="price-info"><?php echo $cart_item['quantity']  ?></span></span>
													<span>Unit Price: <span class="price-info"> <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?> </span></span>


												</div>
											</div>

										<?php
											}
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="right">
							<div class="placeorder">
								<div class="box">
									<h1><p>Cart Total</p>
										<div class="question-mark">?
												<div class="pop" style="display: none;">
													<span class="close">X</span>
													<?php echo '<span class="cart_total_question_mark">'.$GLOBALS['cart_total_question_mark'].'</span>'; ?>
												</div>
										</div>
									</h1>
									<div class="box-cont cart-total review-order">
										<div class="totals-wrap"></div>
										<div class="divider"></div>
										<div class="create-account-checkboxes">
											<fieldset>
												<input type="checkbox" id="terms-and-conditions">
												<label for="terms-and-conditions">I accept Kinivo’s <a href="<?php bloginfo('url'); ?>/privacy-policy" target="_blank">terms and conditions</a> (Required)</label>
											</fieldset>
											<fieldset id="subscribe-checkbox">
												<input type="checkbox" id="subscrbe-user">
												<label for="subscrbe-user">Subscribe for exclusive e-mail offers and discounts</label>
											</fieldset>
											<?php if ( !is_user_logged_in() ) { ?>
											<fieldset class="the-create-account-box">
												<!-- <input type="checkbox" id="create-an-account">
												<label for="create-an-account">Create an account password to manage this order</label> -->
											</fieldset>
											<div class="create-account-form">

											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>

					<div class="content-step" data-step="3" <?php if(isset($_GET['key'])) echo 'style="display:block;"' ?> ></div>



					<!-- <h1 class="shipping-box-title checkout-step checkout-step-1" id="checkout-title-change">Checkout</h1> -->

					<div class="shipping-box">


						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<?php
							the_content();?>
						<?php endwhile; else : ?>
							<p>No content.</p>
						<?php endif;?>

						<!-- <div class="shipping-actions">
							<div>
							</div>

							<div>
								<b>Have a question?</b>
								<span>call 800-393-2830</span>
							</div>
							<div>
								<a href="">FAQ</a>
								<a href="">Return Policy</a>
							</div>
							<div>
								<a href="">Warranty policy</a>
								<a href="">Live chat and support</a>
							</div>
						</div> -->

					</div>

					<div class="kinivo-steps nav-bto">
						<?php if( !isset($_GET['key']) && !isset($_GET['pp_action']) ){ ?>
							<a class="bto next" data-step="1">Next</a>
							<!-- <a class="bto prev deactivated" data-step="1">Previous</a> -->
						<?php } ?>
					</div>

				</div>
			</div>

<!-- End Content! -->
<?php get_footer(); ?>
