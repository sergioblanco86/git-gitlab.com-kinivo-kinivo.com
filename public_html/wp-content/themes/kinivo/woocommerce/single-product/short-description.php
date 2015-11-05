<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

if ( ! $post->post_excerpt ) return;
?>
<p itemprop="description" class="description">
	<?php echo  $post->post_excerpt; ?>
	<ul class="colors"></ul>
	<a class="reset_variations2">Undo</a>
</p>