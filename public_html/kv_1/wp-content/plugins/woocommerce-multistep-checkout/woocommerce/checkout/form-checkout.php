<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout
if (!$checkout->enable_signup && !$checkout->enable_guest_checkout && !is_user_logged_in()) {
    echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
    return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters('woocommerce_get_checkout_url', WC()->cart->get_checkout_url());
?>

    <form  name="checkout" method="post" class="checkout" action="<?php echo esc_url($get_checkout_url); ?>">
    <div id="wizard">
        <?php do_action('woocommerce_multistep-checkout-before'); ?>
        <?php if (sizeof($checkout->checkout_fields) > 0) : ?>

    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

<?php if (WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
            <h1><?php _e('Billing & Shipping', 'woocommerce') ?></h1>
            <?php else:?>
            <h1><?php echo get_option('wmc_billing_label') ? __(get_option('wmc_billing_label'), 'woocommerce-multistep-checkout') : __('Billing', 'woocommerce-multistep-checkout') ?></h1>
            <?php endif;?>
            <section>


    <?php do_action('woocommerce_checkout_billing'); ?>


            </section>
            
            <?php if (!WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
            <h1><?php echo get_option('wmc_shipping_label') ? __(get_option('wmc_shipping_label'),'woocommerce-multistep-checkout') : __('Shipping', 'woocommerce-multistep-checkout') ?></h1>
            <section>


    <?php do_action('woocommerce_checkout_shipping'); ?>

<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
            </section>
            <?php endif;?>



        <?php endif; ?>

<?php do_action('woocommerce_checkout_order_review'); ?>
<?php do_action('woocommerce_multistep-checkout-after'); ?>
    </div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>