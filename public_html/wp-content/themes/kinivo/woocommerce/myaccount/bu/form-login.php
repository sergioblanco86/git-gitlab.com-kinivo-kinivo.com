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
			<h1>“Log In to Your Account”</h1>
			<form method="post" class="login">
				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<fieldset>
					<label for="">User name</label>
					<input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</fieldset>
				<fieldset>
					<label for="">Password</label>
					<input class="input-text" type="password" name="password" id="password" />
				</fieldset>
				<?php do_action( 'woocommerce_login_form' ); ?>
				<fieldset>
					<?php wp_nonce_field( 'woocommerce-login' ); ?>
					<input type="submit" class="button submit-button green standar-nowidth" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" /> 
					<label for="rememberme" class="inline">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
					</label>
				</fieldset>
				<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>" class="forgot-password"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>


				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

		</div>
		<div class="col">
			<h1>Create An Account</h1>
			<form method="post" class="register">

				<?php do_action( 'woocommerce_register_form_start' ); ?>

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

				<fieldset>
					<?php wp_nonce_field( 'woocommerce-register', 'register' ); ?>
					<input type="submit" class="button submit-button green standar-nowidth" name="register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
				</fieldset>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

		</div>
		<div class="col">
			<h1>Continue as a Guest?</h1>
			<p>“Don’t have an account? You can complete your order as a guest”</p>
			<a href="<?php bloginfo('url'); ?>" class="button green standar-nowidth">CONTINUE AS A GUEST</a>
		</div>
	</div>
</div>



<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
