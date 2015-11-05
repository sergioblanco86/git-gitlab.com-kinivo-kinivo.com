<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>


		
<div class="checkout-login-content">
	<div class="wrapper wrap">
		<div class="col">
			<h1><?php _e( 'Log in to your account', 'woocommerce' ); ?></h1>
			<form name="loginform" id="login-form2" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
				<?php do_action( 'woocommerce_login_form_start' ); ?>
				<fieldset>
					<label for=""><?php _e( 'Username or email address', 'woocommerce' ); ?><span class="required">*</span></label>
					<input type="text" name="log" id="user_login" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</fieldset>
				<fieldset>
					<label for=""><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" name="pwd" id="user_pass" value="" size="20" />
				</fieldset>
				<p class="required-text"><span>*</span> Indicates required field.</p>
				<fieldset>
					<input type="submit" class="button submit-button green standar-nowidth" name="wp-submit" id="wp-submit" value="Log In" />
					<label for="rememberme" class="inline">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
					</label>
				</fieldset>
				
				<input type="hidden" name="redirect_to" id="redirect_to" value="<?php bloginfo('url'); ?>/my-account/" />
				<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
				<p class="status"></p>

				<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>" class="forgot-password"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>

				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>
		</div>
		<div class="col">
			<h1><?php _e( 'Create an account', 'woocommerce' ); ?></h1>
			<form method="post" class="register">

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<p>Create an account today to track your orders and get access to exclusive deals and promotions.</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<fieldset>
						<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
					</fieldset>

				<?php endif; ?>

				<fieldset>
					<label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
				</fieldset>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
		
					<fieldset>
						<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="password" class="input-text" name="password" id="reg_password" />
					</fieldset>

				<?php endif; ?>

				<!-- Spam Trap -->
				<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

				<?php do_action( 'woocommerce_register_form' ); ?>
				<?php do_action( 'register_form' ); ?>
				<p class="required-text"><span>*</span> Indicates required field.</p>
				<p>By clicking to create an account, you accept our <a href="<?php bloginfo('url'); ?>/privacy-policy/" class="normal-link" target="blank">terms of use policy.</a></p>

				<fieldset>
					<?php wp_nonce_field( 'woocommerce-register' ); ?>
					<input type="submit" class="button submit-button green standar-nowidth" name="register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
				</fieldset>

				<?php do_action( 'woocommerce_register_form_end' ); ?>



			</form>

		</div>
		<div class="col">
			<h1>Continue as a guest?</h1>
			<p>Don’t have an account? You can complete your order as a guest.</p>
			<a href="<?php bloginfo('url'); ?>" class="button green standar-nowidth">CONTINUE AS A GUEST</a>
		</div>
	</div>
</div>



<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
