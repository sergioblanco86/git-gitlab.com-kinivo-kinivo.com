<?php
/**
 * Lost password form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php wc_print_notices(); ?>

<div class="forgot-password-content">
	<div class="wrapper wrap">
		
		
		<form method="post" class="lost_reset_password">

			<?php if( 'lost_password' == $args['form'] ) : ?>
				<h1>Forgot Your Password</h1>

		        <p>“Enter the email address associated with your Kinivo account. Once you click “Reset Password”, we’ll send you an email message containing a link to get you back in.”</p>

		        <fieldset>
		        	<label for="user_login"><?php _e( 'Username or email', 'woocommerce' ); ?></label>
		        	<input class="input-text" type="text" name="user_login" id="user_login" />
		        </fieldset>

			<?php else : ?>
				
				<h1>Restore Your Password</h1>

		        <p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?></p>

		        <fieldset>
		            <label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
		            <input type="password" class="input-text" name="password_1" id="password_1" />
		        </fieldset>
		        <fieldset>
		            <label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
		            <input type="password" class="input-text" name="password_2" id="password_2" />
		        </fieldset>

		        <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
		        <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

		    <?php endif; ?>

		    
			<fieldset>
		    <input type="submit" class="button standar-nowidth green" name="wc_reset_password" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save new password', 'woocommerce' ); ?>" />
		    <fieldset>
			<?php wp_nonce_field( $args['form'] ); ?>

		</form>
	</div>
</div>