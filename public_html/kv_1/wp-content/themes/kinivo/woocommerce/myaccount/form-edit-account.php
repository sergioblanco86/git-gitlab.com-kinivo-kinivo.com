<?php
/**
 * Edit account form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user= $GLOBALS['current_user'];
?>

<?php wc_print_notices(); ?>

<form action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<fieldset>
		<label for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</fieldset>
	<fieldset>
		<label for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</fieldset>
	<fieldset>
		<label for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</fieldset>
	<fieldset>
		<label for="account_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">(Usernames cannot be changed)</span></label>
		<input type="text" class="input-text" name="account_username" id="account_username" value="<?php echo esc_attr( $user->user_login ); ?>" readonly="readonly" />
	</fieldset>
	<fieldset class="col2">
		<label for="password_current"><?php _e( 'Current Password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
		<input type="password" class="input-text" name="password_current" id="password_current" />
	</fieldset>
	<fieldset class="col2">
		<label for="password_1"><?php _e( 'New Password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
		<input type="password" class="input-text" name="password_1" id="password_1" />
	</fieldset>
	<fieldset class="col2">
		<label for="password_2"><?php _e( 'Confirm New Password', 'woocommerce' ); ?></label>
		<input type="password" class="input-text" name="password_2" id="password_2" />
	</fieldset>
	
	<fieldset class="col2">
		<?php do_action( 'woocommerce_edit_account_form' ); ?>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" class="submit-button green standar-nowidth" name="save_account_details" value="<?php _e( 'Save changes', 'woocommerce' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />
	</fieldset>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>
	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	
</form>