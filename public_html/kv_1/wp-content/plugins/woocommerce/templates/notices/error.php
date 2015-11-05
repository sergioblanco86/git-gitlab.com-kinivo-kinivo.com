<?php
/**
 * Show error messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>
<div class="woocommerce-error">
	<?php foreach ( $messages as $message ) : ?>
		<?php echo wp_kses_post( $message ); ?>
	<?php endforeach; ?>
</div>