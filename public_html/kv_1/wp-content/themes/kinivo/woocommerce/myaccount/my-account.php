<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

wc_print_notices(); 

// Retrieve The Post's Author ID
$author_id = $GLOBALS['current_user']->ID;
// Set the image size. Accepts all registered images sizes and array(int, int)
$size = 'thumbnail';

// Get the image URL using the author ID and image size params
$imgURL = get_cupp_meta($author_id, $size);

if( !isset($_GET['address_saved']) ) $_GET['address_saved'] = false;
?>


<div class="my-account-content">
	<div class="wrapper wrap">
		<div class="bread">Home / My Account</div>

		<div class="dash">
			<div class="top">
				<?php get_template_part('content','menuresponsive-myaccount'); ?>
			</div>
			
			<?php get_template_part('content','menu-myaccount'); ?>

			<div class="personal-information">
				<div class="header-btos">
					<a class="button standar-nowidth <?php if(!$_GET['address_saved']){ echo 'active';} ?>" data-content="change-info-content">Account Information</a>
					<a class="button standar-nowidth <?php if($_GET['address_saved']){ echo 'active';} ?>" data-content="address-book-content">My Address Book</a>
				</div>

				<div class="user-information-content change-info-content" <?php if($_GET['address_saved']){ echo 'style="display:none;"';} ?>>
					<h1>Change your Name, Email Address, or Password</h1>
					<p>If you wish to change the name, email address, or password associated with your Kinivo customer account, you may do so below. For your security, we require that you enter your existing password to edit any of your personal information.</p>
					<?php wc_get_template( 'myaccount/form-edit-account.php' ); ?>
					<?php do_action( 'woocommerce_before_my_account' ); ?>
				</div>
				



				<div class="user-information-content address-book-content" <?php if($_GET['address_saved']){ echo 'style="display:block;"';} ?>>
					<h1>Manage Your Address Book</h1>
					<!-- <a class="button standar-nowidth green add-address show-modal" data-modal="add-address"> add a new shipping address <span class="more">+</span></a> -->

					<div class="books">

						<?php wc_get_template( 'myaccount/my-address.php' ); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<?php do_action( 'woocommerce_after_my_account' ); ?>



<?php //wc_get_template( 'myaccount/my-downloads.php' ); ?>



<?php //wc_get_template( 'myaccount/my-address.php' ); ?>
