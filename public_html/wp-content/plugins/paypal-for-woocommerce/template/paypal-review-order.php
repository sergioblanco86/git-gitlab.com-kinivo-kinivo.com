<?php
/**
 * Review Order
 */

global $woocommerce;
$checked = get_option('woocommerce_enable_guest_checkout');

//Add hook to show login form or not
$show_login = apply_filters('paypal-for-woocommerce-show-login', !is_user_logged_in() && $checked==="no" && isset($_REQUEST['pp_action']));
?>
<style type="text/css">
    #payment{
        display:none;
    }
</style>


<form class="angelleye_checkout" method="POST" action="<?php echo add_query_arg( 'pp_action', 'payaction', add_query_arg( 'wc-api', 'WC_Gateway_PayPal_Express_AngellEYE', home_url( '/' ) ) );?>">

    <div class="content-step" data-step="2" style="display:block;">
        <div class="left">
            <div class="address">
                <div class="box clear">
                    <h1 class="col2">Shipping Information <a class="link bill-shipp-info" href="<?php bloginfo('url'); ?>/cart">Modify</a></h1>
                    <h1 class="col2">Billing Information <a class="link bill-shipp-info" href="<?php bloginfo('url'); ?>/cart">Modify</a></h1>
                    <div id="shipping-address-content" class="box-cont col2">
                        <p>
                        <?php
                        // Formatted Addresses
                        $address = array(
                            'first_name'    => WC()->customer->shiptoname,
                            'last_name'     => "",
                            'company'       => "",
                            'address_1'     => WC()->customer->get_address(),
                            'address_2'     => "",
                            'city'          => WC()->customer->get_city(),
                            'state'         => WC()->customer->get_state(),
                            'postcode'      => WC()->customer->get_postcode(),
                            'country'       => WC()->customer->get_country()
                        ) ;

                        echo WC()->countries->get_formatted_address( $address );
                        ?>
                        </p>
                    </div>
                    <div id="billing-address-content" class="box-cont col2">
                        <p>
                            <img style="max-width:100%;" src="<?php echo get_template_directory_uri(); ?>/img/misc/paypal_checkout_s1.png" width="130" />
                        </p>
                    </div>
                </div>
            </div>
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

    


        <div id="paypalexpress_order_review" style="display: none;">
                <?php woocommerce_order_review(); ?>
        </div>

<?php if ( WC()->cart->needs_shipping()  ) : ?>


  

    <div class="col2-set addresses">

        <div class="col-1">

            <div class="title">
                <h3><?php // _e( 'Shipping Address', 'woocommerce' ); ?></h3>
            </div>
            <div class="address">
                <p>
                    
                </p>
            </div>

        </div><!-- /.col-1 -->
        <div class="col-2">
        </div><!-- /.col-2 -->
    </div><!-- /.col2-set -->
<?php endif; ?>
<?php if ( $show_login ):  ?>
</form>
    <style type="text/css">

        .woocommerce #content p.form-row input.button,
        .woocommerce #respond p.form-row input#submit,
        .woocommerce p.form-row a.button,
        .woocommerce p.form-row button.button,
        .woocommerce p.form-row input.button,
        .woocommerce-page p.form-row #content input.button,
        .woocommerce-page p.form-row #respond input#submit,
        .woocommerce-page p.form-row a.button,
        .woocommerce-page p.form-row button.button,
        .woocommerce-page p.form-row input.button{
            display: block !important;
        }
    </style>
    <div class="title">
        <h2><?php _e( 'Login', 'woocommerce' ); ?></h2>
    </div>
    <form name="" action="" method="post">
        <?php
        function curPageURL() {
            $pageURL = 'http';
            if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }

        woocommerce_login_form(
            array(
                'message'  => 'Please login or create an account to complete your order.',
                'redirect' => curPageURL(),
                'hidden'   => true
            )
        );
        $result = unserialize(WC()->session->RESULT);
        $email = (!empty($_POST['email']))?$_POST['email']:$result['EMAIL'];
        ?>
    </form>
    <div class="title">
        <h2><?php _e( 'Create A New Account', 'woocommerce' ); ?></h2>
    </div>
    <form action="" method="post">
        <p class="form-row form-row-first">
            <label for="paypalexpress_order_review_username">Username:<span class="required">*</span></label>
            <input style="width: 100%;" type="text" name="username" id="paypalexpress_order_review_username" value="" />
        </p>
        <p class="form-row form-row-last">
            <label for="paypalexpress_order_review_email">Email:<span class="required">*</span></label>
            <input style="width: 100%;" type="email" name="email" id="paypalexpress_order_review_email" value="<?php echo $email; ?>" />
        </p>
        <div class="clear"></div>
        <p class="form-row form-row-first">
            <label for="paypalexpress_order_review_password">Password:<span class="required">*</span></label>
            <input type="password" name="password" id="paypalexpress_order_review_password" class="input-text" />
        </p>
        <p class="form-row form-row-last">
            <label for="paypalexpress_order_review_repassword">Re Password:<span class="required">*</span></label>
            <input type="password" name="repassword" id="paypalexpress_order_review_repassword" class="input-text"/>
        </p>
        <div class="clear"></div>
        <p>
            <input class="button" type="submit" name="createaccount" value="Create Account" />
            <input type="hidden" name="address" value="<?php echo WC()->customer->get_address(); ?>">
        </p>
    </form>
<?php else: ?>

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
                            <input type="hidden" id="create_user_url" value="<?php  echo get_template_directory_uri(); ?>/register_log_user.php">
                        </div>
                        <div id="new_user_messages"></div>
                        <?php } ?>
                        <?php echo '<input type="button" class="button submit-button green standar-nowidth pp_place_order" value="' . __( 'Place Order','paypal-for-woocommerce') . '" />'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <?php                 
       // echo '<div class="clear"></div>';
        //echo '<p><a class="button angelleye_cancel" href="' . $woocommerce->cart->get_cart_url() . '">'.__('Cancel order :)', 'paypal-for-woocommerce').'</a> ';
        
    ?>
    </div>
    </form><!--close the checkout form-->
<?php endif; ?>
<script>
    
    jQuery( 'input#createaccount' ).change( function() {
            jQuery( 'div.create-account' ).slideUp();
            jQuery("#subscrbe-user").attr("disabled", true);
            jQuery('#subscrbe-user').attr('checked', false);
            

            if ( jQuery( this ).is( ':checked' ) ) {
                jQuery( 'div.create-account' ).slideDown();
                jQuery("#subscrbe-user").removeAttr("disabled");
                jQuery("#subscribe-checkbox").show();
                jQuery('#subscrbe-user+label').css({"opacity":"1"});
            }
        }).change();
</script>
