<?php
/*
Template Name: Login Form
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

?>

<?php do_action('woocommerce_before_customer_login_form'); ?>

<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>
	<div class="login-arrow arrow"></div>
	<div class="tooltiptheme login hidden">
<?php endif; ?>

		<h2>Log In to your Profile <?php _e( 'Login', 'woocommerce' ); ?></h2>
		<form method="post" class="login">
			<?php do_action( 'woocommerce_login_form_start' ); ?>
			<input type="text" placeholder="YOUR E-MAIL ADDRESS" name="username" id="username" />
			<input type="text" placeholder="PASSWORD" name="password" id="password" />
			<fieldset>
				<input type="checkbox" name="rememberme" id="rememberme" value="forever" />
				<label for="rememberme">Remember me</label>
			</fieldset>
			<!-- <a class="button standar green" href="#">LOG IN</a> -->
			<input type="submit" class="button" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" />
			<a class="button standar white" href="#">CREATE ACCOUNT</a>
			<?php do_action( 'woocommerce_login_form_end' ); ?>
		</form>
	</div>