<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) return;

$info_message  = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
$info_message .= ' <a href="#" class="showlogin">' . __( 'Click here to login', 'woocommerce' ) . '</a>';
wc_print_notice( $info_message, 'notice' );
?>

<?php
	// woocommerce_login_form(
	// 	array(
	// 		'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
	// 		'redirect' => get_permalink( wc_get_page_id( 'checkout' ) ),
	// 		'hidden'   => true
	// 	)
	// );
?>

<form name="loginform" id="login-form2" class="login" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
	<?php do_action( 'woocommerce_login_form_start' ); ?>
	<p>If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.</p>
	<fieldset>
		<label for=""><?php _e( 'Username or email address', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" name="log" id="user_login" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
	</fieldset>
	<fieldset>
		<label for=""><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="password" name="pwd" id="user_pass" value="" size="20" />
	</fieldset>
	
	<fieldset>
		<input type="submit" class="button submit-button green standar-nowidth" name="wp-submit" id="wp-submit" value="Log In" />
		<label for="rememberme" class="inline">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
		</label>
	</fieldset>
	
	<input type="hidden" name="redirect_to" id="redirect_to" value="<?php bloginfo('url'); ?>/checkout" />
	<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
	<p class="status"></p>

	<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>" class="forgot-password"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>

	<?php do_action( 'woocommerce_login_form_end' ); ?>
</form>