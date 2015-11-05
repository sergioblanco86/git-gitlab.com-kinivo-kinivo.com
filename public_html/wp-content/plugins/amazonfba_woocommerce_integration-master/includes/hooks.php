<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// On Payment Complete
add_action('woocommerce_payment_complete', 'CreateAmzFBAOrder');
add_action('woocommerce_order_status_on-hold','SendOnHoldEmail');
?>
