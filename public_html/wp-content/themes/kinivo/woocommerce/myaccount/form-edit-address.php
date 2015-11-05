<?php
/**
 * Edit address form
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $current_user;

$page_title = ( $load_address === 'billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );

$GLOBALS['load_address'] = $load_address;


get_currentuserinfo();
?>

<?php wc_print_notices(); ?>

<?php if ( ! $load_address ) : ?>

	<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php else : ?>

<div class="my-account-content" style="background-image:url(<?php echo $GLOBALS['bgimg'][0]; ?>);">
	<div class="wrapper wrap">
		<div class="bread">Home / My Account</div>
		<div class="dash">
			<div class="top">
				<?php get_template_part('content','menuresponsive-myaccount'); ?>
			</div>

			    <?php get_template_part('content','menu-myaccount'); ?>

			<div class="personal-information">
				<div class="user-information-content change-info-content">
					<form method="post">

						<h1><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h1>

						<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

						<?php foreach ( $address as $key => $field ) : ?>

							<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>

						<?php endforeach; ?>
						
						<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

						<p>
							<input type="submit" class="button submit-button green standar-nowidth" name="save_address" value="<?php _e( 'Save Address', 'woocommerce' ); ?>" />
							<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
							<input type="hidden" name="action" value="edit_address" />
						</p>

					</form>
				</div>

			</div>
		</div>
	</div>
</div>
	

<?php endif; ?>
